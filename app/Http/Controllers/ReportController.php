<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        // ── Purchase report ──────────────────────────────────────
        $purchaseQuery = Purchase::with('supplierAccount')
            ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('date', '<=', $to));

        $purchasesBySupplier = $purchaseQuery->clone()
            ->join('accounts', 'accounts.id', '=', 'purchases.supplier_account_id')
            ->selectRaw('
                accounts.name   as supplier_name,
                COUNT(*)        as count,
                SUM(purchases.total_amount)     as total,
                SUM(purchases.paid_amount)      as paid,
                SUM(purchases.remaining_amount) as remaining
            ')
            ->groupBy('accounts.id', 'accounts.name')
            ->orderByDesc('total')
            ->get();

        $totalPurchases          = $purchasesBySupplier->sum('total');
        $totalPurchasesPaid      = $purchasesBySupplier->sum('paid');
        $totalPurchasesRemaining = $purchasesBySupplier->sum('remaining');

        // ── Sale report ───────────────────────────────────────────
        $saleQuery = Sale::with('customerAccount')
            ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('date', '<=', $to));

        $salesByCustomer = $saleQuery->clone()
            ->join('accounts', 'accounts.id', '=', 'sales.customer_account_id')
            ->selectRaw('
                accounts.name as customer_name,
                COUNT(*)      as count,
                SUM(sales.total_amount)     as total,
                SUM(sales.received_amount)  as received,
                SUM(sales.remaining_amount) as remaining
            ')
            ->groupBy('accounts.id', 'accounts.name')
            ->orderByDesc('total')
            ->get();

        $totalSales          = $salesByCustomer->sum('total');
        $totalSalesReceived  = $salesByCustomer->sum('received');
        $totalSalesRemaining = $salesByCustomer->sum('remaining');

        // ── Expense report ────────────────────────────────────────
        $expenseQuery = Expense::with('account')
            ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('date', '<=', $to));

        $expensesByAccount = $expenseQuery->clone()
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id')
            ->selectRaw('
                accounts.name as account_name,
                COUNT(*)      as count,
                SUM(expenses.amount) as total
            ')
            ->groupBy('accounts.id', 'accounts.name')
            ->orderByDesc('total')
            ->get();

        $totalExpenses = $expensesByAccount->sum('total');

        // ── P&L ───────────────────────────────────────────────────
        $grossProfit = $totalSales - $totalPurchases;
        $netProfit   = $grossProfit - $totalExpenses;

        // ── Account balances ──────────────────────────────────────
        $accountBalances = Account::all()->groupBy('type');

        return view('reports.index', compact(
            'purchasesBySupplier',
            'totalPurchases',
            'totalPurchasesPaid',
            'totalPurchasesRemaining',

            'salesByCustomer',
            'totalSales',
            'totalSalesReceived',
            'totalSalesRemaining',

            'expensesByAccount',
            'totalExpenses',

            'grossProfit',
            'netProfit',

            'accountBalances',
        ));
    }
}
