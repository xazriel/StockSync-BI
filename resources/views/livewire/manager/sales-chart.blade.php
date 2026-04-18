<div class="bg-white p-6 rounded-lg shadow-sm border border-purple-100" wire:ignore>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">
            📈 Statistik Pendapatan (7 Hari Terakhir)
        </h3>
        <span class="text-xs text-purple-600 font-medium">Auto-updated via BI Module</span>
    </div>

    {{-- Tempat Grafik Muncul --}}
    <div style="height: 300px;">
        <canvas id="salesChart"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('salesChart');
            
            // Ambil data dari PHP Livewire
            const labels = @json($labels);
            const values = @json($values);

            // Jika data kosong, tampilkan pesan placeholder (opsional)
            if (labels.length === 0) {
                console.log("Belum ada data transaksi untuk grafik.");
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: values,
                        borderColor: '#9333ea', // Warna Ungu (Manager Theme)
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4, // Membuat garis jadi smooth/melengkung
                        pointBackgroundColor: '#9333ea',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>