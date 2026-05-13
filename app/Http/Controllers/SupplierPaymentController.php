<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierPaymentStoreRequest;
use App\Http\Requests\SupplierPaymentUpdateRequest;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\VoucherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierPaymentController extends Controller
{
    public function __construct(protected VoucherService $voucher) {}

    public function index(Request $request)
    {
        $query = Payment::whereHas('supplierAccounts')
            ->whereHas('transaction', fn ($q) => $q->where('type', 'payment'));
        // ->whereHas('paymentAccounts')
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('transaction', fn ($query) => $query->where('voucher_no', 'like', "%{$search}%")
                )
                    ->orWhereHas('account', fn ($query) => $query->where('name', 'like', "%{$search}%")
                    );
            });
        }
        $supplierPayments = $query->latest()->paginate(10)->withQueryString();

        return view('supplier.supplierPayment.index', compact('supplierPayments'));
    }

    public function create()
    {
        $supplierAccounts = Account::where('type', 'supplier')->whereHas('purchases')->get();

        return view('supplier.supplierPayment.create', compact('supplierAccounts'));

    }

    public function store(SupplierPaymentStoreRequest $request)
    {
        $validated = $request->validated();
        $account = Account::where('type', 'supplier')->findOrFail($validated['account_id']);
        // $paymentDate = Carbon::parse($request->date)->startOfDay();
        // $createdDate = Carbon::parse($account->created_at)->startOfDay();
        // dd(Carbon::parse($request->date)->toDateString() < $account->created_at->toDateString());
        // if ($account && Carbon::parse($request->date)->lte($account->created_at)) {
        //     return back()->withErrors([
        //         'payment_date' => 'Payment date cannot be earlier than supplier account creation date.',
        //     ]);
        // }
        // dd($accpimts)
        //  AccountEntry → Supplier account   DR  ← reduces what we owe
        //   AccountEntry → Cash/Bank account  CR  ← money goes out
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
                ->route('supplierPayment.index')
                ->with('success', 'Supplier Entry recorded successfully!');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to record supplier entry. Please try again.']);
        }
    }

    public function edit(Payment $supplierPayment)
    {
        $supplierAccounts = Account::where('type', 'supplier')->get();
        $supplierPayment = $supplierPayment->whereHas('supplierAccounts')->findOrFail($supplierPayment->id);

        return view('supplier.supplierPayment.create', compact('supplierPayment', 'supplierAccounts'));
    }

    public function update(SupplierPaymentUpdateRequest $request)
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
                ->route('supplierPayment.index')
                ->with('success', 'Supplier Entry updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to updated supplier entry. Please try again.']);
        }

    }

    private function postEntries(int $transactionId, array $data): void
    {
        // Debit expense account
        AccountEntry::create([
            'transaction_id' => $transactionId,
            'account_id' => $data['account_id'],
            'amount' => $data['amount'],
            'type' => 'debit',
        ]);

        // Credit payment account if paid
        if (
            ($data['payment_type'] ?? null) !== 'credit' &&
            ($data['amount'] ?? 0) > 0 &&
            ! empty($data['payment_account_id'])
        ) {
            AccountEntry::create([
                'transaction_id' => $transactionId,
                'account_id' => $data['payment_account_id'],
                'amount' => $data['amount'],
                'type' => 'credit',
            ]);
        }
    }

    private function applyBalances(array $data, int $direction): void
    {
        $cashDelta = ($data['amount'] ?? 0) * $direction;
        Account::where('id', $data['account_id'])
            ->decrement('balance', $cashDelta);

        Account::where('id', $data['payment_account_id'])
            ->decrement('balance', $cashDelta);
    }
}
