<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('type', 'supplier');
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'supplier')->where('name', 'like', "%{$search}%");
            });
        }
        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('supplier.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(SupplierStoreRequest $request)
    {
        $isUpdate = filled($request->update_id);
        $validated = $request->validated();
        $validated['type'] = 'supplier';
        $validated['balance_type'] = 'credit';
        try {
            DB::transaction(function () use ($validated) {
                $validated['balance'] = $validated['opening_balance'];
                $account = Account::updateOrCreate(
                    ['id' => $validated['update_id']],
                    $validated);
                AccountDetail::updateOrCreate(
                    ['account_id' => $validated['update_id']],
                    [
                        'account_id' => $account->id,
                        'fathername' => $validated['fathername'],
                        'phone_number' => $validated['phone_number'],
                    ]);

            });
            $message = $isUpdate ? 'updated' : 'created';

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier '.$message.' successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create supplier. Please try again.']);
        }
    }

    public function edit(Account $supplier)
    {
        return view('supplier.create', [
            'supplier' => $supplier,
        ]);
    }
}
