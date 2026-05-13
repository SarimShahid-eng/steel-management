<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerPaymentStoreRequest;
use App\Http\Requests\CustomerPaymentUpdateRequest;
use App\Models\Account;
// use App\Services\BalanceChecker;
use App\Models\AccountEntry;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    public function __construct(protected VoucherService $voucher) {}

    public function index(Request $request)
    {
        $query = Payment::whereHas('customerAccounts')
        ->whereHas('transaction', fn ($q) => $q->where('type', 'payment'))
        ;
        // ->whereHas('paymentAccounts')
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('transaction', fn ($query) => $query->where('voucher_no', 'like', "%{$search}%")
                )
                    ->orWhereHas('customerAccounts', fn ($query) => $query->where('name', 'like', "%{$search}%")
                    );
            });
        }
        $customerPayments = $query->latest()->paginate(10)->withQueryString();

        return view('customer.customerPayment.index', compact('customerPayments'));
    }

    public function create()
    {
        $customerAccounts = Account::where('type', 'customer')->get();

        return view('customer.customerPayment.create', compact('customerAccounts'));

    }

    public function store(CustomerPaymentStoreRequest $request)
    {
        $validated = $request->validated();
        // Cash/Bank   DR   ↑ increases
        // Customer    CR   ↓ decreases
        try {

            DB::transaction(function () use ($validated) {
                // transaction
                $transaction = Transaction::create([
                    'type' => 'payment',
                    'voucher_no' => $this->voucher->next('payment'),
                    'date' => $validated['date'],
                ]);
                Payment::create([
                    'account_id' => $validated['account_id'],
                    'transaction_id' => $transaction->id,
                    'payment_account_id' => $validated['payment_account_id'],
                    'amount' => $validated['amount'],
                    'type' => $validated['payment_type'],
                    'description' => $validated['description'],
                    'date' => $validated['date'],
                ]);

                $this->postEntries($transaction->id, $validated);
                $this->applyBalances($validated, direction: 1);
            });

            return redirect()
                ->route('customerPayment.index')
                ->with('success', 'Customer Entry recorded successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to record customer entry. Please try again.']);
        }
    }

    public function edit(Payment $customerPayment)
    {
        $customerPayment = $customerPayment->whereHas('customerAccounts')->findOrFail($customerPayment->id);
        $customerAccounts = Account::where('type', 'customer')->get();

        return view('customer.customerPayment.create', compact('customerPayment', 'customerAccounts'));
    }

    public function update(CustomerPaymentUpdateRequest $request)
    {
        $validated = $request->validated();
        try {

            DB::transaction(function () use ($validated) {

                $supplierPayment = Payment::find($validated['update_id']);
                // STEP 1: REVERSE OLD BALANCES
                $this->applyBalances($validated, direction: -1);
                //   STEP 2: DELETE OLD ENTRIES
                $supplierPayment->accountEntries()->delete();
                //   STEP 3: UPDATE PAYMENT
                $supplierPayment->update([
                    'account_id' => $validated['account_id'],
                    'payment_account_id' => $validated['payment_account_id'],
                    'amount' => $validated['amount'],
                    'type' => $validated['payment_type'],
                    'description' => $validated['description'],
                    'date' => $validated['date'],
                ]);
                // STEP 4: CREATE NEW ENTRIES
                $this->postEntries($supplierPayment->transaction_id, $validated);
                //  STEP 5: APPLY NEW BALANCES
                $this->applyBalances($validated, direction: 1);

            });

            return redirect()
                ->route('customerPayment.index')
                ->with('success', 'Customer Entry updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to updated customer entry. Please try again.']);
        }

    }

    private function postEntries(int $transactionId, array $data): void
    {
        // Customer Pays money comes in as debit
        // cash/bank -> dr -> increases
        // customer credit -> cr->decreases
        // Debit payment account
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['payment_account_id'],
            'amount' => $data['amount'],
            'type' => 'debit',
        ]);

        // Credit customer account
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['account_id'],
            'amount' => $data['amount'],
            'type' => 'credit',
        ]);

    }

    private function applyBalances(array $data, int $direction): void
    {
        $cashDelta = ($data['amount'] ?? 0) * $direction;
        Account::where('id', $data['payment_account_id'])
            ->increment('balance', $cashDelta);
        Account::where('id', $data['account_id'])
            ->decrement('balance', $cashDelta);

    }
}
