<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anugerah Ponsel - Sistem Manajemen & Business Intelligence</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r content-box from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Anugerah Ponsel
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all duration-200">
                                <i class="fa-solid fa-chart-pie mr-1"></i> Ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700 border border-blue-200 hover:border-blue-300 rounded-lg transition-all duration-200">
                                Masuk Sistem
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <header class="relative overflow-hidden bg-gradient-to-b from-blue-50 via-white to-slate-50 py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                        <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-pulse"></span>
                        Sistem Informasi Tugas Akhir v1.0
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        Integrasi <span class="text-blue-600">Business Intelligence</span> & Kasir Toko Ponsel
                    </h1>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0">
                        Aplikasi manajemen toko ritel pintar yang mengintegrasikan pencatatan kasir operasional real-time, monitoring pengeluaran, hingga visualisasi tren penjualan berbasis analitik data.
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}" class="px-6 py-3 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 transition-all duration-200">
                            Mulai Operasional <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                        <a href="#fitur" class="px-6 py-3 text-base font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl shadow-sm transition-all duration-200">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                
                <div class="lg:col-span-5 relative">
                    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 p-6 text-white max-w-md mx-auto transform hover:-translate-y-2 transition-all duration-300">
                        <div class="flex justify-between items-center border-b border-slate-800 pb-4 mb-4">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            </div>
                            <span class="text-xs text-slate-400">AnugerahPonsel-BI_v1</span>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-800">
                                <p class="text-xs text-slate-400">Total Transaksi Selesai</p>
                                <p class="text-2xl font-bold text-blue-400 mt-1">45 Invoice</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-800">
                                    <p class="text-[10px] text-slate-400">Omset Penjualan</p>
                                    <p class="text-sm font-semibold text-emerald-400 mt-0.5">Rp 216.4M+</p>
                                </div>
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-800">
                                    <p class="text-[10px] text-slate-400">Stok Kritis (< min)</p>
                                    <p class="text-sm font-semibold text-amber-400 mt-0.5">2 Produk</p>
                                </div>
                            </div>
                            <div class="h-24 bg-slate-800/30 rounded-xl border border-dashed border-slate-800 flex items-center justify-center">
                                <span class="text-xs text-slate-500"><i class="fa-solid fa-chart-line mr-1"></i> BI Chart Active Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="fitur" class="py-20 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Arsitektur Modul Penelitian Sistem
                </h2>
                <p class="text-slate-600">
                    Sistem ini dirancang dengan memisahkan dua peran utama untuk mendukung kebutuhan manajerial dan operasional toko ritel gawai secara presisi.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-8 bg-slate-50 rounded-2xl border border-slate-200 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xl mb-6 shadow-md shadow-indigo-600/10">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Modul Executive Dashboard (Business Intelligence)</h3>
                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                        Dirancang khusus untuk kebutuhan pemilik usaha dalam menganalisis performa toko. Memanfaatkan rekaman histori data mentah penjualan untuk memetakan grafik keuntungan bersih dan prediksi pengadaan barang.
                    </p>
                    <ul class="space-y-2 text-xs text-slate-500 font-medium">
                        <li><i class="fa-solid fa-check text-indigo-600 mr-2"></i> Analisis Profit Bersih Otomatis via Penghitungan Beban Pokok</li>
                        <li><i class="fa-solid fa-check text-indigo-600 mr-2"></i> Deteksi Ambang Batas Pengadaan Barang (Kolom <code class="bg-slate-200 px-1 py-0.5 rounded">min_stock</code>)</li>
                        <li><i class="fa-solid fa-check text-indigo-600 mr-2"></i> Grafik Tren Penjualan Ritel untuk Manajemen Strategis</li>
                    </ul>
                </div>

                <div class="p-8 bg-slate-50 rounded-2xl border border-slate-200 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white text-xl mb-6 shadow-md shadow-blue-600/10">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Modul Point of Sales (Operasional Kasir)</h3>
                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                        Dirancang sebagai engine transaksi harian staf administrasi toko. Menangani pencatatan entitas produk berdasar nomor SKU, manajemen stok dinamis, kalkulasi kembalian tunai, serta manajemen pencatatan biaya keluar.
                    </p>
                    <ul class="space-y-2 text-xs text-slate-500 font-medium">
                        <li><i class="fa-solid fa-check text-blue-600 mr-2"></i> Pemrosesan Invoice Multi-item Real-time dengan Livewire</li>
                        <li><i class="fa-solid fa-check text-blue-600 mr-2"></i> Manajemen Inventori & Sinkronisasi Logistik Dinamis</li>
                        <li><i class="fa-solid fa-check text-blue-600 mr-2"></i> Pencatatan Biaya Operasional Toko Terpusat (Tabel <code class="bg-slate-200 px-1 py-0.5 rounded">expenses</code>)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <p class="text-sm">
                &copy; {{ date('Y') }} <strong>Anugerah Ponsel</strong>. Hak Cipta Dilindungi Undang-Undang.
            </p>
            <p class="text-xs text-slate-500">
                Dikembangkan sebagai Implementasi Sistem Informasi Penelitian Berbasis Framework Laravel & Livewire.
            </p>
        </div>
    </footer>

</body>
</html>