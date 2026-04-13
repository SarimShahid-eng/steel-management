<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('type', 'customer');
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'customer')->where('name', 'like', "%{$search}%");
            });
        }
        $customers = $query->paginate(10)->withQueryString();

        return view('customer.index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(CustomerStoreRequest $request)
    {
        $isUpdate = filled($request->update_id);
        $validated = $request->validated();
        $validated['type'] = 'customer';
        $validated['balance_type'] = 'debit';
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
                ->route('customers.index')
                ->with('success', 'Customer '.$message.' successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create customer. Please try again.']);
        }

        return redirect()->route('customers.index');
    }

    public function edit(Account $customer)
    {
        return view('customer.create', [
            'customer' => $customer,
        ]);
    }
}
