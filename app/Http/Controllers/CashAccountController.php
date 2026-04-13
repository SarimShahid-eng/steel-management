<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashStoreRequest;
use App\Models\Account;
use App\Models\Cash;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashAccountController extends Controller
{
    public function index(Request $request)
    {
        $cashAccounts = Account::where('typ','cash')->paginate(10);

        return view('cash.index', [
            'cashAccounts' => $cashAccounts,
        ]);
    }

    public function create(Request $request)
    {
        return view('cash.create');
    }

    public function store(CashStoreRequest $request)
    {
         $validated = $request->validated();
        $validated['type'] = 'cash';
        $validated['balance_type'] = 'debit';
        $supplier = Account::create($validated);

        return redirect()->route('cashAccounts.index');
    }
}
