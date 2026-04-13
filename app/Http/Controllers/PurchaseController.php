<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseStoreRequest;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Transaction;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct(protected VoucherService $voucher) {}

    public function index(Request $request)
    {
        $query = Purchase::with(['transaction', 'supplierAccount']);
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('transaction', fn ($query) => $query->where('voucher_no', 'like', "%{$search}%")
                )
                    ->orWhereHas('supplierAccount', fn ($query) => $query->where('name', 'like', "%{$search}%")
                    );
            });
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();

        return view('purchase.index', compact('purchases'));
    }

    public function create(Request $request)
    {
        $purchases = Purchase::all();
        $products = Product::all();
        $suppliers = Account::where('type', 'supplier')->get();

        // $suppliers=
        return view('purchase.create', [
            'purchases' => $purchases,
            'products' => $products,
            'suppliers' => $suppliers,
        ]);
    }

    public function fetchAccountsbyType(Request $request)
    {
        $accounts = Account::where('type', $request->type)
            ->select('id', 'name')
            ->get();

        return response()->json($accounts);
    }

    // public function create(Request $request): Response
    // {
    //     return view('purchase.create');
    // }

    public function store(PurchaseStoreRequest $request)
    {
        $validated = $request->validated();
        if ($validated['paid_amount'] > $validated['total_amount']) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Paid Amount cant be greater than remaining amount']);
        }
        // create transaction
        // create purchase
        // create account_entries:
        // cash/bank → credit
        // supplier → credit (if unpaid)
        // create product_histories (+qty)
        try {
            DB::transaction(function () use ($validated) {
                // 1 Transaction
                $transaction = Transaction::create([
                    'type' => 'purchase',
                    'voucher_no' => $this->voucher->next('purchase'),
                    'date' => $validated['date'],
                    'notes' => $validated['notes'] ?? null,
                ]);
                // 20000.00
                // 10000.00
                // 2 Purchase
                $purchase = Purchase::create([
                    'supplier_account_id' => $validated['supplier_account_id'],
                    'payment_account_id' => $validated['payment_account_id'],
                    'transaction_id' => $transaction->id,
                    'total_amount' => $validated['total_amount'],
                    'paid_amount' => $validated['paid_amount'],
                    'remaining_amount' => $validated['remaining_amount'],
                    'date' => $validated['date'],
                ]);
                foreach ($validated['items'] as $index => $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'weight' => $item['weight'],
                        'rate' => $item['rate'],
                        'amount' => $item['amount'],
                    ]);
                    ProductHistory::create([
                        'product_id' => $item['product_id'],
                        'weight' => $item['weight'],
                        'rate' => $item['rate'],
                        'type' => 'purchase',
                        'reference_id' => $purchase->id,
                        'reference_type' => Purchase::class,
                    ]);
                }

                $this->postEntries($transaction->id, $validated);
                $this->applyBalances($validated, direction: 1);

                return $purchase;

            });

            return redirect()
                ->route('purchase.index')
                ->with('success', 'Items Bought Successfully');

        } catch (\Exception $e) {
// dd($e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create purchase. Please try again.']);
        }
    }

    private function postEntries(int $transactionId, array $data): void
    {
        // Supplier always gets credited (we owe them)
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['supplier_account_id'],
            'amount' => $data['remaining_amount'],
            'type' => 'credit',
        ]);

        // Cash/bank gets credited (money left our account) only if something was paid
        if (
            $data['payment_type'] !== 'credit' &&
            $data['paid_amount'] > 0 &&
            ! empty($data['payment_account_id'])
        ) {
            AccountEntry::create([
                'transaction_id' => $transactionId,
                'account_id' => $data['payment_account_id'],
                'amount' => $data['paid_amount'],
                'type' => 'credit',
            ]);
        }
    }

    private function applyBalances(array $data, int $direction): void
    {
        $supplierDelta = $data['remaining_amount'] * $direction;
        $cashDelta = $data['paid_amount'] * $direction;

        // direction  1 → supplier owed more, cash goes down
        // direction -1 → supplier owed less, cash comes back
        Account::where('id', $data['supplier_account_id'])
            ->increment('balance', $supplierDelta);

        if (
            ($data['payment_type'] ?? null) !== 'credit' &&
            ($data['paid_amount'] ?? 0) > 0 &&
            ! empty($data['payment_account_id'])
        ) {
            Account::where('id', $data['payment_account_id'])
                ->decrement('balance', $cashDelta);
        }
    }
}
