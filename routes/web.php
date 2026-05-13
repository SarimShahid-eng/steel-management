<?php

use App\Http\Controllers\BankCashController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpensePaymentController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
    // Route
    Route::controller(SupplierController::class)->prefix('supplier')->name('suppliers.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{supplier}', 'edit')->name('edit');
    });
    Route::controller(SupplierPaymentController::class)->prefix('supplierPayment')->name('supplierPayment.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::get('edit/{supplierPayment}', 'edit')->name('edit');
    });
    Route::controller(CustomerController::class)->prefix('customer')->name('customers.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{customer}', 'edit')->name('edit');
    });
    Route::controller(CustomerPaymentController::class)->prefix('customerPayment')->name('customerPayment.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{customerPayment}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
    });
    Route::controller(ExpenseController::class)->prefix('expense')->name('expenses.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{expense}', 'edit')->name('edit');
    });
    // merged bank and cash type wise
    Route::controller(BankCashController::class)->prefix('bankCash')->name('bankCash.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{bankCash}', 'edit')->name('edit');
    });
    Route::controller(PurchaseController::class)->prefix('purchase')->name('purchase.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::get('edit/{purchase}', 'edit')->name('edit');
        Route::get('accounts/by-type', 'fetchAccountsbyType')->name('fetchAccountsByType');
        Route::get('fetchProductDetails/{purchase}', 'fetchProductDetails')->name('fetchProductDetails');

    });
    // Route::get('sales/{sale}/items',[SaleController::class,'items'])->name('sales.items');
    Route::controller(SaleController::class)->prefix('sale')->name('sale.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::get('edit/{sale}', 'edit')->name('edit');
        Route::get('fetchProductDetails/{sale}', 'fetchProductDetails')->name('fetchProductDetails');
    });
    Route::controller(ExpensePaymentController::class)->prefix('expensePayment')->name('expensePayment.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{sale}', 'edit')->name('edit');
    });
    Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{product}', 'edit')->name('edit');
    });
    Route::controller(LedgerController::class)->prefix('ledger')->name('ledger.')->group(function () {
        Route::get('supplier', 'supplier')->name('supplier');
        Route::get('customer', 'customer')->name('customer');
        Route::get('expense', 'expense')->name('expense');
    });
    Route::controller(ReportController::class)->prefix('report')->name('report.')->group(function () {
        Route::get('index', 'index')->name('index');
    });
    // Route::controller(CapitalDepositController::class)->prefix('capitalDeposit')->name('capitalDeposit.')->group(function () {
    //     Route::get('index', 'index')->name('index');
    //     Route::get('create', 'create')->name('create');
    //     Route::post('store', 'store')->name('store');
    //     Route::get('edit/{sale}', 'edit')->name('edit');
    // });
    // Route::controller(SaleController::class)->prefix('sale')->name('sale.')->group(function () {
    //     Route::get('index', 'index')->name('index');
    //     Route::get('create', 'create')->name('create');
    //     Route::post('store', 'store')->name('store');
    //     Route::get('edit/{sale}', 'edit')->name('edit');
    // });

});
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('login');
    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});
// Route::get('login',')

// Route::resource('products', App\Http\Controllers\ProductController::class)->only('index', 'create', 'store');
