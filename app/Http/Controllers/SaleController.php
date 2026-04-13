<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleStoreRequest;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Transaction;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(protected VoucherService $voucher) {}

    public function index(Request $request)
    {
        $query = Sale::with(['transaction', 'customerAccount']);
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('transaction', fn ($query) => $query->where('voucher_no', 'like', "%{$search}%")
                )
                    ->orWhereHas('customerAccount', fn ($query) => $query->where('name', 'like', "%{$search}%")
                    );
            });
        }
        $sales = $query->latest()->paginate(10)->withQueryString();

        return view('sale.index', compact('sales'));
    }

    public function create(Request $request)
    {
        $customers = Account::where('type', 'customer')->get();
        $products = Product::all();

        return view('sale.create', compact('customers', 'products'));
    }

    public function store(SaleStoreRequest $request)
    {
        $validated = $request->validated();
        if ($validated['received_amount'] > $validated['total_amount']) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Received Amount cant be greater than remaining amount']);
        }
        try {
            DB::transaction(function () use ($validated) {
                // 1 Transaction
                $transaction = Transaction::create([
                    'type' => 'sale',
                    'voucher_no' => $this->voucher->next('sale'),
                    'date' => $validated['date'],
                    'notes' => $validated['notes'] ?? null,
                ]);
                // 2 Purchase
                $sale = Sale::create([
                    'customer_account_id' => $validated['customer_account_id'],
                    'transaction_id' => $transaction->id,
                    'payment_account_id' => $validated['payment_account_id'],
                    'total_amount' => $validated['total_amount'],
                    'received_amount' => $validated['received_amount'],
                    'remaining_amount' => $validated['remaining_amount'],
                    'date' => $validated['date'],
                ]);
                foreach ($validated['items'] as $index => $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'weight' => $item['weight'],
                        'rate' => $item['rate'],
                        'amount' => $item['amount'],
                    ]);
                    ProductHistory::create([
                        'product_id' => $item['product_id'],
                        'weight' => $item['weight'],
                        'rate' => $item['rate'],
                        'type' => 'sale',
                        'reference_id' => $sale->id,
                        'reference_type' => Sale::class,
                    ]);
                }

                $this->postSalesEntries($transaction->id, $validated);
                $this->applySalesBalances($validated, direction: 1);

                return $sale;
            });

            return redirect()
                ->route('sale.index')
                ->with('success', 'Item Sold successfully.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create sale. Please try again.']);

        }

    }

    private function postSalesEntries(int $transactionId, array $data): void
    {
        // Customer always gets debited (they owe us for the sale)
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['customer_account_id'],
            'amount' => $data['received_amount'],
            'type' => 'debit', // Changed from credit
        ]);

        // Cash/bank gets debited (money entered our account) only if they paid something
        if (
            $data['payment_type'] !== 'credit' &&
            $data['received_amount'] > 0 &&
            ! empty($data['payment_account_id'])
        ) {
            AccountEntry::create([
                'transaction_id' => $transactionId,
                'account_id' => $data['payment_account_id'],
                'amount' => $data['received_amount'],
                'type' => 'debit', // Changed from credit
            ]);
        }
    }

    private function applySalesBalances(array $data, int $direction): void
    {
        // For Sales:
        // direction  1 → customer owes us more (+ Asset)
        // direction -1 → customer owes us less (e.g., return/cancel)

        $customerDelta = $data['total_amount'] * $direction;
        $cashDelta = $data['received_amount'] * $direction;

        // 1. Update Customer Balance
        Account::where('id', $data['customer_account_id'])
            ->increment('balance', $cashDelta);

        // 2. Update Cash/Bank Balance
        if (
            ($data['payment_type'] ?? null) !== 'credit' &&
            ($data['received_amount'] ?? 0) > 0 &&
            ! empty($data['payment_account_id'])
        ) {
            // IMPORTANT: We INCREMENT here because cash is coming IN
            Account::where('id', $data['payment_account_id'])
                ->increment('balance', $cashDelta);
        }
    }
}
