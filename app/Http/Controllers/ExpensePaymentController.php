<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensePaymentStoreRequest;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Expense;
use App\Models\Transaction;
use App\Services\BalanceChecker;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensePaymentController extends Controller
{
    public function __construct(protected VoucherService $voucher) {}

    public function index(Request $request)
    {
        $query = Expense::with(['transaction', 'account']);
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('transaction', fn ($query) => $query->where('voucher_no', 'like', "%{$search}%")
                )
                    ->orWhereHas('account', fn ($query) => $query->where('name', 'like', "%{$search}%")
                    );
            });
        }
        $expenses = $query->latest()->paginate(10)->withQueryString();

        return view('expense.expensePayment.index', compact('expenses'));
    }

    public function create()
    {
        $expenseAccounts = Account::where('type', 'expense')->get();

        return view('expense.expensePayment.create', compact('expenseAccounts'));

    }

    public function store(ExpensePaymentStoreRequest $request)
    {
        $validated = $request->validated();
        // if (
        //     ($validated['payment_type'] ?? null) !== 'credit' &&
        //     ($validated['paid_amount'] ?? 0) > 0
        // ) {
        //     $hasBalance = BalanceChecker::hasSufficientBalance(
        //         $validated['payment_account_id'],
        //         $validated['paid_amount']
        //     );

        // }
        // if (! $hasBalance) {
        //     return back()
        //         ->withInput()
        //         ->withErrors([
        //             'payment_account_id' => 'Insufficient balance in selected account.',
        //         ]);
        // }

        try {

            DB::transaction(function () use ($validated) {
                // transaction
                $transaction = Transaction::create([
                    'type' => 'expense',
                    'voucher_no' => $this->voucher->next('expense'),
                    'date' => $validated['date'],
                ]);
                // Expense
                Expense::create([
                    'account_id' => $validated['account_id'],
                    'transaction_id' => $transaction->id,
                    'payment_account_id' => $validated['payment_account_id'],
                    'amount' => $validated['amount'],
                    'description' => $validated['description'],
                    'date' => $validated['date'],
                ]);

                $this->postEntries($transaction->id, $validated);
                $this->applyBalances($validated, direction: 1);
            });

            return redirect()
                ->route('expensePayment.index')
                ->with('success', 'Expense recorded successfully!');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to expense. Please try again.']);
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
            ->increment('balance', $cashDelta);

        Account::where('id', $data['payment_account_id'])
            ->decrement('balance', $cashDelta);
    }
}
