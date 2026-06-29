<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #F1F5F9; color: #1A1A1A; overflow: hidden; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex h-screen">

    <aside class="w-[250px] bg-white border-r border-black/5 flex flex-col py-8 px-6 shrink-0 z-20 shadow-sm">
        <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[46px] h-[46px] rounded-full object-cover shadow-sm border border-black/10 shrink-0">

            <h1 class="text-[17px] font-[900] tracking-tighter m-0 p-0 mt-1 whitespace-nowrap">
                <span class="text-[#1A1A1A]">Seblak</span>
                <span class="text-[#DC0F11]">Jeletet Medan</span>
            </h1>
        </div>

        <div class="border border-black/5 rounded-[12px] p-3 flex items-center gap-3 mb-8 bg-[#F3F3F3]">
            <div class="w-[40px] h-[40px] bg-[#1A1A1A] text-white rounded-full flex items-center justify-center text-[16px] font-bold">
                👑
            </div>
            <div>
                <div class="text-[10px] text-[#8A8A8E] font-[800] uppercase tracking-wider">Owner</div>
                <div class="text-[14px] font-[900] text-[#1A1A1A] leading-tight">{{ Auth::user()->name ?? 'Pemilik Warung' }}</div>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-4 px-4 py-3 bg-[#DC0F11] text-white rounded-[12px] font-[800] text-[14px] transition shadow-md">
                <span class="text-[18px] opacity-70"></span> Dashboard
            </a>
            <a href="{{ route('owner.menu') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Daftar Menu
            </a>
            <a href="{{ route('owner.kategori') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Kategori
            </a>
            <a href="{{ route('owner.pengeluaran') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Pengeluaran
            </a>
            <a href="{{ route('owner.meja') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Meja
            </a>

            <a href="{{ route('owner.laporan') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-90"></span> Laporan Transaksi
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

    <main class="flex-1 flex flex-col overflow-y-auto bg-[#F1F5F9] scrollbar-none">

        <header class="bg-white/80 backdrop-blur-md px-10 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[26px] font-[900] text-[#1A1A1A] tracking-tight m-0">Dashboard</h1>
        </header>

        <div class="p-10 flex flex-col gap-6">

            @if(session('success'))
                <div class="bg-[#03913F]/10 text-[#03913F] p-4 rounded-[16px] font-[800] text-[14px] border border-[#03913F]/20 shadow-sm flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white text-[#1A1A1A] border border-black/5 p-6 rounded-[16px] shadow-sm">
                    <div class="text-[13px] font-[800] text-[#8A8A8E] mb-2 tracking-wide">Total Pendapatan</div>
                    <div class="text-[32px] font-[900] leading-none text-[#1A1A1A]">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-[#DC0F11] text-white p-6 rounded-[16px] shadow-md relative overflow-hidden">
                    <div class="absolute -right-4 -top-2 text-[60px] opacity-10">💵</div>
                    <div class="text-[13px] font-[800] text-white/80 mb-2 tracking-wide">Pendapatan Tunai</div>
                    <div class="text-[32px] font-[900] leading-none">Rp {{ number_format($pendapatanTunai, 0, ',', '.') }}</div>
                </div>
                <div class="bg-[#03913F] text-white p-6 rounded-[16px] shadow-md relative overflow-hidden">
                    <div class="absolute -right-4 -top-2 text-[60px] opacity-10">📱</div>
                    <div class="text-[13px] font-[800] text-white/80 mb-2 tracking-wide">Pendapatan QRIS</div>
                    <div class="text-[32px] font-[900] leading-none">Rp {{ number_format($pendapatanQris, 0, ',', '.') }}</div>
                </div>
                <div class="bg-[#E85D32] text-white p-6 rounded-[16px] shadow-md relative overflow-hidden">
                    <div class="absolute -right-4 -top-2 text-[60px] opacity-10">🧾</div>
                    <div class="text-[13px] font-[800] text-white/80 mb-2 tracking-wide">Total Transaksi</div>
                    <div class="text-[32px] font-[900] leading-none">{{ $totalTransaksi }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="bg-white border border-black/5 p-6 rounded-[16px] shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-[16px] font-[900]">Trend Penjualan</h2>
                        <div class="flex gap-4 text-[11px] font-[800] text-[#8A8A8E] uppercase">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#DC0F11]"></span> Tunai</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#03913F]"></span> QRIS</span>
                        </div>
                    </div>
                    <div class="relative h-[250px] w-full">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white border border-black/5 p-6 rounded-[16px] shadow-sm flex flex-col">
                    <h2 class="text-[16px] font-[900] mb-6">Transactions</h2>
                    <div class="flex-1 flex flex-col gap-4">
                        @forelse($recentOrders as $ro)
                            <div class="flex justify-between items-center pb-3 border-b border-black/5 last:border-0">
                                <div>
                                    <div class="text-[13px] font-[800] text-[#1A1A1A]">{{ $ro->nama_pelanggan }}</div>
                                    <div class="text-[11px] font-[700] text-[#8A8A8E]">Meja #{{ $ro->no_meja }}</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 rounded-[4px] text-[9px] font-[900] uppercase tracking-wide {{ $ro->metode_pembayaran == 'Midtrans' ? 'bg-[#1A1A1A] text-white' : 'bg-[#DC0F11] text-white' }}">
                                        {{ $ro->metode_pembayaran == 'Midtrans' ? 'QRIS' : 'TUNAI' }}
                                    </span>
                                    <span class="text-[13px] font-[900] text-[#03913F]">+{{ number_format($ro->total_harga/1000, 0) }}k</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-[12px] font-[800] text-[#8A8A8E] text-center py-10">Belum ada transaksi.</div>
                        @endforelse
                    </div>
                    <a href="{{ route('kasir.riwayat') }}" class="mt-4 w-full bg-[#FFB020] hover:bg-[#e59e1c] text-[#1A1A1A] font-[900] text-[13px] py-3 rounded-[8px] text-center transition">
                        Lihat Semua Transaksi
                    </a>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="bg-white border border-black/5 p-6 rounded-[16px] shadow-sm flex flex-col items-center justify-center relative w-full">
                    <h2 class="text-[16px] font-[900] w-full mb-4 text-center lg:text-left">Metode Bayar</h2>

                    <div class="relative w-[180px] h-[180px] mb-5">
                        <canvas id="salesChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-[24px] font-[900] leading-none">{{ $totalTransaksi }}</span>
                            <span class="text-[10px] font-[800] text-[#8A8A8E] uppercase tracking-wide mt-1">Total</span>
                        </div>
                    </div>

                    <div class="flex gap-5 text-[12px] font-[800] text-[#1A1A1A] justify-center w-full mb-5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#DC0F11] shadow-sm"></span> Tunai
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#1A1A1A] shadow-sm"></span> QRIS
                        </div>
                    </div>
                </div>


                <div class="bg-white border border-black/5 p-6 rounded-[16px] shadow-sm lg:col-span-2">
                    <h2 class="text-[16px] font-[900] mb-6">Akses Cepat Manajemen</h2>

                    <div class="flex flex-col gap-4">
                        <a href="{{ route('owner.menu') }}" class="flex justify-between items-center hover:bg-[#F3F3F3]/50 p-2 rounded-[8px] transition">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-[#03913F]"></div>
                                <span class="text-[14px] font-[800] text-[#1A1A1A]">Kelola Menu Makanan</span>
                            </div>
                            <span class="bg-black text-white px-2 py-1 rounded-[4px] text-[10px] font-[900]">{{ $totalMenu }} Item</span>
                        </a>
                        <a href="{{ route('owner.kategori') }}" class="flex justify-between items-center hover:bg-[#F3F3F3]/50 p-2 rounded-[8px] transition">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-[#E85D32]"></div>
                                <span class="text-[14px] font-[800] text-[#1A1A1A]">Kategori & Variasi</span>
                            </div>
                            <span class="bg-[#F3F3F3] text-black px-2 py-1 rounded-[4px] text-[10px] font-[900]">{{ $totalKategori }} Kategori</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script>
        const labelsBulan = {!! json_encode($trendBulan) !!};
        const dataTunai = {!! json_encode($dataTunai) !!};
        const dataQris = {!! json_encode($dataQris) !!};

        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: labelsBulan,
                datasets: [
                    {
                        label: 'Tunai',
                        data: dataTunai,
                        backgroundColor: '#DC0F11',
                        borderRadius: 4,
                        barPercentage: 0.5,
                        categoryPercentage: 0.4
                    },
                    {
                        label: 'QRIS',
                        data: dataQris,
                        backgroundColor: '#03913F',
                        borderRadius: 4,
                        barPercentage: 0.5,
                        categoryPercentage: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#E5E7EB' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) return (value / 1000) + 'k';
                                return value;
                            }
                        }
                    }
                }
            }
        });

        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'doughnut',
            data: {
                labels: ['Tunai', 'QRIS'],
                datasets: [{
                    // 🔥 DI SINI PERUBAHANNYA: MENGGUNAKAN JUMLAH TRANSAKSI
                    data: [{{ $transaksiTunai ?? 0 }}, {{ $transaksiQris ?? 0 }}],
                    backgroundColor: ['#DC0F11', '#1A1A1A'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed !== null) {
                                    // 🔥 DI SINI PERUBAHANNYA: MENGUBAH FORMAT RUPIAH JADI KATA TRANSAKSI
                                    label += context.parsed + ' Transaksi';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
