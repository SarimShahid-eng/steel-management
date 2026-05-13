@extends('partials.app', ['title' => 'Expense Ledger'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Expense Ledger</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">All expense transactions by account</p>
            </div>
        </div>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('ledger.expense') }}">
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5 sm:p-6 mb-5">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Expense
                            Account</label>
                        <div class="relative">
                            <select name="account_id"
                                class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 appearance-none">
                                <option value="">All Expense Accounts</option>
                                @foreach ($expenseAccounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                    </div>

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
                        <a href="{{ route('ledger.expense') }}"
                            class="inline-flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Clear
                        </a>
                    </div>

                </div>
            </div>
        </form>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Expenses</p>
                <p class="mt-1 text-2xl font-semibold text-error-500">
                    {{ number_format($totalExpenses, 0) }}
                </p>
                @if (request('from') || request('to'))
                    <p class="mt-1 text-xs text-gray-400">Filtered period</p>
                @endif
            </div>

            {{-- <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $entries->total() }}
                </p>
            </div> --}}

        </div>

        {{-- LEDGER TABLE --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4 px-5 sm:px-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ request('account_id') && $account ? $account->name : 'All Expense Accounts' }}
                </h3>
                <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                    {{-- {{ $entries->total() }} entr{{ $entries->total() !== 1 ? 'ies' : 'y' }} found --}}
                </p>
            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Voucher</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Expense Account</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Paid From</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Description</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($entries as $index => $entry)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 pr-4 text-gray-500 text-sm">{{ ++$index }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $entry->transaction->voucher_no }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $entry->account->name }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $entry->paymentFrom->name ?? '—' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $entry->description ?? '—' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-right font-medium text-error-500">
                                    {{ number_format($entry->amount, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-500">No expense entries found</td>
                            </tr>
                        @endforelse

                    </tbody>

                    @if ($entries->count())
                        <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                            <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                <td colspan="6" class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Total</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-error-500">
                                    {{ number_format($totalExpenses, 0) }}</td>
                            </tr>
                        </tfoot>
                    @endif

                </table>
            </div>
            {{--
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $entries->links() }}
            </div> --}}

        </div>
    </div>
@endsection
