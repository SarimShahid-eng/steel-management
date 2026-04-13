<?php

// namespace App\Http\Controllers;

// use App\Models\Account;
// use App\Models\AccountEntry;
// use App\Models\Transaction;
// use App\Services\VoucherService;
// use Illuminate\Support\Facades\DB;

// class CapitalDepositController extends Controller
// {
//     public function __construct(protected VoucherService $voucher) {}

//     public function index()
//     {
//         return view('capitalDeposit.index');
//     }

//     public function create()
//     {
//         $bankOrCashAccounts = Account::where('type', 'bank')->get();

//         return view('capitalDeposit.create', compact('bankOrCashAccounts'));
//     }

//     public function store(CapitalDepositStoreRequest $request)
//     {
//         $validated = $request->validated();
//         DB::transaction(function () use ($validated) {
//             $transaction = Transaction::create([
//                 'type' => 'capitalDeposit',
//                 'voucher_no' => $this->voucher->next('capitalDeposit'),
//                 'date' => $validated['date'],
//                 'notes' => $validated['notes'] ?? null,
//             ]);
//             Account::where('id', $validated['account_id'])
//                 ->increment('balance', $validated['amount']);

//             AccountEntry::create([
//                 'transaction_id' => $transaction->id,
//                 'account_id' => $validated['account_id'],
//                 'amount' => $validated['amount'],
//                 'type' => 'debit', // money coming IN
//             ]);

//         });

//     }
// }
