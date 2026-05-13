<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $accountBaseQuery = Account::query();
        $customersCount = (clone $accountBaseQuery)->where('type', 'customer')->count();
        $suppliersCount = (clone $accountBaseQuery)->where('type', 'supplier')->count();
        $expensesCount = (clone $accountBaseQuery)->where('type', 'expense')->count();
        $totalProductsCount = Product::count();
        $totalSales = ceil(Sale::sum('total_amount'));
        $totalPurchases = ceil(Purchase::sum('total_amount'));

        $rawSales = Sale::selectRaw('MONTH(date) as month, SUM(total_amount) as total')
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        // Ensure we have a value for every month [Jan, Feb, ... Dec]
        $monthlyData = [];
        foreach (range(1, 12) as $month) {
            $monthlyData[] = $rawSales[$month] ?? 0;
        }
        $chartData = $monthlyData;

        $rawPurchase = Purchase::selectRaw('MONTH(date) as month, SUM(total_amount) as total')
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        // Ensure we have a value for every month [Jan, Feb, ... Dec]
        $monthlyPurchaseChartData = [];
        foreach (range(1, 12) as $purchaseMonth) {
            $monthlyPurchaseChartData[] = $rawPurchase[$purchaseMonth] ?? 0;
        }
        $purchaseChartData = $monthlyPurchaseChartData;
        // dd($purchaseChartData);

        return view('dashboard', compact('customersCount', 'suppliersCount', 'expensesCount',
            'totalProductsCount', 'totalSales',
            'totalPurchases',
            'chartData',
            'purchaseChartData'
        ));
    }
}
