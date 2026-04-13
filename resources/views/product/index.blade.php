@extends('partials.app', ['title' => 'Products'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        {{-- SUCCESS TOAST --}}
        <x-toast />

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Products</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage Products</p>
            </div>

            <a href="{{ route('product.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600 transition-colors shadow-theme-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add product
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- TOOLBAR --}}
            <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All products</h3>
                    <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                        {{ $products->total() }} Product{{ $products->total() !== 1 ? 's' : '' }} found
                    </p>
                </div>

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('product.index') }}">
                    <div class="flex items-center gap-2">
                         <x-filter-toolbar :dateRange="false" placeholder="Search product name..." />
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">

                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Name</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Description</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Stock</th>
                            <th class="py-3 px-4 text-center text-theme-sm text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($products as $index => $product)
                            <tr class="hover:bg-gray-50 transition-colors">

                                <td class="py-3 pr-4">
                                    {{ $products->firstItem() + $index }}
                                </td>

                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-white/90">
                                    {{ $product->name }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-white/90">
                                    {{ $product->description }}
                                </td>

                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-white/90">
                                    {{ $product->total_weight??0 }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('product.edit', $product->id) }}" title="Edit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-gray-500">
                                    No product found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $products->links() }}
            </div>

        </div>
    </div>
@endsection
