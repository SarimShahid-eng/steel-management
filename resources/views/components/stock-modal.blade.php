@props(['routeName'])

{{-- STOCK INFO MODAL --}}
<div id="stockModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" onclick="closeStockModal()"></div>

    {{-- Modal --}}
    <div class="relative z-10 w-full max-w-sm mx-4 rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-900">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Stock Information</h3>
                <p id="modalMaterial" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"></p>
            </div>
            <button onclick="closeStockModal()"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-5 space-y-3">

            <div id="modalLoading" class="py-6 text-center text-sm text-gray-400">
                Loading...
            </div>

            <div id="modalContent" class="hidden space-y-3">

                <div class="flex items-center justify-between rounded-lg bg-blue-50 px-4 py-3 dark:bg-blue-500/10">
                    <span class="text-sm text-blue-700 dark:text-blue-400">Total Purchased</span>
                    <span id="modalTotalPurchased" class="text-sm font-semibold text-blue-700 dark:text-blue-400"></span>
                </div>

                <div class="flex items-center justify-between rounded-lg bg-orange-50 px-4 py-3 dark:bg-orange-500/10">
                    <span class="text-sm text-orange-700 dark:text-orange-400">Total Used</span>
                    <span id="modalTotalUsed" class="text-sm font-semibold text-orange-700 dark:text-orange-400"></span>
                </div>

                <div class="flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 dark:bg-green-500/10">
                    <span class="text-sm text-green-700 dark:text-green-400">Current Stock</span>
                    <span id="modalCurrentStock" class="text-sm font-semibold text-green-700 dark:text-green-400"></span>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function showStockInfo(url) {
        document.getElementById('stockModal').classList.remove('hidden');
        document.getElementById('modalLoading').classList.remove('hidden');
        document.getElementById('modalContent').classList.add('hidden');

        fetch(url)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modalMaterial').textContent        = data.material + ' (' + data.unit + ')';
                document.getElementById('modalTotalPurchased').textContent  = data.total_purchased + ' ' + data.unit;
                document.getElementById('modalTotalUsed').textContent       = data.total_used + ' ' + data.unit;
                document.getElementById('modalCurrentStock').textContent    = data.current_stock + ' ' + data.unit;

                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalContent').classList.remove('hidden');
            })
            .catch(() => {
                document.getElementById('modalLoading').textContent = 'Failed to load data.';
            });
    }

    function closeStockModal() {
        document.getElementById('stockModal').classList.add('hidden');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeStockModal();
    });
</script>
