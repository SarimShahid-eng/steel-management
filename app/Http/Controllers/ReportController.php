<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function report()
    {
        $purchasesBySupplier = Purchase::with('supplier')
            ->whereBetween('date', [$from, $to])
            ->join('accounts', 'accounts.id', '=', 'purchases.supplier_account_id')
            ->selectRaw('accounts.name as supplier_name, count(*) as count, sum(total_amount) as total, sum(paid_amount) as paid, sum(remaining_amount) as remaining')
            ->groupBy('accounts.id', 'accounts.name')->get();

        // same pattern for salesByCustomer and expensesByAccount

        $grossProfit = $totalSales - $totalPurchases;
        $netProfit = $grossProfit - $totalExpenses;

        $accountBalances = Account::all()->groupBy('type');

    }
}
