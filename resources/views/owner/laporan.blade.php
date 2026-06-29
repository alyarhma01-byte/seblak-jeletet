<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; overflow: hidden; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        input[type=date]::-webkit-inner-spin-button, input[type=date]::-webkit-calendar-picker-indicator { cursor: pointer; }
    </style>
</head>
<body class="flex h-screen">

    <aside class="w-[260px] bg-white border-r border-black/5 flex flex-col p-6 shrink-0 z-20">
        <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[46px] h-[46px] rounded-full object-cover shadow-sm border border-black/10 shrink-0">

            <h1 class="text-[17px] font-[900] tracking-tighter m-0 p-0 mt-1 whitespace-nowrap">
                <span class="text-[#1A1A1A]">Seblak</span>
                <span class="text-[#DC0F11]">Jeletet Medan</span>
            </h1>
        </div>
        <div class="border border-black/5 rounded-[12px] p-3 flex items-center gap-3 mb-8 bg-[#F3F3F3]">
            <div class="w-[40px] h-[40px] bg-[#1A1A1A] text-white rounded-full flex items-center justify-center text-[16px] font-bold">👑</div>
            <div>
                <div class="text-[10px] text-[#8A8A8E] font-[800] uppercase tracking-wider">Owner</div>
                <div class="text-[14px] font-[900] text-[#1A1A1A] leading-tight">{{ Auth::user()->name ?? 'Pemilik Warung' }}</div>
            </div>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Dashboard</a>
            <a href="{{ route('owner.menu') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Daftar Menu</a>
            <a href="{{ route('owner.kategori') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Kategori</a>
            <a href="{{ route('owner.pengeluaran') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Pengeluaran</a>
            <a href="{{ route('owner.meja') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Meja</a>
            <a href="{{ route('owner.laporan') }}" class="flex items-center gap-4 px-4 py-3 bg-[#DC0F11] text-white rounded-[12px] font-[800] text-[14px] transition shadow-md">Laporan Transaksi</a>
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

    <main class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] scrollbar-none relative">
        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] text-[#1A1A1A] m-0 tracking-tight">Laporan Rekapitulasi Transaksi</h1>
            <div class="flex items-center gap-3">
                <!-- TOMBOL EXPORT SUDAH MENYIMPAN FILTER METODE PEMBAYARAN -->
                <a href="{{ route('owner.laporan.export', ['type' => 'excel', 'start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate), 'metode_pembayaran' => request('metode_pembayaran', 'Semua')]) }}" class="bg-[#1A1A1A] hover:bg-black text-white font-[800] py-2 px-5 rounded-full transition text-[12px] tracking-wide shadow-sm flex items-center gap-2">Ekspor Excel</a>
                <a href="{{ route('owner.laporan.export', ['type' => 'pdf', 'start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate), 'metode_pembayaran' => request('metode_pembayaran', 'Semua')]) }}" target="_blank" class="bg-[#DC0F11] hover:opacity-80 text-white font-[800] py-2 px-5 rounded-full transition text-[12px] tracking-wide shadow-md flex items-center gap-2">Cetak PDF</a>
            </div>
        </header>

        <div class="p-8 flex flex-col gap-6 relative">

            <div class="w-full bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <form action="{{ route('owner.laporan') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4 w-full">
                    <div class="w-full md:w-1/4">
                        <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Periode Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[14px] font-[800] text-black transition cursor-pointer">
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Periode Selesai</label>
                        <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[14px] font-[800] text-black transition cursor-pointer">
                    </div>
                    <!-- FITUR BARU: FILTER METODE PEMBAYARAN -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[14px] font-[800] text-black transition cursor-pointer appearance-none">
                            <option value="Semua" {{ request('metode_pembayaran') == 'Semua' ? 'selected' : '' }}>Semua (Tunai & QRIS)</option>
                            <option value="Tunai" {{ request('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Hanya Tunai (Cash)</option>
                            <option value="QRIS" {{ request('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>Hanya QRIS (Transfer)</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <button type="submit" class="w-full bg-[#1A1A1A] hover:bg-black text-white font-[900] py-3.5 px-8 rounded-[12px] transition shadow-md text-[14px] tracking-wide">Terapkan Filter</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                    <span class="text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider block mb-2">Total Pemasukan (Omzet)</span>
                    <div class="text-[32px] font-[900] text-[#1A1A1A] tracking-tight leading-none">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                    <span class="text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider block mb-2">Total Pengeluaran</span>
                    <div class="text-[32px] font-[900] text-[#DC0F11] tracking-tight leading-none">- Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-[4px] bg-[#03913F]"></div>
                    <span class="text-[11px] font-[900] text-[#03913F] uppercase tracking-wider block mb-2">Total Laba Bersih</span>
                    <div class="text-[32px] font-[900] text-[#03913F] tracking-tight leading-none">Rp {{ number_format($labaBersih ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="mb-4">
                    <h2 class="text-[18px] font-[900] text-[#1A1A1A] tracking-tight">Kurva Tren Penjualan</h2>
                    <span class="text-[12px] font-[800] text-[#8A8A8E]">Pergerakan omzet tunai dan QRIS pada rentang tanggal yang dipilih.</span>
                </div>
                <div class="relative h-[220px] w-full"><canvas id="reportLineChart"></canvas></div>
            </div>

            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-[18px] font-[900] text-[#1A1A1A] tracking-tight">Rincian Buku Besar Transaksi (Pemasukan)</h2>
                        <p class="text-[13px] font-[700] text-[#8A8A8E]">Pilih kategori untuk melihat transaksi Kasir atau Pesanan QR.</p>
                    </div>
                    <div class="flex gap-2 bg-[#F3F3F3] p-1 rounded-[12px] border border-black/5">
                        <button type="button" onclick="switchTab('prasmanan')" id="btn-prasmanan" class="px-5 py-2 rounded-[8px] font-[900] text-[12px] transition bg-[#1A1A1A] text-white shadow-sm">KASIR PRASMANAN</button>
                        <button type="button" onclick="switchTab('qr')" id="btn-qr" class="px-5 py-2 rounded-[8px] font-[900] text-[12px] transition text-[#8A8A8E] hover:text-[#1A1A1A]">PESANAN QR (MEJA)</button>
                    </div>
                </div>

                <div id="tab-prasmanan" class="overflow-x-auto rounded-[12px] border border-black/5 block">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F3F3F3] text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                                <th class="p-4 w-[140px]">ID & Waktu</th>
                                <th class="p-4 w-[160px]">Info Pelanggan</th>
                                <th class="p-4">Rincian Item</th>
                                <th class="p-4 text-right w-[150px]">Total Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-[14px]">
                            @forelse($ordersPrasmanan as $row)
                                <tr class="hover:bg-[#FAF7F4]/50 transition">
                                    <td class="p-4 align-top">
                                        <span class="block font-[900] text-[#1A1A1A] mb-1">#INV-{{ $row->id }}</span>
                                        <span class="text-[12px] font-[700] text-[#8A8A8E]">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y - H:i') }}</span>
                                    </td>
                                    <td class="p-4 align-top">
                                        <span class="block font-[900] text-[#DC0F11] mb-1">Meja #{{ str_pad($row->no_meja, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[13px] font-[800] text-[#1A1A1A]">{{ $row->nama_pelanggan }}</span>
                                    </td>
                                    <td class="p-4 align-top">
                                        <ul class="flex flex-col gap-2">
                                            @foreach($row->details as $item)
                                            <li class="flex items-start text-[13px]">
                                                <span class="font-[900] text-[#1A1A1A] w-[30px]">{{ $item->qty }}x</span>
                                                <div class="flex-1"><span class="font-[800] text-[#5e5e5e]">{{ $item->menu_name }}</span></div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-4 align-top text-right">
                                        <span class="block font-[900] text-[16px] text-[#1A1A1A]">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</span>
                                        <span class="inline-block px-2 py-1 mt-1 rounded-[6px] text-[10px] font-[900] tracking-wide {{ $row->metode_pembayaran == 'Tunai' ? 'bg-[#E5E7EB] text-[#374151]' : 'bg-[#FDE7E7] text-[#DC0F11]' }}">{{ strtoupper($row->metode_pembayaran) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-12 text-center text-[#8A8A8E] font-[700]">Belum ada transaksi Prasmanan Kasir sesuai filter ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="tab-qr" class="overflow-x-auto rounded-[12px] border border-black/5 hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F3F3F3] text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                                <th class="p-4 w-[140px]">ID & Waktu</th>
                                <th class="p-4 w-[160px]">Info Pelanggan</th>
                                <th class="p-4">Rincian Item</th>
                                <th class="p-4 text-right w-[150px]">Total Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-[14px]">
                            @forelse($ordersQr as $row)
                                <tr class="hover:bg-[#FAF7F4]/50 transition">
                                    <td class="p-4 align-top">
                                        <span class="block font-[900] text-[#1A1A1A] mb-1">#INV-{{ $row->id }}</span>
                                        <span class="text-[12px] font-[700] text-[#8A8A8E]">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y - H:i') }}</span>
                                    </td>
                                    <td class="p-4 align-top">
                                        <span class="block font-[900] text-[#DC0F11] mb-1">Meja #{{ str_pad($row->no_meja, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[13px] font-[800] text-[#1A1A1A]">{{ $row->nama_pelanggan }}</span><br>
                                        <span class="text-[11px] font-[800] text-[#8A8A8E]">{{ $row->tipe_pesanan }}</span>
                                    </td>
                                    <td class="p-4 align-top">
                                        <ul class="flex flex-col gap-2">
                                            @foreach($row->details as $item)
                                            <li class="flex items-start text-[13px]">
                                                <span class="font-[900] text-[#1A1A1A] w-[30px]">{{ $item->qty }}x</span>
                                                <div class="flex-1">
                                                    <span class="font-[800] text-[#5e5e5e]">{{ $item->menu_name }}</span>
                                                    @if($item->catatan || $item->level !== null || $item->kencur || $item->kuah)
                                                        <div class="text-[11px] text-[#8A8A8E] mt-0.5 leading-snug">
                                                            @if($item->catatan) *{{ $item->catatan }}* @endif
                                                            @if($item->level !== null) [Lv {{ $item->level }}] @endif
                                                            @if($item->kencur) [Kencur {{ $item->kencur }}] @endif
                                                            @if($item->kuah) [Kuah {{ $item->kuah }}] @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-4 align-top text-right">
                                        <span class="block font-[900] text-[16px] text-[#1A1A1A]">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</span>
                                        <span class="inline-block px-2 py-1 mt-1 rounded-[6px] text-[10px] font-[900] tracking-wide {{ $row->metode_pembayaran == 'Tunai' ? 'bg-[#E5E7EB] text-[#374151]' : 'bg-[#FDE7E7] text-[#DC0F11]' }}">{{ strtoupper($row->metode_pembayaran) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-12 text-center text-[#8A8A8E] font-[700]">Belum ada transaksi Meja QR sesuai filter ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="mb-6">
                    <h2 class="text-[18px] font-[900] text-[#1A1A1A] tracking-tight">Rincian Pengeluaran Operasional</h2>
                    <p class="text-[13px] font-[700] text-[#8A8A8E]">Daftar biaya pengeluaran bahan baku atau operasional warung.</p>
                </div>
                <div class="overflow-x-auto rounded-[12px] border border-black/5">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F3F3F3] text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                                <th class="p-4 w-[160px]">Tanggal</th>
                                <th class="p-4">Keterangan Pengeluaran</th>
                                <th class="p-4 text-right w-[180px]">Nominal Biaya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-[14px]">
                            @forelse($pengeluaran ?? [] as $out)
                                <tr class="hover:bg-[#FAF7F4]/50 transition">
                                    <td class="p-4 font-[800] text-[#5e5e5e]">
                                        {{ \Carbon\Carbon::parse($out->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="p-4 font-[900] text-[#1A1A1A]">
                                        {{ $out->nama_pengeluaran ?? $out->keterangan ?? '-' }}
                                    </td>
                                    <td class="p-4 text-right font-[900] text-[#DC0F11]">
                                        Rp {{ number_format($out->total_biaya ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-12 text-center text-[#8A8A8E] font-[700]">Belum ada catatan data pengeluaran di periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="mb-6">
                    <h2 class="text-[18px] font-[900] text-[#1A1A1A] tracking-tight">Rekap Tutup Kasir (Validasi Keuangan)</h2>
                    <p class="text-[13px] font-[700] text-[#8A8A8E]">Data pencocokan uang fisik laci dengan sistem oleh kasir.</p>
                </div>
                <div class="overflow-x-auto rounded-[12px] border border-black/5">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F3F3F3] text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                                <th class="p-4">Tanggal</th>
                                <th class="p-4 text-right">Total Uang di Sistem (Tunai)</th>
                                <th class="p-4 text-right">Uang Fisik di Laci</th>
                                <th class="p-4 text-right">Selisih</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-[14px]">
                            @forelse($closingShifts ?? [] as $close)
                                <tr class="hover:bg-[#FAF7F4]/50 transition">
                                    <td class="p-4 font-[800] text-[#1A1A1A]">{{ \Carbon\Carbon::parse($close->tanggal)->format('d M Y') }}</td>
                                    <td class="p-4 text-right font-[800] text-[#5e5e5e]">Rp {{ number_format($close->total_system, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right font-[900] text-[#1A1A1A]">Rp {{ number_format($close->uang_fisik, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right font-[900] {{ $close->selisih < 0 ? 'text-[#DC0F11]' : ($close->selisih > 0 ? 'text-blue-600' : 'text-[#03913F]') }}">Rp {{ number_format($close->selisih, 0, ',', '.') }}</td>
                                    <td class="p-4 text-center">
                                        @if($close->selisih < 0)
                                            <span class="bg-[#FDE7E7] text-[#DC0F11] px-3 py-1 rounded-[6px] text-[11px] font-[900]">MINUS</span>
                                        @elseif($close->selisih > 0)
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-[6px] text-[11px] font-[900]">LEBIH</span>
                                        @else
                                            <span class="bg-[#e6f4ea] text-[#03913F] px-3 py-1 rounded-[6px] text-[11px] font-[900]">BALANCE</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-12 text-center text-[#8A8A8E] font-[700] text-[13px]">Belum ada data tutup kasir pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Logika Switch Tab Tabel Pemasukan
        function switchTab(tab) {
            const btnPrasmanan = document.getElementById('btn-prasmanan');
            const btnQr = document.getElementById('btn-qr');
            const tabPrasmanan = document.getElementById('tab-prasmanan');
            const tabQr = document.getElementById('tab-qr');

            if (tab === 'prasmanan') {
                btnPrasmanan.className = "px-5 py-2 rounded-[8px] font-[900] text-[12px] transition bg-[#1A1A1A] text-white shadow-sm";
                tabPrasmanan.classList.remove('hidden');
                tabPrasmanan.classList.add('block');

                btnQr.className = "px-5 py-2 rounded-[8px] font-[900] text-[12px] transition text-[#8A8A8E] hover:text-[#1A1A1A]";
                tabQr.classList.remove('block');
                tabQr.classList.add('hidden');
            } else {
                btnQr.className = "px-5 py-2 rounded-[8px] font-[900] text-[12px] transition bg-[#1A1A1A] text-white shadow-sm";
                tabQr.classList.remove('hidden');
                tabQr.classList.add('block');

                btnPrasmanan.className = "px-5 py-2 rounded-[8px] font-[900] text-[12px] transition text-[#8A8A8E] hover:text-[#1A1A1A]";
                tabPrasmanan.classList.remove('block');
                tabPrasmanan.classList.add('hidden');
            }
        }

        // Logika Chart JS
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('reportLineChart').getContext('2d');
            const graphLabels = {!! json_encode($chartLabels ?? []) !!};
            const graphTunai = {!! json_encode($chartDataTunai ?? []) !!};
            const graphQris = {!! json_encode($chartDataQris ?? []) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: graphLabels,
                    datasets: [
                        { label: 'Omzet Tunai', data: graphTunai, borderColor: '#1A1A1A', borderWidth: 3, pointBackgroundColor: '#fff', pointBorderColor: '#1A1A1A', pointBorderWidth: 2, pointRadius: 4, fill: false, tension: 0.2 },
                        { label: 'Omzet QRIS', data: graphQris, borderColor: '#DC0F11', borderWidth: 3, pointBackgroundColor: '#fff', pointBorderColor: '#DC0F11', pointBorderWidth: 2, pointRadius: 4, fill: false, tension: 0.2 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, suggestedMax: 100000, grid: { color: '#f3f3f3' }, ticks: { precision: 0, font: { family: "'Nunito', sans-serif", weight: 'bold' }, callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                        x: { grid: { display: false }, ticks: { font: { family: "'Nunito', sans-serif", weight: 'bold' } } }
                    },
                    plugins: {
                        legend: { display: true, position: 'top', align: 'end', labels: { usePointStyle: true, font: { family: "'Nunito', sans-serif", weight: 'bold', size: 12 }, color: '#1A1A1A' } },
                        tooltip: { backgroundColor: '#1A1A1A', titleFont: { family: "'Nunito', sans-serif", size: 14 }, bodyFont: { family: "'Nunito', sans-serif", size: 14, weight: 'bold' }, padding: 12, callbacks: { label: function(context) { let label = context.dataset.label || ''; if (label) label += ': '; if (context.parsed.y !== null) label += 'Rp ' + context.parsed.y.toLocaleString('id-ID'); return label; } } }
                    }
                }
            });
        });
    </script>
</body>
</html>
