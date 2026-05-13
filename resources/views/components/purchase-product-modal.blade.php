{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PURCHASE ITEMS MODAL                                        --}}
{{-- Usage: add x-data="purchaseModal()" to your table wrapper  --}}
{{-- Add @click="open({{ $purchase->id }})" to any trigger      --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

{{--
    In your purchase index blade, wrap the page div with:
    x-data="purchaseModal()"

    Add view button to each row:
    <button @click="open({{ $purchase->id }})" type="button" ...>
        View Items
    </button>

    Then paste this modal at the bottom of the page before @endsection
--}}

{{-- PURCHASE MODAL --}}
<div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="close()">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 dark:bg-black/70" @click="close()">
    </div>

    {{-- Modal --}}
    <div class="relative z-10 w-full max-w-2xl mx-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-xl"
        @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
            <div>
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Purchase Items
                    <span class="font-mono text-sm text-brand-500 ml-1"
                        x-text="voucherNo ? 'PV-' + voucherNo : ''"></span>
                </h3>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400" x-text="supplierName"></p>
            </div>
            <button @click="close()" type="button"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="px-6 py-12 text-center">
            <svg class="animate-spin mx-auto w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="3" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">Loading items...</p>
        </div>

        {{-- Items table --}}
        <div x-show="!loading">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-3 px-5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Weight (kg)</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rate</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="py-3 px-5 text-sm text-gray-500 dark:text-gray-400" x-text="index + 1"></td>
                                <td class="py-3 px-4 text-sm text-right text-gray-700 dark:text-gray-300"
                                    x-text="item.product.name"></td>
                                <td class="py-3 px-4 text-sm text-right text-gray-700 dark:text-gray-300"
                                    x-text="item.weight"></td>
                                <td class="py-3 px-4 text-sm text-right text-gray-700 dark:text-gray-300"
                                    x-text="formatNum(item.rate)"></td>
                                <td class="py-3 px-4 text-sm text-right font-medium text-gray-800 dark:text-white/90"
                                    x-text="formatNum(item.amount)"></td>
                            </tr>
                        </template>
                        <template x-if="!loading && items.length === 0">
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-gray-400">No items found</td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot x-show="items.length > 0" class="border-t-2 border-gray-200 dark:border-gray-700">
                        <tr class="bg-gray-50 dark:bg-white/[0.02]">
                            <td colspan="3" class="py-3 px-5 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Total</td>
                            <td class="py-3 px-4 text-sm text-right text-gray-500 dark:text-gray-400">
                                <span x-text="items.length + ' item' + (items.length !== 1 ? 's' : '')"></span>
                            </td>
                            <td class="py-3 px-4 text-sm text-right font-bold text-gray-800 dark:text-white/90"
                                x-text="formatNum(items.reduce((s, i) => s + parseFloat(i.amount || 0), 0))"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Footer summary --}}
            <div
                class="grid grid-cols-3 divide-x divide-gray-100 dark:divide-gray-800 border-t border-gray-100 dark:border-gray-800">
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                    <p class="mt-0.5 text-sm font-semibold text-gray-800 dark:text-white/90"
                        x-text="formatNum(totalAmount)"></p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Paid</p>
                    <p class="mt-0.5 text-sm font-semibold text-green-600 dark:text-green-400"
                        x-text="formatNum(paidAmount)"></p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Remaining</p>
                    <p class="mt-0.5 text-sm font-semibold"
                        :class="remainingAmount > 0 ? 'text-error-500' : 'text-gray-400'"
                        x-text="formatNum(remainingAmount)"></p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        function purchaseModal() {
            return {
                isOpen: false,
                loading: false,
                items: [],
                voucherNo: null,
                supplierName: '',
                totalAmount: 0,
                paidAmount: 0,
                remainingAmount: 0,

                async open(purchaseId) {
                    this.isOpen = true;
                    this.loading = true;
                    this.items = [];
                    this.voucherNo = null;
                    this.supplierName = '';
                    const url = "{{ route('purchase.fetchProductDetails', ':purchase') }}".replace(':purchase', purchaseId);
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });
                        const data = await res.json();
                        this.items = data.purchase_items;
                        this.voucherNo = data.voucher_no;
                        this.supplierName = data.supplier_name;
                        this.totalAmount = data.total_amount;
                        this.paidAmount = data.paid_amount;
                        this.remainingAmount = data.remaining_amount;
                    } catch (e) {
                        this.items = [];
                    } finally {
                        this.loading = false;
                    }
                },

                close() {
                    this.isOpen = false;
                },

                formatNum(val) {
                    return Number(val || 0).toLocaleString('en-PK', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                },
            }
        }
    </script>
@endpush
