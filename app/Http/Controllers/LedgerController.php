<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Expense;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function supplier(Request $request)
    {
        $suppliers = Account::where('type', 'supplier')->get();
        $entries = collect();
        $account = [];
        $currentBalance = 0;
        $totalDebit = 0;
        $totalCredit = 0;
        if (filled($request->account_id)) {
            $baseQuery = AccountEntry::whereHas('supplierAccount')
                ->with('transaction:id,date,voucher_no,type')
                ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
                ->when($request->from, fn ($q) => $q->whereHas('transaction', fn ($q) => $q->whereDate('date', '>=', $request->from)
                )
                )
                ->when($request->to, fn ($q) => $q->whereHas('transaction', fn ($q) => $q->whereDate('date', '<=', $request->to)
                )
                );
            $entries = (clone $baseQuery)
                ->get();
            $account = Account::find($request->account_id);
            $totalDebit = (clone $baseQuery)
                ->where('type', 'debit')
                ->sum('amount');
            $totalCredit = (clone $baseQuery)
                ->where('type', 'credit')
                ->sum('amount');

            if ($account) {
                $currentBalance = $account->opening_balance + $totalCredit - $totalDebit;
            } else {
                $currentBalance = $totalCredit - $totalDebit;
            }
            $runningTotal = $account ? $account->opening_balance : 0;
            $entries->transform(function ($entry) use (&$runningTotal) {
                $amount = floatval($entry->amount);

                $runningTotal = $entry->type === 'credit' ? $runningTotal += $amount : $runningTotal -= $amount;

                $entry->running_balance = $runningTotal;

                return $entry;
            });
        }

        return view('ledger.supplier', compact('entries', 'suppliers', 'totalDebit', 'totalCredit', 'currentBalance', 'account'));
    }

    public function customer(Request $request)
    {
        $customers = Account::where('type', 'customer')->get();
        $entries = collect();
        $account = [];
        $currentBalance = 0;
        $totalDebit = 0;
        $totalCredit = 0;
        if (filled($request->account_id)) {
            $baseQuery = AccountEntry::whereHas('customerAccount')
                ->with('transaction:id,date,voucher_no,type')
                ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
                ->when($request->from, fn ($q) => $q->whereHas('transaction', fn ($q) => $q->whereDate('date', '>=', $request->from)
                )
                )
                ->when($request->to, fn ($q) => $q->whereHas('transaction', fn ($q) => $q->whereDate('date', '<=', $request->to)
                )
                );
            $entries = (clone $baseQuery)
                ->get();
            $account = Account::find($request->account_id);
            $totalDebit = (clone $baseQuery)
                ->where('type', 'debit')
                ->sum('amount');
            $totalCredit = (clone $baseQuery)
                ->where('type', 'credit')
                ->sum('amount');

            if ($account) {
                $currentBalance = $account->opening_balance + $totalDebit - $totalCredit;
            } else {
                $currentBalance = $$totalDebit - $totalCredit;
            }
            $runningTotal = $account ? $account->opening_balance : 0;
            $entries->transform(function ($entry) use (&$runningTotal) {
                $amount = floatval($entry->amount);

                $runningTotal = $entry->type === 'debit' ? $runningTotal += $amount : $runningTotal -= $amount;

                $entry->running_balance = $runningTotal;

                return $entry;
            });
        }

        return view('ledger.customer', compact('entries', 'customers', 'totalDebit', 'totalCredit', 'currentBalance', 'account'));
    }

    public function expense(Request $request)
    {
        $expenseAccounts = Account::where('type', 'expense')->get();
        $entries = collect();
        $totalExpenses = 0;
        $query = Expense::with(['transaction', 'paymentFrom'])
            ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
            ->when($request->from, fn ($q) => $q->whereHas('transaction', fn ($q) => $q->whereDate('date', '>=', $request->from)
            )
            );

        $entries = $query->get();
        $totalExpenses = $query->sum('amount');

        return view('ledger.expense', compact('entries', 'totalExpenses', 'expenseAccounts'));
    }
}
