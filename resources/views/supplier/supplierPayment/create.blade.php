@extends('partials.app', ['title' => 'Create Supplier Payment'])

@section('content')
 {{-- <x-toast /> --}}
  {{-- <x-toast-error field="updateId" /> --}}
    {{-- <x-toast-error field="updateId" /> --}}
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <div class="col-span-12 space-y-6 xl:col-span-12">
 <x-toast-fetch-error />

        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">New Supplier Payment</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Record an supplier payment and update
                        accounts accordingly</p>
                </div>
                <a href="{{ route('supplierPayment.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Supplier Payments
                </a>
            </div>

            <form method="POST"
            action="{{ isset($supplierPayment) ? route('supplierPayment.update', $supplierPayment->id) : route('supplierPayment.store') }}"
            {{-- action="{{ route('supplierPayment.store') }}" --}}
             x-data="supplierForm()">
                @csrf

                {{-- ── SECTION 1: Supplier Payment Details ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Supplier Payment Details</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select the supplier account, payment
                            source, amount and date.</p>
                    </div>

                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Supplier Payment Account --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Supplier Payment Account <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="account_id" x-model="supplierAccountId" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                            'account_id'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                            'account_id'),
                                    ])>
                                        <option value="" disabled selected>Select supplier account</option>
                                        @foreach ($supplierAccounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </span>
                                    @if ($errors->has('account_id'))
                                        <span class="absolute top-1/2 right-8 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('account_id')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Payment Type --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Pay From <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="payment_type" x-model="paymentType" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                            'payment_type'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                            'payment_type'),
                                    ])>
                                        <option value="" disabled selected>Select payment source</option>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash
                                        </option>
                                        <option value="bank" {{ old('payment_type') == 'bank' ? 'selected' : '' }}>Bank
                                        </option>
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </span>
                                </div>
                                @error('payment_type')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Payment Account — fetched from backend --}}
                            <div x-show="paymentType === 'cash' || paymentType === 'bank'" x-cloak>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    <span x-text="paymentType === 'bank' ? 'Bank Account' : 'Cash Account'"></span>
                                    <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="payment_account_id" x-model="paymentAccountId" :disabled="accountsLoading"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none disabled:opacity-50 disabled:cursor-wait',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                                'payment_account_id'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                                'payment_account_id'),
                                        ])>
                                        <option value="" disabled selected
                                            x-text="accountsLoading ? 'Loading...' : 'Select account'"></option>
                                        <template x-for="account in paymentAccounts" :key="account.id">
                                            <option :value="account.id" x-text="account.name"></option>
                                        </template>
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                        <svg x-show="accountsLoading" class="animate-spin" width="16" height="16"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="3" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                        <svg x-show="!accountsLoading" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </span>
                                </div>
                                @error('payment_account_id')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Amount <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="amount" value="{{ old('amount', @$supplierPayment->amount) }}" min="1"
                                        step="1" placeholder="0" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                                'amount'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                                'amount'),
                                        ])>
                                    @if ($errors->has('amount'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('amount')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type="hidden" name="update_id" value="{{ @$supplierPayment->id }}">

                            {{-- Date --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Date <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="date"
                                        value="{{ old('date', isset($supplierPayment) ? $supplierPayment->date : now()->format('Y-m-d')) }}" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                                'date'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                                'date'),
                                        ])>
                                    @if ($errors->has('date'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('date')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="sm:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Description
                                </label>
                                <textarea name="description" rows="2" placeholder="e.g. Monthly rent payment, fuel for delivery"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('description',@$supplierPayment->description) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Account entries preview ── --}}
                <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-white/[0.03] mb-5 overflow-hidden"
                    x-show="supplierAccountId && paymentType">
                    <div class="px-5 py-3 bg-gray-50 dark:bg-white/[0.02] border-b border-gray-100 dark:border-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Account entries that will be created
                        </p>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">DR</span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Supplier Payment account</span>
                            </div>
                            <span class="text-sm text-gray-400 dark:text-gray-500">amount</span>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">CR</span>
                                <span class="text-sm text-gray-700 dark:text-gray-300"
                                    x-text="paymentType === 'bank' ? 'Bank account' : 'Cash account'"></span>
                            </div>
                            <span class="text-sm text-gray-400 dark:text-gray-500">amount</span>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div
                    class="flex items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white px-5 py-4 sm:px-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <a href="{{ route('suppliers.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Save Supplier Payment
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function supplierForm() {
                return {
                    supplierAccountId: '{{ old('account_id', $supplierPayment->account_id??'' ) }}',
                    paymentType: '{{ old('payment_type', $supplierPayment->type ?? '') }}',
                    paymentAccountId: '{{ old('payment_account_id', $supplierPayment->payment_account_id ?? '') }}',
                    paymentAccounts: [],
                    accountsLoading: false,

                    init() {
                        if (this.paymentType === 'cash' || this.paymentType === 'bank') {
                            this.fetchAccounts(this.paymentType);
                        }

                        this.$watch('paymentType', (type) => {
                            this.paymentAccountId = '';
                            this.paymentAccounts = [];
                            if (type === 'cash' || type === 'bank') {
                                this.fetchAccounts(type);
                            }
                        });
                    },

                    async fetchAccounts(type) {
                        this.accountsLoading = true;
                        try {
                            const res = await fetch(`{{ route('purchase.fetchAccountsByType') }}?type=${type}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            });
                            this.paymentAccounts = await res.json();
                        } catch (e) {
                            this.paymentAccounts = [];
                        } finally {
                            this.accountsLoading = false;
                        }
                    },
                }
            }
        </script>
    @endpush
@endsection
