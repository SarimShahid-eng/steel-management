<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleStoreRequest;
use App\Http\Requests\SaleUpdateRequest;
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
        if ($request->filled('fromDate')) {
            $query->where('date', $request->fromDate);
        }
        $sales = $query->latest()->paginate(10)->withQueryString();

        return view('sale.index', compact('sales'));
    }

    public function fetchProductDetails(Sale $sale)
    {
        $sale = $sale->with(['saleItems', 'saleItems.product'])->findOrFail($sale->id);

        return response()->json($sale);
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
        if (array_key_exists('received_amount', $validated)) {

            if ($validated['received_amount'] > $validated['total_amount']) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Received Amount cant be greater than remaining amount']);
            }
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
                // 2 Sale
                $sale = Sale::create([
                    'customer_account_id' => $validated['customer_account_id'],
                    'transaction_id' => $transaction->id,
                    // in_array('payment_account_id',$validated)
                    'payment_account_id' => isset($validated['payment_account_id']) ?? null,
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
                $this->postSalesEntries($validated, $transaction);
                $this->applySalesBalances($validated, direction: 1);

                return $sale;
            });

            return redirect()
                ->route('sale.index')
                ->with('success', 'Item Sold successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create sale. Please try again.']);

        }

    }

    public function edit(Sale $sale)
    {
        $customers = Account::where('type', 'customer')->get();
        $products = Product::all();

        return view('sale.create', compact('customers', 'products', 'sale'));
    }

    public function update(SaleUpdateRequest $request)
    {
        $validated = $request->validated();
        if (array_key_exists('received_amount', $validated)) {
            if ($validated['received_amount'] > $validated['total_amount']) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Paid Amount cant be greater than remaining amount']);
            }
        }
        try {

            DB::transaction(function () use ($validated) {

                $sale = Sale::findOrFail($validated['update_id']);
                $previousRecord = [
                    'total_amount' => $sale->total_amount,
                    'received_amount' => $sale->received_amount,
                    'customer_account_id' => $sale->customer_account_id,
                ];
                if (! is_null($sale->receivedAccount)) {
                    $previousRecord['payment_type'] = $sale->receivedAccount->type;
                }
                // reverse applied balances according to previousRecord
                $this->applySalesBalances($previousRecord, direction: -1);
                $sale->update([
                    'customer_account_id' => $validated['customer_account_id'],
                    'payment_account_id' => $validated['payment_account_id'] ?? null,
                    'total_amount' => $validated['total_amount'],
                    'received_amount' => $validated['received_amount'],
                    'remaining_amount' => $validated['remaining_amount'],
                    'date' => $validated['date'],
                ]);
                $sale->transaction()->update([
                    'date' => $validated['date'],
                    // 'notes' => $validated['notes'] ?? null,
                ]);
                $sale->saleItems()->delete();
                $sale->accountEntries()->delete();
                ProductHistory::where('reference_id', $sale->id)
                    ->where('reference_type', Sale::class)
                    ->delete();

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

                $this->postSalesEntries($validated, Transaction::findOrFail($sale->transaction_id));
                $this->applySalesBalances($validated, direction: 1);

                return $sale;
            });

            return redirect()
                ->route('sale.index')
                ->with('success', 'Purchase Updated Successfully');

        } catch (\Exception $e) {
            dd($e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update sale. Please try again.']);
        }

    }

    private function postSalesEntries($data, $transaction): void
    {
        $transactionId = $transaction->id;
        // Customer always gets debited (they owe us for the sale)
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['customer_account_id'],
            'amount' => $data['total_amount'],
            'type' => 'debit', // Changed from credit
        ]);

        // Cash/bank gets debited (money entered our account) only if they paid something
        if (
            $data['payment_type'] !== 'credit' &&
            $data['total_amount'] > 0 &&
            array_key_exists('payment_account_id', $data)
        ) {
            AccountEntry::create([
                'transaction_id' => $transactionId,
                'account_id' => $data['customer_account_id'],
                'amount' => $data['received_amount'],
                'type' => 'credit',
            ]);
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
        $customerAccount = Account::find($data['customer_account_id']);
        $customerAccount
            ->increment('balance', $customerDelta);

        // 1. Update Customer Balance
        // Account::where('id', $data['customer_account_id'])
        $customerAccount
            ->increment('balance', $customerDelta);
        if ($data['received_amount'] > 0) {
            $customerAccount->decrement('balance', $data['received_amount'] * $direction);
        }
        // 2. Update Cash/Bank Balance
        if (
            ($data['payment_type'] ?? null) !== 'credit' &&
            ($data['received_amount'] ?? 0) > 0 &&
            array_key_exists('payment_account_id', $data)
        ) {
            // IMPORTANT: We INCREMENT here because cash is coming IN
            Account::where('id', $data['payment_account_id'])
                ->increment('balance', $cashDelta);
        }
    }
}
