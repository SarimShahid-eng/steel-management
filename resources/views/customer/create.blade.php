@extends('partials.app', ['title' => 'Create Customer'])

@section('content')
 <x-toast-fetch-error />

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Add Customer</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Fill in the details to create a new
                        Customer</p>
                </div>
                <a href="{{ route('customers.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to customers
                </a>
            </div>

            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Customer Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic details information for the Customer.
                        </p>
                    </div>
                    <input type="hidden" name="update_id" value="{{ @$customer->id }}">

                    {{-- Fields --}}
                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <x-input-text name="name" label="Name" placeholder="e.g. John Doe" required
                                    :value="@$customer->name" />
                            </div>

                            {{-- Email --}}
                            <div>
                                <div class="relative">
                                    <x-input-text name="fathername" label="Fathername" placeholder="Enter Fathername ..."
                                        :value="@$customer->account_detail?->fathername" />

                                </div>
                            </div>

                            {{-- Phone --}}
                            <div>
                                <x-input-text name="phone_number" type="number" label="Phone Number"
                                    placeholder="03xxxxxxxxx" :value="@$customer->account_detail?->phone_number" />
                            </div>
                            <div>
                                <x-input-text
                                disabled="{{ isset($customer) ? true : false }}"
                                name="opening_balance" type="number" label="Opening Balance"
                                    :value="@$customer->opening_balance" placeholder="100-200000000..." required />
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div
                        class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                        <a href="{{ route('customers.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Save Customer
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
