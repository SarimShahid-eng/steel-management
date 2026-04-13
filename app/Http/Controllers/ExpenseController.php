<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseAccountStoreRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('type', 'expense');
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'expense')->where('name', 'like', "%{$search}%");
            });
        }
        $expenses = $query->latest()->paginate(10)->withQueryString();

        return view('expense.index', [
            'expenses' => $expenses,
        ]);
    }

    public function create(Request $request)
    {
        return view('expense.create');
    }

    public function store(ExpenseAccountStoreRequest $request)
    {
        $isUpdate = filled($request->update_id);
        $validated = $request->validated();
        $validated['type'] = 'expense';
        $validated['balance_type'] = 'debit';
        $validated['balance'] = $validated['opening_balance'];
        Account::updateOrCreate(
            ['id' => $validated['update_id']],
            $validated);
        $message = $isUpdate ? 'updated' : 'created';

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense '.$message.' successfully.');

    }

    public function edit(Account $expense)
    {
        return view('expense.create', [
            'expense' => $expense,
        ]);
    }
}
