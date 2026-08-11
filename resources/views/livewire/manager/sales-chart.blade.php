<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- GRAFIK PENDAPATAN (LINE CHART) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-purple-100" wire:ignore>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">
                    📈 Tren Pendapatan (30 Hari Terakhir)
                </h3>
                <span class="text-[10px] bg-purple-100 text-purple-600 px-2 py-1 rounded-full font-bold">BI ENGINE ACTIVE</span>
            </div>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- KLASIFIKASI PRODUK (DOUGHNUT CHART) --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-purple-100" wire:ignore>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-1 text-center">
                📊 Komposisi Inventaris
            </h3>
            <p class="text-[9px] text-purple-500 font-bold uppercase tracking-widest text-center mb-4">
                🤖 Klasifikasi C4.5 Decision Tree
            </p>
            <div style="height: 250px;">
                <canvas id="classificationChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center text-[10px] font-bold uppercase">
                <div class="text-emerald-500">Fast</div>
                <div class="text-amber-500">Medium</div>
                <div class="text-rose-500">Slow</div>
            </div>
        </div>
    </div>

    {{-- TABEL ANALISIS REORDER POINT (ROP) --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100">
        <div class="flex items-center gap-2 mb-4">
            <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase">Rekomendasi Restock Cepat</h3>
                <p class="text-[10px] text-gray-500">Berdasarkan Reorder Point (Lead Time 2 Hari + Safety Stock)</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase border-b">
                    <tr>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2 text-center">Stok</th>
                        <th class="px-4 py-2 text-center">Sinyal ROP</th>
                        <th class="px-4 py-2 text-right">Saran Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restockList as $product)
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-700">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-red-600 font-bold">{{ $product->stock }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500">
                            {{ round($product->reorder_point, 1) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-black">
                                +{{ $product->restock_recommendation }} Unit
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400 italic">Semua stok masih aman terkendali.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SCRIPT INSTANT REAL-TIME CHART SINKRONISASI --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let instanceSalesChart = null;
            let instanceClassChart = null;

            // Fungsi utama membuat / menggambar grafik Chart.js
            function buildCharts(labelsData, valuesData, classData) {
                // Hancurkan chart lama jika ada agar tidak bentrok data (*Crucial untuk Livewire*)
                if (instanceSalesChart) instanceSalesChart.destroy();
                if (instanceClassChart) instanceClassChart.destroy();

                // 1. Bar Chart (Pendapatan)
                const ctxSales = document.getElementById('salesChart');
                if(ctxSales) {
                    instanceSalesChart = new Chart(ctxSales, {
                        type: 'bar',
                        data: {
                            labels: labelsData,
                            datasets: [{
                                label: 'Pendapatan',
                                data: valuesData,
                                backgroundColor: '#3b82f6',
                                borderRadius: 4
                            }]
                        },
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } }
                        }
                    });
                }

                // 2. Doughnut Chart (Klasifikasi BI)
                const ctxClass = document.getElementById('classificationChart');
                if(ctxClass) {
                    instanceClassChart = new Chart(ctxClass, {
                        type: 'doughnut',
                        data: {
                            labels: ['Fast Moving', 'Medium Moving', 'Slow Moving'],
                            datasets: [{
                                data: [classData.fast, classData.medium, classData.slow],
                                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: { legend: { display: false } }
                        }
                    });
                }
            }

            // Panggilan pertama saat halaman dimuat bawaan PHP data awal
            buildCharts(
                @json($labels), 
                @json($values), 
                @json($classification)
            );

            // TANGKAP DISPATCH REAKTIF DARI KASIR RAFI TANPA REFRESH
            Livewire.on('bi-data-updated', (event) => {
                const payload = event[0]; // Tangkap data array kembalian Livewire v3
                buildCharts(payload.labels, payload.values, payload.classification);
            });
        });
    </script>
</div>