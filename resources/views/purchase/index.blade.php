@extends('partials.app', ['title' => 'Purchases'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <x-toast />

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Purchases</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage stock purchases</p>
            </div>

            <a href="{{ route('purchase.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600 transition-colors shadow-theme-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                New Purchase
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- TOOLBAR --}}
            <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Purchases</h3>
                    <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                        {{ $purchases->total() }} purchase{{ $purchases->total() !== 1 ? 's' : '' }} found
                    </p>
                </div>

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('purchase.index') }}">
                    <div class="flex items-center gap-2">
                        <x-filter-toolbar :dateRange="false" :singleDate="true" dateName="fromDate"
                            placeholder="Search supplier/voucher..." />
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6" x-data="purchaseModal()">
                <x-purchase-product-modal />
                <table class="min-w-full">

                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Voucher</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Type</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Paid</th>
                            <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Remaining</th>
                            <th class="py-3 px-4 text-center text-theme-sm text-gray-500">Status</th>
                            <th class="py-3 px-4 text-center text-theme-sm text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($purchases as $index => $purchase)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                                <td class="py-3 pr-4 text-gray-500 text-sm">
                                    {{ $purchases->firstItem() + $index }}
                                </td>

                                <td class="py-3 px-4">
                                    <span class="font-mono text-sm font-medium text-gray-800 dark:text-white/90">
                                        PV-{{ $purchase->transaction->voucher_no }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $purchase->paymentAccount ? $purchase->paymentAccount->type . '/' . $purchase->paymentAccount->name : 'credit' }}

                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}
                                </td>

                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $purchase->supplierAccount->name }}
                                </td>

                                <td class="py-3 px-4 text-sm text-right font-medium text-gray-800 dark:text-white/90">
                                    {{ number_format($purchase->total_amount, 0) }}
                                </td>

                                <td class="py-3 px-4 text-sm text-right text-green-600 dark:text-green-400">
                                    {{ number_format($purchase->paid_amount, 0) }}
                                </td>

                                <td
                                    class="py-3 px-4 text-sm text-right {{ $purchase->remaining_amount > 0 ? 'text-error-500' : 'text-gray-400' }}">
                                    {{ number_format($purchase->remaining_amount, 0) }}
                                </td>

                                <td class="py-3 px-4 text-center">
                                    @if ($purchase->reversed_at)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                            Reversed
                                        </span>
                                    @elseif($purchase->remaining_amount <= 0)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            Paid
                                        </span>
                                        {{-- @else --}}
                                    @elseif($purchase->paid_amount <= 0)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white dark:bg-green-500/10 dark:text-green-400">
                                            Unpaid
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">
                                            Partial
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-1">

                                        {{-- View --}}
                                        <button type="button" title="View"
                                            @click.prevent.stop="open({{ $purchase->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        {{-- Edit: only if no payment made and not reversed --}}
                                        {{-- @if ($purchase->paid_amount == 0) --}}
                                        <a href="{{ route('purchase.edit', $purchase->id) }}" title="Edit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        {{-- @endif --}}

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="py-16 text-center text-gray-500">
                                    No purchases found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="border-t border-gray-200 px-6 py-4">
                {{-- @dd($purchases->links()) --}}
                {{ $purchases->links() }}
            </div>

        </div>
    </div>
@endsection
