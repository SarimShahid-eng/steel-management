@extends('partials.app', ['title' => 'Reports'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12" x-data="{ tab: '{{ request('tab', 'pl') }}' }">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Reports</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Business performance and account summaries</p>
            </div>
        </div>

        {{-- TABS --}}
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="flex gap-1 px-3 overflow-x-auto">
                @foreach([
                    'pl'       => 'Profit & Loss',
                    'purchase' => 'Purchases',
                    'sale'     => 'Sales',
                    'expense'  => 'Expenses',
                    'accounts' => 'Account Balances',
                ] as $key => $label)
                    <button type="button" @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                        class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- DATE FILTER --}}
        <form method="GET" action="{{ route('report.index') }}">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">From</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">To</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                            Apply
                        </button>
                        <a href="{{ route('report.index') }}"
                            class="inline-flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Clear
                        </a>
                    </div>

                </div>
            </div>
        </form>

        {{-- ════════════════════════════════ --}}
        {{-- P & L                            --}}
        {{-- ════════════════════════════════ --}}
        <div x-show="tab === 'pl'">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-5">

                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sales</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">
                        {{ number_format($totalSales, 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Purchases</p>
                    <p class="mt-1 text-2xl font-semibold text-orange-600 dark:text-orange-400">
                        {{ number_format($totalPurchases, 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Expenses</p>
                    <p class="mt-1 text-2xl font-semibold text-error-500">
                        {{ number_format($totalExpenses, 0) }}
                    </p>
                </div>

                <div class="rounded-2xl border {{ $netProfit >= 0 ? 'border-green-200 dark:border-green-900' : 'border-red-200 dark:border-red-900' }} bg-white dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Net Profit</p>
                    <p class="mt-1 text-2xl font-semibold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-error-500' }}">
                        {{ number_format($netProfit, 0) }}
                    </p>
                </div>

            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Profit & Loss Statement</h3>
                    @if(request('from') || request('to'))
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d M Y') : 'Beginning' }}
                            —
                            {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d M Y') : 'Today' }}
                        </p>
                    @endif
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Revenue (Sales)</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                            + {{ number_format($totalSales, 0) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Cost of Goods (Purchases)</span>
                        <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">
                            - {{ number_format($totalPurchases, 0) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 bg-gray-50 dark:bg-white/[0.02]">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Gross Profit</span>
                        <span class="text-sm font-bold {{ $grossProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-error-500' }}">
                            {{ number_format($grossProfit, 0) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Total Expenses</span>
                        <span class="text-sm font-semibold text-error-500">
                            - {{ number_format($totalExpenses, 0) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 bg-gray-50 dark:bg-white/[0.02]">
                        <span class="text-base font-bold text-gray-800 dark:text-white/90">Net Profit</span>
                        <span class="text-base font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-error-500' }}">
                            {{ number_format($netProfit, 0) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════ --}}
        {{-- PURCHASE REPORT                  --}}
        {{-- ════════════════════════════════ --}}
        <div x-show="tab === 'purchase'" x-cloak>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-5">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Purchased</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalPurchases, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ number_format($totalPurchasesPaid, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Outstanding</p>
                    <p class="mt-1 text-2xl font-semibold text-error-500">{{ number_format($totalPurchasesRemaining, 0) }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Purchases by Supplier</h3>
                </div>
                <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                    <table class="min-w-full">
                        <thead class="border-y border-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Count</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Paid</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($purchasesBySupplier as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $row->supplier_name }}</td>
                                    <td class="py-3 px-4 text-sm text-right text-gray-600 dark:text-gray-400">{{ $row->count }}</td>
                                    <td class="py-3 px-4 text-sm text-right font-medium text-gray-800 dark:text-white/90">{{ number_format($row->total, 0) }}</td>
                                    <td class="py-3 px-4 text-sm text-right text-green-600 dark:text-green-400">{{ number_format($row->paid, 0) }}</td>
                                    <td class="py-3 px-4 text-sm text-right {{ $row->remaining > 0 ? 'text-error-500' : 'text-gray-400' }}">{{ number_format($row->remaining, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-16 text-center text-gray-500">No purchases found</td></tr>
                            @endforelse
                        </tbody>
                        @if($purchasesBySupplier->count())
                        <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                            <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Total</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-gray-700 dark:text-gray-300">{{ $purchasesBySupplier->sum('count') }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalPurchases, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-green-600">{{ number_format($totalPurchasesPaid, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-error-500">{{ number_format($totalPurchasesRemaining, 0) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════ --}}
        {{-- SALE REPORT                      --}}
        {{-- ════════════════════════════════ --}}
        <div x-show="tab === 'sale'" x-cloak>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-5">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sales</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalSales, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Received</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ number_format($totalSalesReceived, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Outstanding</p>
                    <p class="mt-1 text-2xl font-semibold text-error-500">{{ number_format($totalSalesRemaining, 0) }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Sales by Customer</h3>
                </div>
                <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                    <table class="min-w-full">
                        <thead class="border-y border-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Customer</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Count</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Received</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($salesByCustomer as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $row->customer_name }}</td>
                                    <td class="py-3 px-4 text-sm text-right text-gray-600 dark:text-gray-400">{{ $row->count }}</td>
                                    <td class="py-3 px-4 text-sm text-right font-medium text-gray-800 dark:text-white/90">{{ number_format($row->total, 0) }}</td>
                                    <td class="py-3 px-4 text-sm text-right text-green-600 dark:text-green-400">{{ number_format($row->received, 0) }}</td>
                                    <td class="py-3 px-4 text-sm text-right {{ $row->remaining > 0 ? 'text-error-500' : 'text-gray-400' }}">{{ number_format($row->remaining, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-16 text-center text-gray-500">No sales found</td></tr>
                            @endforelse
                        </tbody>
                        @if($salesByCustomer->count())
                        <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                            <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Total</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-gray-700 dark:text-gray-300">{{ $salesByCustomer->sum('count') }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalSales, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-green-600">{{ number_format($totalSalesReceived, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-error-500">{{ number_format($totalSalesRemaining, 0) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════ --}}
        {{-- EXPENSE REPORT                   --}}
        {{-- ════════════════════════════════ --}}
        <div x-show="tab === 'expense'" x-cloak>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Expenses</p>
                    <p class="mt-1 text-2xl font-semibold text-error-500">{{ number_format($totalExpenses, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Expense Categories</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $expensesByAccount->count() }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Expenses by Account</h3>
                </div>
                <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                    <table class="min-w-full">
                        <thead class="border-y border-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Expense Account</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Transactions</th>
                                <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expensesByAccount as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $row->account_name }}</td>
                                    <td class="py-3 px-4 text-sm text-right text-gray-600 dark:text-gray-400">{{ $row->count }}</td>
                                    <td class="py-3 px-4 text-sm text-right font-medium text-error-500">{{ number_format($row->total, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-16 text-center text-gray-500">No expenses found</td></tr>
                            @endforelse
                        </tbody>
                        @if($expensesByAccount->count())
                        <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                            <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Total</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-gray-700 dark:text-gray-300">{{ $expensesByAccount->sum('count') }}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-error-500">{{ number_format($totalExpenses, 0) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════ --}}
        {{-- ACCOUNT BALANCES                 --}}
        {{-- ════════════════════════════════ --}}
        <div x-show="tab === 'accounts'" x-cloak>

            @php
                $accountConfig = [
                    'supplier' => ['label' => 'Suppliers',        'color' => 'text-orange-600 dark:text-orange-400'],
                    'customer' => ['label' => 'Customers',        'color' => 'text-blue-600 dark:text-blue-400'],
                    'cash'     => ['label' => 'Cash Accounts',    'color' => 'text-green-600 dark:text-green-400'],
                    'bank'     => ['label' => 'Bank Accounts',    'color' => 'text-green-600 dark:text-green-400'],
                    'expense'  => ['label' => 'Expense Accounts', 'color' => 'text-error-500'],
                ];
            @endphp

            @foreach($accountConfig as $type => $meta)
                @if(isset($accountBalances[$type]) && $accountBalances[$type]->count())
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">
                    <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $meta['label'] }}</h3>
                    </div>
                    <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                        <table class="min-w-full">
                            <thead class="border-y border-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Account Name</th>
                                    <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Opening Balance</th>
                                    <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Current Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($accountBalances[$type] as $account)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                        <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $account->name }}</td>
                                        <td class="py-3 px-4 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($account->opening_balance, 0) }}</td>
                                        <td class="py-3 px-4 text-sm text-right font-semibold {{ $meta['color'] }}">{{ number_format($account->balance, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                                <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                    <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Total</td>
                                    <td class="py-3 px-4 text-sm text-right font-semibold text-gray-600 dark:text-gray-400">
                                        {{ number_format($accountBalances[$type]->sum('opening_balance'), 0) }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-semibold {{ $meta['color'] }}">
                                        {{ number_format($accountBalances[$type]->sum('balance'), 0) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach

        </div>

    </div>
@endsection
