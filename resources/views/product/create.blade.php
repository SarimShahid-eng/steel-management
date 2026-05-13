@extends('partials.app', ['title' => 'Create Product'])

@section('content')
<x-toast-fetch-error />

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Add Product</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Fill in the details to create a new
                        Product</p>
                </div>
                <a href="{{ route('product.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to products
                </a>
            </div>

            <form method="POST" action="{{ route('product.store') }}">
                @csrf
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Product Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic details information for the Product.
                        </p>
                    </div>
                    <input type="hidden" name="update_id" value="{{ @$product->id }}">

                    {{-- Fields --}}
                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="">
                                <x-input-text name="name" label="Name" placeholder="e.g. Sariya,Steel etc.... " required
                                    :value="@$product->name" />
                            </div>
                            <div class="">
                                <x-input-text name="type" label="Type" placeholder="e.g. 1/2,1/3 etc... " required
                                    :value="@$product->type" />
                            </div>
                            <div class="">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Description
                                </label>
                                <textarea name="description"
                                rows="2" placeholder="e.g. Monthly rent payment, fuel for delivery"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('description', @$product->description ) }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div
                        class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                        <a href="{{ route('product.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Save Product
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
