<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; overflow: hidden; }
        .scrollbar-none::-webkit-scrollbar { display: none; }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="flex h-screen">

    <!-- SIDEBAR KIRI -->
    <aside class="w-[260px] bg-white border-r border-black/5 flex flex-col p-6 shrink-0 z-20">
         <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[46px] h-[46px] rounded-full object-cover shadow-sm border border-black/10 shrink-0">

            <h1 class="text-[17px] font-[900] tracking-tighter m-0 p-0 mt-1 whitespace-nowrap">
                <span class="text-[#1A1A1A]">Seblak</span>
                <span class="text-[#DC0F11]">Jeletet Medan</span>
            </h1>
        </div>

        <div class="border border-black/5 rounded-[12px] p-3 flex items-center gap-3 mb-8 bg-[#F3F3F3]">
            <div class="w-[40px] h-[40px] bg-[#FDE7E7] text-[#DC0F11] rounded-full flex items-center justify-center text-[20px]">👨‍🍳</div>
            <div>
                <div class="text-[10px] text-[#8A8A8E] font-[800] uppercase tracking-wider">Cashier</div>
                <div class="text-[14px] font-[900] text-[#1A1A1A] leading-tight">{{ Auth::user()->name ?? 'Kasir Utama' }}</div>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-black text-white rounded-full font-[800] text-[13px] transition shadow-md">
                Dashboard Overview
            </a>
            <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Kasir Prasmanan
            </a>
            <a href="{{ route('kasir.meja') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Pesanan Meja (QR)
            </a>
            <a href="{{ route('kasir.riwayat') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Riwayat Pesanan
            </a>
        </nav>

        <div class="mt-auto pt-4 border-t border-black/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-[#DC0F11] bg-[#FDE7E7] rounded-full font-[900] text-[13px] hover:bg-[#DC0F11] hover:text-white transition">
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] scrollbar-none relative">

        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] text-[#1A1A1A] m-0 tracking-tight">Dashboard Kasir</h1>

           <div class="flex items-center gap-3">
                @if($sudahTutup)
                    <button type="button" class="bg-[#F3F3F3] text-[#8A8A8E] font-[800] py-1.5 px-5 rounded-full text-[12px] tracking-wide cursor-not-allowed border border-black/5" disabled>
                        Tutup Kasir Selesai
                    </button>

                    <div class="bg-[#FDE7E7] text-[#DC0F11] px-4 py-1.5 rounded-full font-[900] text-[11px] border border-[#DC0F11]/20 tracking-widest shadow-sm">
                        🔴 SHIFT BERAKHIR
                    </div>
                @else
                    <button type="button" onclick="bukaModalTutupKasir()" class="bg-[#1A1A1A] hover:bg-black text-white font-[800] py-1.5 px-5 rounded-full transition shadow-sm text-[12px] tracking-wide">
                        Tutup Kasir
                    </button>

                    <div class="bg-[#e6f4ea] text-[#03913F] px-4 py-1.5 rounded-full font-[900] text-[11px] border border-[#03913F]/20 tracking-widest shadow-sm">
                        🟢 STATUS SHIFT: AKTIF
                    </div>
                @endif
            </div>
        </header>

        <div class="p-8 flex flex-col gap-6 relative">

            @if(session('success'))
                <div class="bg-[#03913F]/10 text-[#03913F] p-4 rounded-[16px] font-[800] text-[14px] border border-[#03913F]/20 shadow-sm flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('success_closing'))
                <div class="bg-[#03913F]/10 text-[#03913F] p-6 rounded-[20px] font-[800] text-[14px] border border-[#03913F]/20 shadow-sm">
                    <h4 class="font-[900] text-[16px] text-[#03913F] mb-2">Laporan Tutup Kasir Berhasil Disimpan</h4>
                    <div class="grid grid-cols-3 gap-4 mt-3 pt-3 border-t border-[#03913F]/20">
                        <div>
                            <span class="block text-[11px] text-[#03913F] uppercase tracking-wider">Ekspektasi Uang Tunai</span>
                            <span class="text-[18px] font-[900]">Rp {{ number_format(session('totalTunaiSystem'), 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-[#03913F] uppercase tracking-wider">Uang Fisik Laci</span>
                            <span class="text-[18px] font-[900]">Rp {{ number_format(session('uangFisik'), 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] text-[#03913F] uppercase tracking-wider">Selisih Kas</span>
                            <span class="text-[18px] font-[900] {{ session('selisih') < 0 ? 'text-[#DC0F11]' : 'text-[#03913F]' }}">
                                Rp {{ number_format(session('selisih'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <p class="text-[11px] font-[700] text-[#03913F] mt-3 italic">*Selisih (-) berarti kasir nombok/uang hilang. Selisih (+) berarti uang di laci lebih/kembalian sisa.</p>
                </div>
            @endif

            <!-- KARTU RINGKASAN ATAS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                    <span class="text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider block mb-2">Pemasukan Tunai Hari Ini</span>
                    <div class="text-[32px] font-[900] text-[#1A1A1A] tracking-tight leading-none">
                        Rp {{ number_format($totalTunai ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                    <span class="text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider block mb-2">Pemasukan QRIS Hari Ini</span>
                    <div class="text-[32px] font-[900] text-[#1A1A1A] tracking-tight leading-none">
                        Rp {{ number_format($totalQris ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-[4px] bg-[#DC0F11]"></div>
                    <span class="text-[11px] font-[900] text-[#DC0F11] uppercase tracking-wider block mb-2">Total Seluruh Pendapatan</span>
                    <div class="text-[32px] font-[900] text-[#DC0F11] tracking-tight leading-none">
                        Rp {{ number_format($totalSystem ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- KARTU GRAFIK TREN PENDAPATAN (SUDAH DILENGKAPI PENJELASAN) -->
            <!-- ========================================================= -->
            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-[18px] font-[900] text-[#1A1A1A] tracking-tight">Tren Pendapatan Harian</h2>
                        <span class="text-[12px] font-[800] text-[#8A8A8E]">Komparasi Tunai & QRIS (7 hari terakhir)</span>
                    </div>
                </div>

                <!-- Kanvas Grafik -->
                <div class="relative h-[250px] w-full mb-6">
                    <canvas id="revenueLineChart"></canvas>
                </div>


        </div>
    </main>

    <!-- MODAL TUTUP KASIR -->
    <div id="modalTutupKasir" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[400px] rounded-[24px] p-8 shadow-2xl">
            <h3 class="text-[22px] font-[900] text-[#1A1A1A] mb-1">Tutup Kasir Harian</h3>
            <p class="text-[14px] text-[#8A8A8E] font-[800] mb-6 tracking-wide">Hitung uang di laci dan masukkan nominal fisiknya.</p>

            <form action="{{ route('kasir.proses_tutup') }}" method="POST">
                @csrf
                <!-- 🔥 PERUBAHAN: Sekarang kita hanya mengirim total pendapatan Tunai -->
                <input type="hidden" name="total_tunai_hidden" value="{{ $totalTunai ?? 0 }}">

                <div class="mb-4">
                    <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wider">Pendapatan Tunai Sistem</label>
                    <input type="text" class="w-full px-5 py-4 rounded-[16px] bg-[#F3F3F3] border-transparent font-[900] text-[24px] text-[#1A1A1A] outline-none" value="Rp {{ number_format($totalTunai ?? 0, 0, ',', '.') }}" readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-[11px] font-[900] text-[#DC0F11] mb-1.5 uppercase tracking-wider">Total Uang Fisik di Laci</label>
                    <input type="number" name="uang_fisik" class="w-full px-5 py-4 rounded-[16px] bg-white border-2 border-[#DC0F11] focus:ring-4 focus:ring-[#DC0F11]/20 outline-none font-[900] text-[28px] text-[#1A1A1A] transition" required placeholder="0">
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="tutupModalTutupKasir()" class="w-1/3 bg-[#efefef] text-[#1A1A1A] font-[900] rounded-full py-4 hover:bg-[#e2e2e2] transition text-[14px]">BATAL</button>
                    <button type="submit" class="w-2/3 bg-[#1A1A1A] text-white font-[900] rounded-full py-4 hover:bg-black transition shadow-lg text-[14px]">SIMPAN & HITUNG</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PENGUMPULAN DATA GRAFIK DARI DATABASE -->
    @php
        $chartLabels = [];
        $chartDataTunai = [];
        $chartDataQris = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');

            $dailyTunai = \App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))
                            ->where('status_pembayaran', 'Lunas')
                            ->where('metode_pembayaran', 'Tunai')
                            ->sum('total_harga');

            $dailyQris = \App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))
                            ->where('status_pembayaran', 'Lunas')
                            ->where('metode_pembayaran', 'QRIS')
                            ->sum('total_harga');

            $chartDataTunai[] = $dailyTunai;
            $chartDataQris[] = $dailyQris;
        }
    @endphp

    <!-- JAVASCRIPT KENDALI MODAL & CHART -->
    <script>
        function bukaModalTutupKasir() {
            let modal = document.getElementById('modalTutupKasir');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalTutupKasir() {
            let modal = document.getElementById('modalTutupKasir');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('revenueLineChart').getContext('2d');

            const labels = {!! json_encode($chartLabels) !!};
            const dataTunai = {!! json_encode($chartDataTunai) !!};
            const dataQris = {!! json_encode($chartDataQris) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pendapatan Tunai',
                            data: dataTunai,
                            borderColor: '#1A1A1A',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#1A1A1A',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Pendapatan QRIS',
                            data: dataQris,
                            borderColor: '#DC0F11',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#DC0F11',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: false,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 100000,
                            grid: { color: '#f3f3f3' },
                            ticks: {
                                precision: 0,
                                font: { family: "'Nunito', sans-serif", weight: 'bold' },
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: "'Nunito', sans-serif", weight: 'bold' }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                font: { family: "'Nunito', sans-serif", weight: 'bold', size: 12 },
                                color: '#1A1A1A'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1A1A1A',
                            titleFont: { family: "'Nunito', sans-serif", size: 14 },
                            bodyFont: { family: "'Nunito', sans-serif", size: 14, weight: 'bold' },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>
