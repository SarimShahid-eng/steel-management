@extends('partials.app', ['title' => 'Supplier Ledger'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Supplier Ledger</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Transaction history and balance per supplier</p>
            </div>
        </div>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('ledger.supplier') }}">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5 sm:p-6 mb-5">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Supplier --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Supplier</label>
                        <div class="relative">
                            <select name="account_id"
                                class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 appearance-none">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('account_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">From</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">To</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                            Apply
                        </button>
                        <a href="{{ route('ledger.supplier') }}"
                            class="inline-flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Clear
                        </a>
                    </div>

                </div>
            </div>
        </form>

        {{-- BALANCE SUMMARY CARDS --}}
        @if(request('account_id') && $account)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-5">

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Opening Balance</p>
                <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($account->opening_balance, 0) }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Purchased</p>
                <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($totalCredit, 0) }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Current Balance</p>
                <p class="mt-1 text-2xl font-semibold {{ $currentBalance > 0 ? 'text-error-500' : 'text-green-600 dark:text-green-400' }}">
                    {{ number_format($currentBalance, 0) }}
                </p>
            </div>

        </div>
        @endif

        {{-- LEDGER TABLE --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="mb-4 px-5 sm:px-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $account ? $account->name : 'All Suppliers' }} — Transaction History
                </h3>
                {{-- <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                    {{ $entries->total() }} entr{{ $entries->total() !== 1 ? 'ies' : 'y' }} found
                </p> --}}
            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Voucher</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Description</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Debit</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Credit</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        {{-- Opening balance row --}}
                        @if($account)
                        <tr class="bg-gray-50 dark:bg-white/[0.02]">
                            <td class="py-3 pr-4 text-gray-400 text-sm">—</td>
                            <td class="py-3 px-4 text-sm text-gray-500">Opening</td>
                            <td class="py-3 px-4">—</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $account->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500 italic">Opening balance</td>
                            <td class="py-3 px-4 text-sm text-right">—</td>
                            <td class="py-3 px-4 text-sm text-right">—</td>
                            <td class="py-3 px-4 text-sm text-right font-medium text-gray-800 dark:text-white/90">
                                {{ number_format($account->opening_balance, 0) }}
                            </td>
                        </tr>
                        @endif

                        @forelse($entries as $index => $entry)
                        {{-- @dd** --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 pr-4 text-gray-500 text-sm">{{ ++$index }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse(@$entry->transaction->date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ strtoupper(substr(@$entry->transaction->type, 0, 2)) }}-{{ @$entry->transaction->voucher_no }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $entry->account->name }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ @$entry->transaction->notes ?? ucfirst(@$entry->transaction->type) }}
                                </td>
                                <td class="py-3 px-4 text-sm text-right text-blue-600 dark:text-blue-400">
                                    {{ $entry->type === 'debit' ? number_format($entry->amount, 0) : '—' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-right text-orange-600 dark:text-orange-400">
                                    {{ $entry->type === 'credit' ? number_format($entry->amount, 0) : '—' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-right font-medium {{ $entry->running_balance > 0 ? 'text-error-500' : 'text-green-600 dark:text-green-400' }}">
                                    {{ number_format($entry->running_balance, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center text-gray-500">
                                    No transactions found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                    @if($entries->count())
                    <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                        <tr class="bg-gray-50 dark:bg-white/[0.02]">
                            <td colspan="5" class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Totals</td>
                            <td class="py-3 px-4 text-sm text-right font-semibold text-blue-600">{{ number_format($totalDebit, 0) }}</td>
                            <td class="py-3 px-4 text-sm text-right font-semibold text-orange-600">{{ number_format($totalCredit, 0) }}</td>
                            <td class="py-3 px-4 text-sm text-right font-semibold {{ $currentBalance > 0 ? 'text-error-500' : 'text-green-600' }}">
                                {{ number_format($currentBalance, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif

                </table>
            </div>

            {{-- <div class="border-t border-gray-200 px-6 py-4">
                {{ $entries->links() }}
            </div> --}}

        </div>
    </div>
@endsection
