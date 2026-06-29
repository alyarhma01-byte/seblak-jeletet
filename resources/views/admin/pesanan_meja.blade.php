<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Meja - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; overflow: hidden; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
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
            <div class="w-[40px] h-[40px] bg-[#FDE7E7] text-[#DC0F11] rounded-full flex items-center justify-center text-[20px]">👨‍🍳</div>
            <div>
                <div class="text-[10px] text-[#8A8A8E] font-[800] uppercase tracking-wider">Cashier</div>
                <div class="text-[14px] font-[900] text-[#1A1A1A] leading-tight">{{ Auth::user()->name ?? 'Kasir Utama' }}</div>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Dashboard Overview
            </a>
            <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Kasir Prasmanan
            </a>
            <a href="{{ route('kasir.meja') }}" class="flex items-center gap-3 px-4 py-3 bg-black text-white rounded-full font-[800] text-[13px] transition shadow-md">
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

    <main class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] scrollbar-none relative">
        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] text-[#1A1A1A] m-0 tracking-tight">Pesanan Meja</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariPesananQR()" placeholder="Cari nama atau meja..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[240px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <div class="bg-[#FDE7E7] text-[#DC0F11] px-4 py-1.5 rounded-full font-[900] text-[11px] border border-[#DC0F11]/20 tracking-widest shadow-sm shrink-0">
                    🔴 LIVE UPDATE
                </div>
            </div>
        </header>

        <div class="p-8 flex flex-col gap-8 relative">
            <div class="w-full">
                <h2 class="text-[18px] font-[900] text-[#1A1A1A] mb-5 tracking-tight flex items-center gap-2">
                    Daftar Pesanan Aktif
                </h2>

                @if(session('success')) <div class="mb-6 bg-[#03913F]/10 text-[#03913F] p-4 rounded-[12px] font-[800] text-[14px] border border-[#03913F]/20 shadow-sm flex items-center gap-2">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="mb-6 bg-[#FDE7E7] text-[#DC0F11] p-4 rounded-[12px] font-[800] text-[14px] border border-[#DC0F11]/20 shadow-sm flex items-center gap-2">⚠️ {{ session('error') }}</div> @endif

                <div class="grid grid-cols-1 gap-6" id="daftarPesananContainer">
                    @forelse($orders as $order)
                        <div class="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-black/5 flex flex-col lg:flex-row overflow-hidden transition hover:shadow-md data-order">

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4 border-b border-black/5 pb-4">
                                    <div>
                                        <span class="text-[10px] text-[#DC0F11] font-[900] tracking-wider uppercase">Meja Pelanggan No</span><br>
                                        <div class="flex items-baseline gap-3 mt-1">
                                            <b class="text-[28px] font-[900] text-[#1A1A1A] leading-none data-meja">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</b>
                                            <span class="text-[13px] font-[900] text-[#8A8A8E] uppercase tracking-wide bg-[#F3F3F3] px-3 py-1.5 rounded-full data-nama">
                                                👤 {{ $order->nama_pelanggan }}
                                            </span>
                                        </div>
                                        <div class="mt-2 text-[12px] font-[800] text-[#8A8A8E]">
                                            Waktu: <span class="text-[#1A1A1A]">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} WIB</span> |
                                            Antrean: <span class="text-[#1A1A1A]">#{{ $order->id }}</span>
                                        </div>
                                    </div>
                                    <div class="inline-block px-3 py-1.5 rounded-[8px] text-[11px] font-[900] tracking-wide {{ $order->status_pembayaran == 'Lunas' ? 'bg-[#03913F]/10 text-[#03913F]' : 'bg-[#FDE7E7] text-[#DC0F11]' }}">
                                        STATUS: {{ strtoupper($order->status_pembayaran) }}
                                    </div>
                                </div>

                                <div class="flex-1">
                                    @foreach($order->details as $item)
                                    <div class="flex items-start text-[14px] mb-3 last:mb-0">
                                        <div class="font-[900] text-[#DC0F11] w-[40px] mt-0.5">{{ $item->qty }}x</div>
                                        <div class="flex-1 pr-2">
                                            <b class="text-[#1A1A1A] block mb-1.5 leading-tight">{{ $item->menu_name }}</b>
                                            @if($item->catatan)
                                                <div class="text-[12px] text-[#5e5e5e] mb-1.5 font-[600] italic leading-snug">
                                                    Isian: {{ $item->catatan }}
                                                </div>
                                            @endif
                                            <div class="text-[11px] text-[#8A8A8E] flex flex-wrap gap-1.5">
                                                @if($item->level !== null) <span class="bg-[#F3F3F3] px-2 py-1 rounded-[6px] text-[#5e5e5e] font-[800]">Lv {{ $item->level }}</span> @endif
                                                @if($item->kencur) <span class="bg-[#F3F3F3] px-2 py-1 rounded-[6px] text-[#5e5e5e] font-[800]">{{ $item->kencur }}</span> @endif
                                                @if($item->kuah) <span class="bg-[#F3F3F3] px-2 py-1 rounded-[6px] text-[#5e5e5e] font-[800]">{{ $item->kuah }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="text-[15px] font-[900] text-[#1A1A1A] mt-0.5">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-6 lg:w-[320px] bg-[#fafafa] flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-black/5">
                                <span class="block text-[12px] font-[900] text-[#8A8A8E] mb-1 uppercase tracking-wider">Total Tagihan Keseluruhan</span>
                                <div class="text-[32px] font-[900] text-[#1A1A1A] mb-6 leading-none">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>

                                @if($order->status_pembayaran == 'Belum Bayar')

                                    @if($order->metode_pembayaran == 'Tunai')
                                        <div class="text-center p-3 mb-3 rounded-[12px] bg-[#FDE7E7] border border-[#DC0F11]/20">
                                            <span class="text-[11px] font-[900] text-[#DC0F11] tracking-wider uppercase">🏃‍♂️ Pelanggan Menuju Kasir (Tunai)</span>
                                        </div>
                                        <button type="button" class="w-full bg-black text-white rounded-[12px] py-3 text-[13px] font-[900] hover:opacity-80 transition shadow-md tracking-wide flex items-center justify-center gap-2"
                                            onclick="bukaModalBayar('{{ $order->id }}', '{{ $order->no_meja }}', '{{ $order->total_harga }}')">
                                            TERIMA PEMBAYARAN TUNAI
                                        </button>
                                    @else
                                        <div class="text-center p-3 mb-3 rounded-[12px] bg-[#e6f4ea] border border-[#03913F]/20">
                                            <span class="text-[11px] font-[900] text-[#03913F] tracking-wider uppercase">Pembayaran via QRIS</span>
                                        </div>

                                        
                                        @if($order->bukti_bayar === 'DITOLAK')
                                            <div class="text-center p-3 rounded-[12px] bg-[#FDE7E7] border-[2px] border-[#DC0F11]/40 shadow-sm animate-pulse">
                                                <span class="block text-[12px] font-[900] text-[#DC0F11] uppercase tracking-wider mb-1">⚠️ Bukti Ditolak</span>
                                                <span class="text-[10px] font-[800] text-[#DC0F11]/80">Menunggu pelanggan upload ulang...</span>
                                            </div>
                                        @elseif($order->bukti_bayar)
                                            <button type="button" class="w-full bg-[#03913F] text-white rounded-[12px] py-3 text-[13px] font-[900] hover:bg-green-700 transition shadow-md tracking-wide animate-pulse"
                                                onclick="bukaModalBukti('{{ asset($order->bukti_bayar) }}', '{{ $order->id }}', '{{ $order->no_meja }}', '{{ $order->total_harga }}')">
                                                CEK BUKTI TRANSFER
                                            </button>
                                        @else
                                            <div class="text-center p-3 rounded-[12px] bg-[#F3F3F3] border border-black/5">
                                                <span class="text-[11px] font-[800] text-[#8A8A8E]">⏳ Menunggu Bukti Transfer...</span>
                                            </div>
                                        @endif
                                    @endif


                                @elseif($order->status_pembayaran == 'Kurang Bayar')
                                    @php
                                        $kekurangan = $order->total_harga - ($order->uang_bayar ?? 0);
                                    @endphp
                                    <div class="text-center p-3 mb-3 rounded-[12px] bg-[#FDE7E7] border border-[#DC0F11]/20">
                                        <span class="block text-[11px] font-[900] text-[#DC0F11] tracking-wider uppercase mb-1">Kekurangan Bayar</span>
                                        <span class="text-[18px] font-[900] text-[#DC0F11]">Rp {{ number_format($kekurangan, 0, ',', '.') }}</span>
                                    </div>


                                    @if($order->bukti_bayar === 'DITOLAK')
                                        <div class="text-center p-3 rounded-[12px] bg-[#FDE7E7] border-[2px] border-[#DC0F11]/40 shadow-sm animate-pulse mb-3">
                                            <span class="block text-[12px] font-[900] text-[#DC0F11] uppercase tracking-wider mb-1">⚠️ Bukti Sisa Ditolak</span>
                                            <span class="text-[10px] font-[800] text-[#DC0F11]/80">Menunggu pelanggan upload ulang...</span>
                                        </div>
                                    @elseif($order->bukti_bayar)
                                        <button type="button" class="w-full bg-[#03913F] text-white rounded-[12px] py-3 text-[13px] font-[900] hover:bg-green-700 transition shadow-md tracking-wide animate-pulse flex items-center justify-center gap-1.5"
                                            onclick="bukaModalBukti('{{ asset($order->bukti_bayar) }}', '{{ $order->id }}', '{{ $order->no_meja }}', '{{ $kekurangan }}')">
                                            <span>📸</span> CEK BUKTI KEKURANGAN
                                        </button>
                                    @else
                                        <div class="text-center p-3 bg-[#F3F3F3] rounded-[12px] border border-black/5 mb-3">
                                            <span class="text-[11px] font-[800] text-[#8A8A8E]">⏳ Menunggu Bukti Transfer Sisa...</span>
                                        </div>
                                    @endif

                                @else
                                    @php
                                        $kembalian = ($order->uang_bayar ?? $order->total_harga) - $order->total_harga;
                                    @endphp

                                    @if($kembalian > 0 && $order->metode_pembayaran == 'QRIS')
                                        <div class="text-center p-3 mb-3 rounded-[12px] bg-[#e6f4ea] border border-[#03913F]/20">
                                            <span class="block text-[11px] font-[900] text-[#03913F] tracking-wider uppercase mb-1">Kembalian QRIS (Antar Ke Meja)</span>
                                            <span class="text-[18px] font-[900] text-[#03913F]">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                    <form action="{{ route('kasir.selesai', $order->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-[#1A1A1A] hover:bg-black text-white rounded-[12px] py-3 text-[13px] font-[900] transition shadow-lg tracking-wide border border-transparent">
                                            TANDAI SELESAI
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div id="emptyDatabaseRow" class="col-span-full flex flex-col items-center justify-center py-32 text-[#8A8A8E] border-2 border-dashed border-black/10 rounded-[20px] bg-white">
                            <div class="text-[48px] mb-3 opacity-50">📱</div>
                            <div class="font-[900] text-[18px] text-[#1A1A1A] mb-1">Belum Ada Pesanan QR</div>
                            <div class="font-[700] text-[14px]">Pesanan dari HP pelanggan akan muncul di sini.</div>
                        </div>
                    @endforelse

                    <div id="notFoundRow" class="hidden col-span-full flex flex-col items-center justify-center py-16 text-[#8A8A8E] border-2 border-dashed border-black/10 rounded-[20px] bg-white">
                        <div class="text-[32px] mb-2 opacity-50">🔍</div>
                        <div class="font-[900] text-[16px] text-[#1A1A1A]">Pesanan Tidak Ditemukan</div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Modal Tunai -->
    <div id="modalBayar" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[400px] rounded-[24px] p-8 shadow-2xl">
            <h3 class="text-[22px] font-[900] text-[#1A1A1A] mb-1">Terima Tunai</h3>
            <p id="modal-info-meja" class="text-[14px] text-[#8A8A8E] font-[800] mb-6 tracking-wide"></p>
            <form action="" id="form-lunas-tunai" method="POST">
                @csrf
                <input type="hidden" name="metode_pembayaran" value="Tunai">
                <div class="mb-4">
                    <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wider">Nominal Tagihan</label>
                    <input type="text" id="total-tagihan-display" class="w-full px-5 py-4 rounded-[16px] bg-[#F3F3F3] border-transparent font-[900] text-[24px] text-[#1A1A1A] outline-none" readonly>
                    <input type="hidden" id="total-tagihan-raw" name="total_tagihan">
                </div>
                <div class="mb-6">
                    <label class="block text-[11px] font-[900] text-[#DC0F11] mb-1.5 uppercase tracking-wider">Uang Pelanggan</label>
                    <input type="number" id="uang-bayar" name="uang_bayar" class="w-full px-5 py-4 rounded-[16px] bg-white border-2 border-[#DC0F11] focus:ring-4 focus:ring-[#DC0F11]/20 outline-none font-[900] text-[28px] text-[#1A1A1A] transition" required oninput="hitungKembalian()" placeholder="0">
                </div>
                <div class="bg-[#F3F3F3] p-5 rounded-[16px] text-center mb-8 border border-black/5">
                    <span class="block text-[11px] text-[#8A8A8E] font-[900] mb-1 uppercase tracking-wider">Kembalian</span>
                    <div id="kembalian-display" class="text-[36px] font-[900] text-[#1A1A1A] leading-none">Rp 0</div>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="tutupModal()" class="w-1/3 bg-[#efefef] text-[#1A1A1A] font-[900] rounded-full py-4 hover:bg-[#e2e2e2] transition text-[14px]">BATAL</button>
                    <button type="submit" id="btn-proses-lunas" class="w-2/3 bg-[#03913F] text-white font-[900] rounded-full py-4 hover:opacity-80 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg text-[14px]">KONFIRMASI</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔥 MODAL BUKTI QRIS 🔥 -->
    <div id="modalBukti" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <!-- 🔥 KODE BARU: Ditambahkan max-h-[95vh] dan overflow-y-auto agar bisa di-scroll jika layar kecil 🔥 -->
        <div class="bg-white w-full max-w-[420px] max-h-[95vh] overflow-y-auto scrollbar-none rounded-[24px] p-6 shadow-2xl text-center relative flex flex-col">

            <button onclick="tutupModalBukti()" class="absolute top-4 right-5 text-[28px] text-[#8A8A8E] hover:text-black font-bold leading-none">&times;</button>
            <h3 class="text-[20px] font-[900] text-[#1A1A1A] mb-1 mt-2">Cek Bukti Transfer</h3>
            <p id="bukti-info-meja" class="text-[13px] text-[#8A8A8E] font-[800] mb-3 tracking-wide"></p>

            <div class="bg-[#FDE7E7] text-[#DC0F11] font-[900] py-2 rounded-[12px] mb-4 text-[14px] shrink-0">
                Tagihan: <span id="bukti-tagihan">Rp 0</span>
            </div>

            <!-- 🔥 KODE BARU: Gambar diperbesar (h-[300px]) & bisa diklik untuk buka full-screen 🔥 -->
            <div class="bg-[#F3F3F3] p-2 rounded-[16px] mb-4 flex justify-center items-center border border-black/5 h-[300px] overflow-hidden relative group shrink-0">
                <a id="link-bukti-full" href="#" target="_blank" class="w-full h-full flex justify-center items-center">
                    <img id="bukti-image" src="" alt="Bukti Transfer" class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </a>
                <div class="absolute bottom-2 bg-black/60 text-white text-[10px] font-bold px-3 py-1 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none">
                    Klik untuk perbesar foto
                </div>
            </div>

            <form action="" id="form-verifikasi-bukti" method="POST" class="w-full mt-auto flex flex-col gap-3">
                @csrf
                <input type="hidden" name="metode_pembayaran" value="QRIS">
                <input type="hidden" id="bukti-tagihan-raw" name="total_tagihan">

                <div class="text-left mb-1 shrink-0">
                    <label class="block text-[11px] font-[900] text-[#1A1A1A] mb-1.5 uppercase tracking-wider">Nominal di Foto Bukti?</label>
                    <input type="number" id="input-nominal-qris" name="uang_bayar" required class="w-full px-4 py-3 rounded-[12px] bg-white border-2 border-[#1A1A1A] focus:border-[#DC0F11] outline-none font-[900] text-[18px] text-[#1A1A1A] transition" oninput="hitungKembalianQRIS()">
                </div>

                <div class="bg-[#F3F3F3] p-3 rounded-[12px] text-center border border-black/5 mb-2 shrink-0">
                    <span class="block text-[10px] text-[#8A8A8E] font-[900] mb-1 uppercase tracking-wider">Status Pembayaran</span>
                    <div id="qris-status-display" class="text-[18px] font-[900] text-[#03913F] leading-none">PAS</div>
                </div>

                <div class="flex gap-2 shrink-0">
                    <button type="submit" id="btn-kurang-bayar" formaction="" class="w-1/2 bg-[#FDE7E7] hover:bg-[#DC0F11] hover:text-white text-[#DC0F11] font-[900] rounded-[12px] py-3.5 transition shadow-sm text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                        NOMINAL KURANG
                    </button>
                    <button type="submit" id="btn-lunas-qris" class="w-1/2 bg-[#03913F] hover:bg-green-700 text-white font-[900] rounded-[12px] py-3.5 transition shadow-lg text-[12px] disabled:opacity-50 disabled:cursor-not-allowed">
                        VERIFIKASI LUNAS
                    </button>
                </div>

                <button type="submit" id="btn-tolak-bukti" formaction="" formnovalidate class="w-full shrink-0 bg-white border-2 border-[#1A1A1A] text-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-white font-[900] rounded-[12px] py-3 transition shadow-sm text-[12px]">
                    TOLAK BUKTI (GAMBAR SALAH)
                </button>
            </form>
        </div>
    </div>

    <script>
        function cariPesananQR() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let cards = document.querySelectorAll(".data-order");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyDatabaseRow = document.getElementById("emptyDatabaseRow");
            let matchCount = 0;

            if(emptyDatabaseRow) return;

            cards.forEach(card => {
                let meja = card.querySelector(".data-meja").textContent.toLowerCase();
                let nama = card.querySelector(".data-nama").textContent.toLowerCase();


                let semuaTeksKartu = card.textContent.toLowerCase();

                // Cari berdasarkan no meja ATAU nama ATAU isi teks kartu (termasuk metode bayar)
                if (meja.includes(input) || nama.includes(input) || semuaTeksKartu.includes(input)) {
                    card.style.display = "";
                    matchCount++;
                } else {
                    card.style.display = "none";
                }
            });

            if(matchCount === 0 && cards.length > 0) {
                notFoundRow.classList.remove('hidden');
            } else {
                notFoundRow.classList.add('hidden');
            }
        }

        function bukaModalBayar(id, meja, tagihanYgHarusDibayar) {
            document.getElementById('form-lunas-tunai').action = "/kasir/lunas/" + id;
            document.getElementById('modal-info-meja').innerText = "Meja Pelanggan #" + meja;
            document.getElementById('total-tagihan-display').value = "Rp " + parseInt(tagihanYgHarusDibayar).toLocaleString('id-ID');
            document.getElementById('total-tagihan-raw').value = tagihanYgHarusDibayar;
            document.getElementById('uang-bayar').value = "";
            document.getElementById('kembalian-display').innerText = "Rp 0";
            document.getElementById('btn-proses-lunas').disabled = true;

            let modal = document.getElementById('modalBayar');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => document.getElementById('uang-bayar').focus(), 100);
        }

        function tutupModal() {
            document.getElementById('modalBayar').classList.remove('flex');
            document.getElementById('modalBayar').classList.add('hidden');
        }

        function hitungKembalian() {
            let total = parseInt(document.getElementById('total-tagihan-raw').value);
            let bayar = parseInt(document.getElementById('uang-bayar').value) || 0;
            let kembalian = bayar - total;

            let btnProses = document.getElementById('btn-proses-lunas');
            let display = document.getElementById('kembalian-display');

            if (kembalian < 0) {
                display.innerText = "Kurang Rp " + Math.abs(kembalian).toLocaleString('id-ID');
                display.style.color = "#DC0F11"; btnProses.disabled = true;
            } else {
                display.innerText = "Rp " + kembalian.toLocaleString('id-ID');
                display.style.color = "#03913F"; btnProses.disabled = false;
            }
        }

        function bukaModalBukti(imageUrl, id, meja, totalHarga) {
            document.getElementById('form-verifikasi-bukti').action = "/kasir/lunas/" + id;
            document.getElementById('btn-kurang-bayar').formAction = "/kasir/kurang-bayar/" + id;
            document.getElementById('btn-tolak-bukti').formAction = "/kasir/tolak-bukti/" + id;

            document.getElementById('bukti-info-meja').innerText = "Meja Pelanggan #" + meja;
            document.getElementById('bukti-image').src = imageUrl;

            document.getElementById('bukti-tagihan').innerText = "Rp " + parseInt(totalHarga).toLocaleString('id-ID');
            document.getElementById('bukti-tagihan-raw').value = totalHarga;

            let inputNominal = document.getElementById('input-nominal-qris');
            let statusDisplay = document.getElementById('qris-status-display');
            let btnLunas = document.getElementById('btn-lunas-qris');
            let btnKurang = document.getElementById('btn-kurang-bayar');

            inputNominal.value = "";
            statusDisplay.innerText = " AI Sedang Membaca Struk... ";
            statusDisplay.style.color = "#8A8A8E";
            btnLunas.disabled = true; btnKurang.disabled = true;
            document.getElementById('link-bukti-full').href = imageUrl;

            let modal = document.getElementById('modalBukti');
            modal.classList.remove('hidden'); modal.classList.add('flex');

            Tesseract.recognize(imageUrl, 'ind', { logger: m => console.log(m) })
            .then(({ data: { text } }) => {

                let nominalDitemukan = "";
                let match = text.match(/Rp\s*([\d\.\,]+)/i);

                if (match) {
                    let angkaBersih = parseInt(match[1].replace(/[\.\,]/g, ''));
                    if (!isNaN(angkaBersih) && angkaBersih > 0) {
                        nominalDitemukan = angkaBersih;
                    }
                }

                inputNominal.value = nominalDitemukan;
                hitungKembalianQRIS(); // Panggil fungsi hitung untuk cek statusnya
            }).catch(err => {
                // Kalau error, biarkan kosong
                inputNominal.value = "";
                hitungKembalianQRIS();
            });
        }

        function tutupModalBukti() {
            document.getElementById('modalBukti').classList.remove('flex');
            document.getElementById('modalBukti').classList.add('hidden');
        }

        function hitungKembalianQRIS() {
            let total = parseInt(document.getElementById('bukti-tagihan-raw').value);
            let inputElement = document.getElementById('input-nominal-qris');

            let btnLunas = document.getElementById('btn-lunas-qris');
            let btnKurang = document.getElementById('btn-kurang-bayar');
            let display = document.getElementById('qris-status-display');


            if (inputElement.value === "") {
                display.innerText = "Nominal Tidak Terbaca! tolak bukti pembayaran.";
                display.style.color = "#DC0F11"; // Merah
                btnLunas.disabled = true;  // Kunci tombol Lunas
                btnKurang.disabled = true; // Kunci tombol Kurang
                return; // Stop perhitungan di sini
            }

            // Hitungan normal kalau ada angkanya
            let bayar = parseInt(inputElement.value) || 0;
            let selisih = bayar - total;

            if (selisih < 0) {
                display.innerText = "Kurang Rp " + Math.abs(selisih).toLocaleString('id-ID');
                display.style.color = "#DC0F11"; btnLunas.disabled = true; btnKurang.disabled = false;
            } else if (selisih > 0) {
                display.innerText = "Kembalian Tunai Rp " + selisih.toLocaleString('id-ID');
                display.style.color = "#03913F"; btnLunas.disabled = false; btnKurang.disabled = true;
            } else {
                display.innerText = "PAS (Sesuai Tagihan)";
                display.style.color = "#03913F"; btnLunas.disabled = false; btnKurang.disabled = true;
            }
        }
    </script>

</body>
</html>
