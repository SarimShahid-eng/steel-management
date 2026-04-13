@extends('partials.app', ['title' => 'Capital Deposit'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <x-toast />

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">capital Deposits</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage capitalDeposit payments</p>
            </div>

            <a href="{{ route('capitalDeposit.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600 transition-colors shadow-theme-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                New capitalDeposit
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- TOOLBAR --}}
            <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Capital Deposit</h3>
                    <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                        {{-- {{ $capitalDeposits->total() }} capitalDeposit{{ $capitalDeposits->total() !== 1 ? 's' : '' }} found --}}
                    </p>
                </div>

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('capitalDeposit.index') }}">
                    <div class="flex items-center gap-2">

                        <x-search-input placeholder="Search by account or description..." />

                        <button type="submit"
                            class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg class="fill-white" width="16" height="16" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('capitalDeposit.index') }}"
                                class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                Clear
                            </a>
                        @endif

                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">

                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Voucher</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">capitalDeposit Account</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Paid From</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Description</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Amount</th>
                            <th class="py-3 px-4 text-center text-theme-sm text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    {{-- <tbody class="divide-y divide-gray-100">

                        @forelse($capitalDeposits as $index => $capitalDeposit)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                                <td class="py-3 pr-4 text-gray-500 text-sm">
                                    {{ $capitalDeposits->firstItem() + $index }}
                                </td>

                                <td class="py-3 px-4">
                                    <span class="font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                                        EV-{{ $capitalDeposit->transaction->voucher_no }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($capitalDeposit->date)->format('d M Y') }}
                                </td>

                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $capitalDeposit->account->name }}
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $capitalDeposit->paymentAccount->name ?? '—' }}
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $capitalDeposit->description ?? '—' }}
                                </td>

                                <td class="py-3 px-4 text-sm text-right font-medium text-error-500">
                                    {{ number_format($capitalDeposit->amount, 0) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('capitalDeposit.show', $capitalDeposit->id) }}" title="View"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center text-gray-500">
                                    No capitalDeposits found
                                </td>
                            </tr>
                        @endforelse

                    </tbody> --}}
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="border-t border-gray-200 px-6 py-4">
                {{-- {{ $capitalDeposits->links() }} --}}
            </div>

        </div>
    </div>
@endsection
