@extends('partials.app', ['title' => 'Create Purchase'])

@section('content')
    <x-toast-fetch-error />
    {{-- <x-toast-error field="updateId" /> --}}

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">New Purchase</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Record a new stock purchase and update
                        supplier & payment accounts</p>
                </div>
                <a href="{{ route('purchase.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Purchases
                </a>
            </div>

            <form method="POST" action="{{ route('purchase.store') }}" x-data="purchaseForm()">
                @csrf

                {{-- ── SECTION 1: Purchase Info ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Purchase Details</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Supplier, date, voucher and payment
                            information.</p>
                    </div>

                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- Voucher No (auto, read-only) --}}


                            {{-- Date --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Date <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                                        @class([
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

                            {{-- Supplier --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Supplier <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="supplier_account_id" x-model="supplierId" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                            'supplier_account_id'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                            'supplier_account_id'),
                                    ])>
                                        <option value="" disabled selected>Select supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_account_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
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
                                    @if ($errors->has('supplier_account_id'))
                                        <span class="absolute top-1/2 right-8 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('supplier_account_id')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Payment Type --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Payment Type <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="payment_type" x-model="paymentType" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                            'payment_type'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                            'payment_type'),
                                    ])>
                                        <option value="" disabled selected>Select payment type</option>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash
                                        </option>
                                        <option value="bank" {{ old('payment_type') == 'bank' ? 'selected' : '' }}>Bank
                                        </option>
                                        <option value="credit" {{ old('payment_type') == 'credit' ? 'selected' : '' }}>
                                            Credit (unpaid)</option>
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

                            {{-- Payment Account — fetched from backend when type changes --}}
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
                                        {{-- Spinner when loading --}}
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

                            {{-- Notes --}}
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Notes
                                </label>
                                <textarea name="notes" rows="2"
                                    placeholder="e.g. Bought 500kg rods from Ahmed Steel, partial payment via HBL"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('notes') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECTION 2: Purchase Items ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Items</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add rods being purchased. Amount =
                                weight × rate.</p>
                        </div>
                        <button type="button" @click="addItem()"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-3.5 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800">

                        {{-- Table Header --}}
                        <div
                            class="hidden sm:grid sm:grid-cols-12 gap-3 px-5 py-3 bg-gray-50 dark:bg-white/[0.02] text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="col-span-1">#</div>
                            <div class="col-span-3 text-right">Product</div>
                            <div class="col-span-3 text-right">Weight (kg)</div>
                            <div class="col-span-3 text-right">Rate / kg</div>
                            <div class="col-span-2 text-right">Amount</div>
                        </div>

                        {{-- Items --}}
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="grid grid-cols-12 gap-3 px-5 py-4 items-center">

                                    {{-- Hidden product_id from backend --}}
                                    @php
                                        $productId = 1;
                                    @endphp
                                    {{-- <input type="hidden" :name="`items[${index}][product_id]`"
                                        value="{{ $productId }}"> --}}


                                    {{-- Row number --}}
                                    <div class="col-span-1">
                                        <span class="text-sm text-gray-400 dark:text-gray-500" x-text="index + 1"></span>
                                    </div>

                                    {{-- Qty --}}
                                    {{-- <div class="col-span-6 sm:col-span-3">
                                        <label class="mb-1 block text-xs text-gray-500 sm:hidden">Qty</label>
                                        <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty"
                                            @input="calcAmount(item)" min="0" placeholder="0"
                                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 text-right placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">


                                        @error('items.*.qty')
                                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                        @enderror

                                    </div> --}}
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="mb-1 block text-xs text-gray-500 sm:hidden">Product</label>
                                        <select :name="`items[${index}][product_id]`" x-init="$el.value = item.product_id ?? ''"
                                            class="shadow-theme-xs rounded-lg border w-full border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('items.*.product_id')
                                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    {{-- Weight --}}
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="mb-1 block text-xs text-gray-500 sm:hidden">Weight (kg)</label>
                                        <input type="number" :name="`items[${index}][weight]`"
                                            x-model.number="item.weight" @input="calcAmount(item)" min="0"
                                            placeholder="0.00"
                                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 text-right placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        @error('items.*.weight')
                                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Rate --}}
                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="mb-1 block text-xs text-gray-500 sm:hidden">Rate / kg</label>
                                        <input type="number" :name="`items[${index}][rate]`" x-model.number="item.rate"
                                            @input="calcAmount(item)" min="0" placeholder="0.00"
                                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 text-right placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        @error('items.*.rate')
                                            <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Amount (computed) + Remove --}}
                                    <div
                                        class="col-span-12 sm:col-span-2 flex items-center justify-between sm:justify-end gap-3">
                                        <div>
                                            <label class="mb-1 block text-xs text-gray-500 sm:hidden">Amount</label>
                                            <input type="hidden" :name="`items[${index}][amount]`" :value="item.amount">
                                            <span class="text-sm font-medium text-gray-800 dark:text-white/90"
                                                x-text="formatNum(item.amount)"></span>
                                            @error('items.*.amount')
                                                <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors flex-shrink-0">
                                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </template>
                        </div>

                        {{-- Empty state --}}
                        <div x-show="items.length === 0"
                            class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                            No items added yet. Click "Add Item" to start.
                        </div>

                    </div>
                </div>

                {{-- ── SECTION 3: Totals + Payment Breakdown ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Payment Summary</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Total is auto-calculated from items.
                            Enter how much was paid now.</p>
                    </div>

                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                            {{-- Paid Amount --}}
                            <div class="w-full sm:max-w-xs">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Paid Amount <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="paid_amount" x-model.number="paidAmount"
                                        @input="calcRemaining()" min="0" placeholder="0.00"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700' => $errors->has(
                                                'paid_amount'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900' => !$errors->has(
                                                'paid_amount'),
                                        ])>
                                    @if ($errors->has('paid_amount'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('paid_amount')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Summary box --}}
                            <div
                                class="w-full sm:max-w-sm rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">

                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-white/[0.02]">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Items</span>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="items.length"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Amount</span>
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white/90"
                                        x-text="formatNum(totalAmount)"></span>
                                </div>

                                <input type="hidden" name="total_amount" :value="totalAmount">

                                <div
                                    class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Paid</span>
                                    <span class="text-sm font-medium text-green-600 dark:text-green-400"
                                        x-text="formatNum(paidAmount)"></span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Remaining</span>
                                    <span class="text-sm font-semibold"
                                        :class="remainingAmount > 0 ? 'text-error-500' : 'text-green-600 dark:text-green-400'"
                                        x-text="formatNum(remainingAmount)"></span>
                                </div>

                                <input type="hidden" name="remaining_amount" :value="remainingAmount">

                            </div>
                        </div>

                        {{-- Account entries preview --}}
                        <div class="mt-5 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden"
                            x-show="supplierId && paymentType">
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-white/[0.02] border-b border-gray-100 dark:border-gray-800">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Account entries that will be created</p>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                                {{-- Supplier entry (always) --}}
                                <div class="flex items-center justify-between px-4 py-3" x-show="supplierId">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">CR</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Supplier account</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white/90"
                                        x-text="formatNum(totalAmount)"></span>
                                </div>
                                {{-- Cash / Bank entry (if paid) --}}
                                <div class="flex items-center justify-between px-4 py-3"
                                    x-show="(paymentType === 'cash' || paymentType === 'bank') && paidAmount > 0">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">DR</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300"
                                            x-text="paymentType === 'bank' ? 'Bank account' : 'Cash account'"></span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white/90"
                                        x-text="formatNum(paidAmount)"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer Actions --}}
                <div
                    class="flex items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white px-5 py-4 sm:px-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <a href="{{ route('purchase.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Save Purchase
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // alett
            function purchaseForm() {
                return {
                    supplierId: '{{ old('supplier_account_id', '') }}',
                    paymentType: '{{ old('payment_type', '') }}',
                    paymentAccountId: '{{ old('payment_account_id', '') }}',
                    paidAmount: {{ old('paid_amount', 0) }},
                    totalAmount: 0,
                    remainingAmount: 0,
                    items: [],
                    nextId: 0,

                    paymentAccounts: [],
                    accountsLoading: false,
                    // alert(items)
                    init() {
                        const oldItems = @json(old('items', []));
                        if (oldItems.length > 0) {
                            this.items = oldItems.map((item, i) => ({
                                id: i,
                                product_id: item.product_id ?? '',
                                weight: parseFloat(item.weight) || null,
                                rate: parseFloat(item.rate) || null,
                                amount: parseFloat(item.amount) || 0,
                            }));
                            this.nextId = this.items.length;
                            this.calcTotal();
                        } else {
                            this.addItem();
                        }
                        this.calcRemaining();

                        // Restore on validation fail
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

                    addItem() {
                        this.items.push({
                            id: this.nextId++,
                             product_id: '',
                            // qty: null,
                            weight: null,
                            rate: null,
                            amount: 0,
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                        this.calcTotal();
                    },

                    calcAmount(item) {
                        item.amount = parseFloat(((item.weight || 0) * (item.rate || 0)).toFixed(2));
                        this.calcTotal();
                    },

                    calcTotal() {
                        this.totalAmount = parseFloat(
                            this.items.reduce((sum, i) => sum + (i.amount || 0), 0).toFixed(2)
                        );
                        this.calcRemaining();
                    },

                    calcRemaining() {
                        this.remainingAmount = parseFloat(
                            Math.max(0, this.totalAmount - (this.paidAmount || 0)).toFixed(2)
                        );
                    },

                    formatNum(val) {
                        return Number(val || 0).toLocaleString('en-PK', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
                }
            }
        </script>
    @endpush
@endsection
