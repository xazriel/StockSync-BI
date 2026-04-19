<div class="p-6 space-y-6">
    {{-- Notifikasi Sukses/Gagal --}}
    @if (session()->has('message'))
        <div class="p-3 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-bold border border-emerald-200">
            ✅ {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-3 bg-red-100 text-red-700 rounded-lg text-sm font-bold border border-red-200">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Row 1: Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-600 p-6 rounded-xl text-white shadow-lg flex justify-between items-center transition hover:scale-[1.01]">
            <div>
                <p class="text-xs uppercase font-bold opacity-80">Transaksi Hari Ini</p>
                <h2 class="text-3xl font-black">{{ $salesCount }} <span class="text-sm font-normal text-blue-100">Order</span></h2>
            </div>
            <span class="text-4xl opacity-30">📦</span>
        </div>
        <div class="bg-emerald-500 p-6 rounded-xl text-white shadow-lg flex justify-between items-center transition hover:scale-[1.01]">
            <div>
                <p class="text-xs uppercase font-bold opacity-80">Omzet Masuk Hari Ini</p>
                <h2 class="text-3xl font-black">Rp {{ number_format($revenue, 0, ',', '.') }}</h2>
            </div>
            <span class="text-4xl opacity-30">💰</span>
        </div>
    </div>

    {{-- Row 2: Monitoring Chart & Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-600 uppercase flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    Produk Terpopuler (Real-time)
                </h3>
            </div>
            <div style="height: 250px;" wire:ignore>
                <canvas id="opChart"></canvas>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="space-y-4">
            <div class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300">
                <h3 class="text-sm font-bold text-gray-600 mb-4 uppercase flex items-center gap-2">
                    ⚡ Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <div class="flex flex-col gap-2">
                        <input type="file" wire:model="fileImport" id="fileImport" class="hidden">
                        <label for="fileImport" class="w-full py-3 bg-white border border-gray-200 rounded-lg text-sm font-bold hover:bg-gray-100 transition flex items-center justify-center gap-2 cursor-pointer text-gray-700 shadow-sm">
                            📥 {{ $fileImport ? 'File Siap Diupload' : 'Import Stok (Excel)' }}
                        </label>
                        
                        @if($fileImport)
                            <button wire:click="importStock" wire:loading.attr="disabled" class="w-full py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md transition disabled:opacity-50">
                                <span wire:loading.remove>🔥 Konfirmasi Update Stok</span>
                                <span wire:loading>⌛ Mengolah Data...</span>
                            </button>
                        @endif
                    </div>

                    <button wire:click="exportSales" class="w-full py-3 bg-white border border-gray-200 rounded-lg text-sm font-bold hover:bg-emerald-50 hover:text-emerald-600 transition flex items-center justify-center gap-2 text-gray-700 shadow-sm">
                        📤 Export Penjualan
                    </button>
                    <p class="text-[10px] text-gray-400 text-center italic">Format: .xlsx / .csv</p>
                </div>
            </div>

            {{-- Recent Logs --}}
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest flex items-center justify-between">
                    Log Terakhir 
                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                </h3>
                <div class="space-y-3">
                    @forelse($recentLogs as $log)
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-2 last:border-0">
                        <div class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                            {{ $log->created_at->format('H:i') }}
                        </div>
                        <div class="text-[11px] text-gray-600">
                            <span class="font-bold text-gray-800">{{ $log->invoice_number }}</span>
                            <br>Rp {{ number_format($log->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                    @empty
                    <p class="text-[10px] text-gray-400 italic text-center py-2">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT GRAPH --}}
    <script>
        function renderOperationalChart() {
            const ctx = document.getElementById('opChart');
            if (!ctx) return;

            // Hapus instance lama agar tidak tumpang tindih
            let chartStatus = Chart.getChart("opChart");
            if (chartStatus != undefined) {
                chartStatus.destroy();
            }

            const labels = @json($labels);
            const values = @json($values);

            if (labels.length === 0) {
                // Tampilkan pesan jika data kosong (Opsional)
                ctx.parentElement.innerHTML = '<div class="h-full flex items-center justify-center text-gray-400 text-sm italic">Belum ada transaksi hari ini</div>';
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Unit Terjual',
                        data: values,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1e293b' }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1 }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Jalankan saat pertama load
        document.addEventListener('livewire:navigated', renderOperationalChart);
        
        // Jalankan setiap kali Livewire melakukan update data (PENTING!)
        document.addEventListener('DOMContentLoaded', renderOperationalChart);

        // Hook khusus Livewire 3 untuk re-render setelah aksi
        document.addEventListener('livewire:load', renderOperationalChart);
    </script>
</div>