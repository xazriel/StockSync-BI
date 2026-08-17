<div class="p-6 space-y-6">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    {{-- Filter Waktu --}}
    <div class="flex justify-end mb-4">
        <select wire:model.live="filter" class="text-sm border-gray-200 text-gray-700 font-bold rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white px-4 py-2 cursor-pointer">
            <option value="today">📊 Hari Ini</option>
            <option value="week">📅 Minggu Ini</option>
            <option value="month">🗓️ Bulan Ini</option>
        </select>
    </div>

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
                    Produk Terpopuler Hari Ini (Real-time)
                </h3>
            </div>

            <div style="height: 250px;" class="relative">
                @if(empty($labels))
                    <div id="noDataMsg" class="absolute inset-0 flex items-center justify-center text-gray-400 text-sm italic bg-white z-10">
                        Belum ada transaksi hari ini
                    </div>
                @endif

                <div wire:ignore class="w-full h-full">
                    <canvas id="opChart"></canvas>
                </div>
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
                        <div wire:click="showInvoiceDetails({{ $log->id }})" class="flex items-center gap-3 border-b border-gray-50 pb-2 last:border-0 cursor-pointer hover:bg-gray-50 transition p-2 rounded-lg group">
                            <div class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ $log->created_at->format('H:i') }}
                            </div>
                            <div class="text-[11px] text-gray-600 w-full flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-gray-800">{{ $log->invoice_number }}</span>
                                    <br>Rp {{ number_format($log->total_price, 0, ',', '.') }}
                                </div>
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    @empty
                        <p class="text-[10px] text-gray-400 italic text-center py-2">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Invoice Details Modal --}}
    @if($this->selectedInvoice)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-slate-100">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-6">
                        <div>
                            <h3 class="font-black text-xl text-slate-800 uppercase tracking-tighter mb-1">{{ $this->selectedInvoice->invoice_number }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $this->selectedInvoice->created_at->format('d M Y - H:i') }} | KASIR: <span class="text-blue-600">{{ $this->selectedInvoice->user->name ?? 'UNKNOWN' }}</span></p>
                        </div>
                        <button wire:click="closeInvoiceDetails" class="bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 transition w-8 h-8 flex items-center justify-center rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-3 max-h-60 overflow-y-auto mb-6 pr-2 custom-scrollbar">
                        @foreach($this->selectedInvoice->items as $item)
                            <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-100 transition hover:border-blue-200">
                                <div>
                                    <p class="text-sm font-bold text-slate-700">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $item->quantity }} UNIT x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-sm font-black text-blue-600">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 space-y-3">
                        <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <span>Total Harga</span>
                            <span class="text-slate-800">Rp {{ number_format($this->selectedInvoice->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <span>Jumlah Bayar</span>
                            <span class="text-slate-800">Rp {{ number_format($this->selectedInvoice->pay_amount, 0, ',', '.') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    @endif

    <script>
        (function () {
            let opChartInstance = null;

            function renderOperationalChart(labels, values) {
                const canvas = document.getElementById('opChart');
                if (!canvas) return;

                if (opChartInstance) {
                    opChartInstance.destroy();
                    opChartInstance = null;
                }

                const noDataMsg = document.getElementById('noDataMsg');

                if (!labels || labels.length === 0) {
                    if (noDataMsg) noDataMsg.style.display = 'flex';
                    return;
                }

                if (noDataMsg) noDataMsg.style.display = 'none';

                opChartInstance = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Unit Terjual',
                            data: values,
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            barThickness: 30,
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
                                ticks: { stepSize: 1, precision: 0 }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                renderOperationalChart(@json($labels), @json($values));
            });

            document.addEventListener('livewire:navigated', function () {
                renderOperationalChart(@json($labels), @json($values));
            });

            window.addEventListener('initChart', function (event) {
                renderOperationalChart(event.detail.labels, event.detail.values);
            });
        })();
    </script>
</div>