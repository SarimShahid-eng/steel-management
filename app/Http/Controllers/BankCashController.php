<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankStoreRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class BankCashController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::whereIn('type', ['bank', 'cash']);
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'bankCash')->where('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('type')) {
            $type = $request->type;

            $query->where(function ($q) use ($type) {
                $q->where('type', $type);
            });
        }
        $banksCashes = $query->latest()->paginate(10)->withQueryString();

        return view('bankCash.index', [
            'banksCashes' => $banksCashes,
        ]);
    }

    public function create(Request $request)
    {
        return view('bankCash.create');
    }

    public function store(BankStoreRequest $request)
    {
        $isUpdate = filled($request->update_id);
        $validated = $request->validated();
        $message = $isUpdate ? 'updated' : 'created';

        $isBank = $validated['type'] === 'bank' ? 'Bank' : 'Cash';
        $validated['balance_type'] = 'debit';
        if (array_key_exists('opening_balance', $validated)) {
            $validated['balance'] = $validated['opening_balance'];
        }

        Account::UpdateOrcreate(
            ['id' => $validated['update_id']],
            $validated);

        return redirect()
            ->route('bankCash.index')
            ->with('success', $isBank.' '.$message.' successfully.');
    }

    public function edit(Account $bankCash)
    {
        return view('bankCash.create', compact('bankCash'));

    }
}
