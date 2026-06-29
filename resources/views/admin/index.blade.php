<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Prasmanan - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

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
            <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-3 bg-black text-white rounded-full font-[800] text-[13px] transition shadow-md">
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

    <main class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] scrollbar-none relative">

        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] text-[#1A1A1A] m-0 tracking-tight">Kasir Prasmanan</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariPesanan()" placeholder="Cari nama atau meja..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[240px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <div class="bg-[#FDE7E7] text-[#DC0F11] px-4 py-1.5 rounded-full font-[900] text-[11px] border border-[#DC0F11]/20 tracking-widest shadow-sm shrink-0">
                    🔴 LIVE UPDATE
                </div>
            </div>
        </header>

        <div class="p-8 flex flex-col gap-8 relative">

            <div class="w-full bg-white rounded-[20px] p-8 shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-black/5">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                    <div class="lg:col-span-5 flex flex-col">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-[900] text-[18px]">Hitung Nominal</h3>
                            <button type="button" onclick="resetCalc()" class="text-[#8A8A8E] font-[800] text-[13px] hover:text-[#DC0F11] transition">Reset ↺</button>
                        </div>

                        <div class="bg-[#F3F3F3] border border-transparent focus-within:border-[#DC0F11]/30 focus-within:bg-white rounded-[16px] p-6 text-center mb-6 flex-1 flex flex-col justify-center transition">
                            <span class="block text-[11px] text-[#8A8A8E] font-[900] mb-2 tracking-wider">TOTAL SEMENTARA</span>
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-[32px] font-[900] text-[#DC0F11]">Rp</span>
                                <input type="number" id="input-manual-harga" class="w-full max-w-[160px] bg-transparent text-[48px] font-[900] text-[#DC0F11] text-center outline-none p-0 m-0 leading-none tracking-tight placeholder-[#DC0F11]/30" value="0" placeholder="0" oninput="updateDariManual()">
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-2 mt-auto">
                            <button type="button" class="bg-white border-2 border-black/5 py-3 rounded-[12px] font-[900] text-[14px] text-[#5e5e5e] hover:bg-[#1A1A1A] hover:text-white hover:border-[#1A1A1A] transition shadow-sm" onclick="addNominal(500)">+ 500</button>
                            <button type="button" class="bg-white border-2 border-black/5 py-3 rounded-[12px] font-[900] text-[14px] text-[#5e5e5e] hover:bg-[#1A1A1A] hover:text-white hover:border-[#1A1A1A] transition shadow-sm" onclick="addNominal(1000)">+ 1k</button>
                            <button type="button" class="bg-white border-2 border-black/5 py-3 rounded-[12px] font-[900] text-[14px] text-[#5e5e5e] hover:bg-[#1A1A1A] hover:text-white hover:border-[#1A1A1A] transition shadow-sm" onclick="addNominal(2000)">+ 2k</button>
                            <button type="button" class="bg-[#FDE7E7] text-[#DC0F11] border-2 border-[#DC0F11]/20 py-3 rounded-[12px] font-[900] text-[14px] hover:bg-[#DC0F11] hover:text-white transition shadow-sm" onclick="addNominal(5000)">+ 5k</button>
                        </div>
                    </div>

                    <div class="lg:col-span-7 border-l border-black/5 pl-10 flex flex-col justify-center">

                        @if($errors->any())
                            <div class="mb-5 bg-[#FDE7E7] text-[#DC0F11] p-4 rounded-[12px] font-[800] text-[13px] border border-[#DC0F11]/20 shadow-sm">
                                <ul class="list-disc pl-4">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('kasir.merge') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="total_prasmanan" id="input-harga" value="0">

                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div>
                                    <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Nomor Meja Pelanggan</label>
                                    <input type="number" name="no_meja" value="{{ old('no_meja') }}" class="w-full px-4 py-3.5 rounded-[12px] bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#DC0F11] focus:ring-4 focus:ring-[#DC0F11]/10 outline-none text-[15px] font-[900] text-black transition" required placeholder="Contoh: 5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" class="w-full px-4 py-3.5 rounded-[12px] bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#DC0F11] focus:ring-4 focus:ring-[#DC0F11]/10 outline-none text-[15px] font-[900] text-black transition cursor-pointer">
                                        <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS Statis</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wide">Tambah Minuman (Opsional)</label>
                                <div class="flex gap-4">
                                    <select name="minuman_id" class="flex-1 px-4 py-3 rounded-[12px] bg-[#F3F3F3] outline-none text-[14px] font-[800] text-black transition cursor-pointer">
                                    <option value="">-- Pilih Minuman --</option>
                                    @foreach($menus as $menu)
                                        @if($menu->category_id == 6)
                                            <option value="{{ $menu->id }}" {{ old('minuman_id') == $menu->id ? 'selected' : '' }}>{{ $menu->nama_menu }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                    <input type="number" name="qty_minuman" value="{{ old('qty_minuman') }}" class="w-[120px] px-4 py-3 rounded-[12px] bg-[#F3F3F3] outline-none text-[14px] font-[800] text-black transition" placeholder="Qty">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#1A1A1A] hover:bg-black text-white font-[900] py-4 rounded-full transition shadow-lg text-[15px] tracking-wide">
                                Proses Pesanan
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <div class="w-full mt-4">

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
                                        <span class="text-[10px] text-[#DC0F11] font-[900] tracking-wider uppercase">Meja Pelanggan</span><br>
                                        <div class="flex items-baseline gap-3 mt-1">
                                            <b class="text-[28px] font-[900] text-[#1A1A1A] leading-none data-meja">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</b>
                                            <span class="text-[14px] font-[700] text-[#5e5e5e] data-nama">{{ $order->nama_pelanggan }}</span>
                                        </div>
                                        <div class="mt-2 text-[12px] font-[800] text-[#8A8A8E]">
                                            Waktu: <span class="text-[#1A1A1A]">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} WIB</span> |
                                            Antrean: <span class="text-[#1A1A1A]">#{{ $order->id }}</span>
                                        </div>
                                    </div>
                                    <div class="inline-block px-3 py-1.5 rounded-[8px] text-[11px] font-[900] tracking-wide {{ $order->status_pembayaran == 'Lunas' ? 'bg-[#03913F]/10 text-[#03913F]' : 'bg-[#FDE7E7] text-[#DC0F11]' }}">
                                        {{ strtoupper($order->status_pembayaran) }}
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
                                        </div>
                                        <div class="text-[15px] font-[900] text-[#1A1A1A] mt-0.5">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-6 lg:w-[320px] bg-[#fafafa] flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-black/5">
                                <span class="block text-[12px] font-[900] text-[#8A8A8E] mb-1 uppercase tracking-wider">Total Tagihan</span>
                                <div class="text-[32px] font-[900] text-[#1A1A1A] mb-6 leading-none">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>

                                @if($order->status_pembayaran == 'Belum Bayar')

                                    @if($order->metode_pembayaran == 'QRIS')
                                        <div class="text-center p-3 mb-3 rounded-[12px] bg-[#e6f4ea] border border-[#03913F]/20">
                                            <span class="text-[11px] font-[900] text-[#03913F] tracking-wider uppercase">Pelanggan Bayar via QRIS</span>
                                        </div>
                                        <button type="button" class="w-full bg-[#03913F] text-white rounded-[12px] py-3 text-[13px] font-[900] hover:bg-green-700 transition shadow-md tracking-wide"
                                            onclick="bukaModalVerifikasiQRIS('{{ $order->id }}', '{{ $order->no_meja }}', '{{ $order->total_harga }}')">
                                            QRIS
                                        </button>
                                    @else
                                        <button type="button" class="w-full bg-black text-white rounded-[12px] py-3 text-[13px] font-[900] hover:opacity-80 transition shadow-md tracking-wide flex items-center justify-center gap-2"
                                            onclick="bukaModalBayar('{{ $order->id }}', '{{ $order->no_meja }}', '{{ $order->total_harga }}')">
                                            BAYAR TUNAI
                                        </button>
                                    @endif

                                <!-- 🔥 KODE PERBAIKAN: MURNI VERIFIKASI VISUAL SISA TRANSFER 🔥 -->
                                @elseif($order->status_pembayaran == 'Kurang Bayar')
                                    @php
                                        $kekurangan = $order->total_harga - ($order->uang_bayar ?? 0);
                                    @endphp
                                    <div class="text-center p-3 mb-3 rounded-[12px] bg-[#FDE7E7] border border-[#DC0F11]/20">
                                        <span class="block text-[11px] font-[900] text-[#DC0F11] tracking-wider uppercase mb-1">Kekurangan Bayar</span>
                                        <span class="text-[18px] font-[900] text-[#DC0F11]">Rp {{ number_format($kekurangan, 0, ',', '.') }}</span>
                                    </div>

                                    <button type="button" class="w-full bg-[#03913F] hover:bg-green-700 text-white rounded-[12px] py-3 text-[13px] font-[900] transition shadow-lg tracking-wide border border-transparent flex items-center justify-center gap-2 animate-pulse"
                                        onclick="bukaModalVerifikasiQRIS('{{ $order->id }}', '{{ $order->no_meja }}', '{{ $kekurangan }}')">
                                        VERIFIKASI TRANSFER SISA
                                    </button>

                                @else
                                    @php
                                        $kembalian = ($order->uang_bayar ?? $order->total_harga) - $order->total_harga;
                                    @endphp

                                    @if($kembalian > 0 && $order->metode_pembayaran == 'QRIS')
                                        <div class="text-center p-3 mb-3 rounded-[12px] bg-[#e6f4ea] border border-[#03913F]/20">
                                            <span class="block text-[11px] font-[900] text-[#03913F] tracking-wider uppercase mb-1">Kembalian QRIS</span>
                                            <span class="text-[18px] font-[900] text-[#03913F]">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                    <button type="button" class="w-full bg-white border-2 border-[#1A1A1A] text-[#1A1A1A] rounded-[12px] py-3 text-[13px] font-[900] hover:bg-[#1A1A1A] hover:text-white transition shadow-sm tracking-wide mb-3 flex items-center justify-center gap-2"
                                        onclick="bukaModalQRStruk('{{ url('/kasir/struk/' . $order->id) }}', '{{ $order->no_meja }}')">
                                        E-STRUK QR
                                    </button>

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
                        <div id="emptyDatabaseRow" class="col-span-full flex flex-col items-center justify-center py-20 text-[#8A8A8E] border-2 border-dashed border-black/10 rounded-[20px] bg-white">
                            <div class="text-[48px] mb-3 opacity-50">🍽️</div>
                            <div class="font-[900] text-[18px] text-[#1A1A1A] mb-1">Belum Ada Antrian Meja</div>
                            <div class="font-[700] text-[14px]">Tambahkan pesanan di atas untuk memulai.</div>
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
                    <label class="block text-[11px] font-[900] text-[#8A8A8E] mb-1.5 uppercase tracking-wider">Total Tagihan</label>
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

    <!-- 🔥 MODAL VERIFIKASI QRIS SUPER CEPAT OPSI KLIK (PINTAR & INSTAN) 🔥 -->
    <div id="modalVerifikasiQRIS" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[420px] rounded-[24px] p-6 shadow-2xl text-center relative flex flex-col">
            <button onclick="tutupModalVerifikasiQRIS()" class="absolute top-4 right-5 text-[28px] text-[#8A8A8E] hover:text-black font-bold leading-none">&times;</button>
            <h3 class="text-[20px] font-[900] text-[#1A1A1A] mb-1">Verifikasi Transfer QRIS</h3>
            <p id="qris-info-meja" class="text-[13px] text-[#8A8A8E] font-[800] mb-4 tracking-wide"></p>

            <div class="bg-[#F3F3F3] text-[#1A1A1A] font-[900] py-3 rounded-[12px] mb-4 text-[16px] border border-black/5">
                Tagihan: <span id="qris-tagihan" class="text-[#DC0F11]">Rp 0</span>
            </div>

            <!-- FORM UTAMA -->
            <form action="" id="form-verifikasi-qris" method="POST" class="w-full flex flex-col gap-3">
                @csrf
                <input type="hidden" name="metode_pembayaran" value="QRIS">
                <input type="hidden" id="qris-tagihan-raw" name="total_tagihan">

                <!-- Input Tersembunyi Penyimpan ID Order -->
                <input type="hidden" id="qris-order-id" value="">
                <!-- Input Uang Bayar Real yang Tersembunyi (Dikirim ke Controller) -->
                <input type="hidden" id="qris-real-uang-bayar" name="uang_bayar">

                <!-- KOTAK PILIHAN KONDISI (SUPER CEPAT) -->
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button type="button" onclick="pilihKondisiQRIS('pas')" id="btn-kondisi-pas" class="py-3 bg-[#e6f4ea] text-[#03913F] font-[900] rounded-[12px] border-2 border-transparent hover:border-[#03913F] transition text-[13px]">
                        UANG PAS
                    </button>
                    <button type="button" onclick="pilihKondisiQRIS('kurang')" id="btn-kondisi-kurang" class="py-3 bg-[#FDE7E7] text-[#DC0F11] font-[900] rounded-[12px] border-2 border-transparent hover:border-[#DC0F11] transition text-[13px]">
                        KURANG
                    </button>
                    <button type="button" onclick="pilihKondisiQRIS('lebih')" id="btn-kondisi-lebih" class="py-3 bg-blue-50 text-blue-600 font-[900] rounded-[12px] border-2 border-transparent hover:border-blue-600 transition text-[13px]">
                        LEBIH
                    </button>
                </div>

                <!-- FORM INPUT DINAMIS (Muncul hanya jika Kurang / Lebih) -->
                <div id="wrapper-input-qris-dinamis" class="hidden text-left bg-[#FAF7F4] p-4 rounded-[16px] border border-black/5 transition animate-fade-in">
                    <label id="label-input-dinamis" class="block text-[11px] font-[900] text-[#1A1A1A] mb-1.5 uppercase tracking-wider">Masukkan Nominal</label>
                    <input type="number" id="input-nominal-qris-dinamis" class="w-full px-4 py-2.5 rounded-[12px] bg-white border-2 border-[#1A1A1A] outline-none font-[900] text-[18px] text-[#1A1A1A]" oninput="kalkulasiQRISDinamis()">

                    <!-- Info Tambahan Kasir -->
                    <div id="info-kalkulasi-dinamis" class="text-[12px] font-[800] mt-2"></div>
                </div>

                <!-- TOMBOL SUBMIT KASIR -->
                <div class="flex gap-2 mt-2">
                    <button type="button" onclick="tutupModalVerifikasiQRIS()" class="w-1/3 bg-[#efefef] text-[#1A1A1A] font-[900] rounded-[12px] py-3.5 transition text-[13px]">
                        BATAL
                    </button>
                    <button type="submit" id="btn-submit-qris-prasmanan" class="w-2/3 bg-black text-white font-[900] rounded-[12px] py-3.5 transition text-[13px] disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                        KONFIRMASI
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal E-Struk QR -->
    <div id="modalQRStruk" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[360px] rounded-[24px] p-8 shadow-2xl text-center relative">
            <button onclick="tutupModalQRStruk()" class="absolute top-4 right-5 text-[28px] text-[#8A8A8E] hover:text-black font-bold">&times;</button>
            <h3 class="text-[20px] font-[900] text-[#1A1A1A] mb-1">E-Struk Pelanggan</h3>
            <p id="struk-info-meja" class="text-[13px] text-[#8A8A8E] font-[800] mb-6 tracking-wide"></p>

            <div class="bg-[#F3F3F3] p-4 rounded-[20px] mb-6 flex justify-center items-center border border-black/5">
                <img id="qris-struk-image" src="" alt="QR Struk" class="w-[220px] h-[220px] object-contain mix-blend-multiply">
            </div>

            <p class="text-[13px] font-[800] text-[#8A8A8E] leading-relaxed mb-4">
                Silakan minta pelanggan <b>Scan QR Code</b> ini menggunakan kamera HP untuk mengunduh struk pesanan mereka.
            </p>
        </div>
    </div>

    <script>

        function cariPesanan() {
            // 1. Ambil teks ketikan kasir, lalu ubah jadi huruf kecil semua
            let input = document.getElementById("searchInput").value.toLowerCase();

            // 2. Kumpulkan seluruh kartu pesanan yang aktif di layar
            let cards = document.querySelectorAll(".data-order");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyDatabaseRow = document.getElementById("emptyDatabaseRow");
            let matchCount = 0; // Penghitung jumlah kartu yang cocok

            // 3. Pengaman: Jika database memang kosong melompong, hentikan fungsi
            if (emptyDatabaseRow) return;

            // 4. Lakukan pemindaian ke setiap kartu pesanan satu per satu
            cards.forEach(card => {
                let meja = card.querySelector(".data-meja").textContent.toLowerCase();
                let nama = card.querySelector(".data-nama").textContent.toLowerCase();


                let semuaTeksDalamKartu = card.textContent.toLowerCase();


                if (meja.includes(input) || nama.includes(input) || semuaTeksDalamKartu.includes(input)) {
                    card.style.display = ""; // Munculkan kartu di layar
                    matchCount++; // Tambah angka kecocokan
                } else {
                    card.style.display = "none"; // Sembunyikan kartu jika tidak cocok
                }
            });

            // 5. Jika setelah di-scan ternyata tidak ada kartu yang cocok sama sekali
            if (matchCount === 0 && cards.length > 0) {
                notFoundRow.classList.remove('hidden'); // Tampilkan gambar "Pesanan Tidak Ditemukan"
            } else {
                notFoundRow.classList.add('hidden'); // Sembunyikan gambar tersebut
            }
        }

        let totalBelanja = 0;
        function addNominal(nominal) { totalBelanja += nominal; updateDisplay(); }
        function updateDariManual() {
            let manualValue = parseInt(document.getElementById('input-manual-harga').value) || 0;
            totalBelanja = manualValue;
            document.getElementById('input-harga').value = totalBelanja;
        }
        function resetCalc() { totalBelanja = 0; updateDisplay(); }
        function updateDisplay() {
            document.getElementById('input-manual-harga').value = totalBelanja;
            document.getElementById('input-harga').value = totalBelanja;
        }

        // Modal Tunai
        function bukaModalBayar(id, meja, total) {
            document.getElementById('form-lunas-tunai').action = "/kasir/lunas/" + id;
            document.getElementById('modal-info-meja').innerText = "Meja Pelanggan #" + meja;
            document.getElementById('total-tagihan-display').value = "Rp " + parseInt(total).toLocaleString('id-ID');
            document.getElementById('total-tagihan-raw').value = total;
            document.getElementById('uang-bayar').value = "";
            document.getElementById('kembalian-display').innerText = "Rp 0";
            document.getElementById('kembalian-display').style.color = "#1A1A1A";
            document.getElementById('btn-proses-lunas').disabled = true;

            let modal = document.getElementById('modalBayar');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => document.getElementById('uang-bayar').focus(), 100);
        }
        function tutupModal() {
            let modal = document.getElementById('modalBayar');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
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

        // Variable global untuk simpan mode pilihan QRIS
        let currentQrisMode = "";

        // Fungsi Membuka Modal QRIS Prasmanan
        function bukaModalVerifikasiQRIS(id, meja, totalHarga) {
            document.getElementById('qris-info-meja').innerText = "Verifikasi Meja #" + meja;
            document.getElementById('qris-tagihan').innerText = "Rp " + parseInt(totalHarga).toLocaleString('id-ID');
            document.getElementById('qris-tagihan-raw').value = totalHarga;

            // Simpan ID pesanan dengan aman
            document.getElementById('qris-order-id').value = id;

            // Reset state tombol & input
            currentQrisMode = "";
            document.getElementById('wrapper-input-qris-dinamis').classList.add('hidden');
            document.getElementById('btn-submit-qris-prasmanan').disabled = true;

            // Reset gaya border tombol pilihan
            ['pas', 'kurang', 'lebih'].forEach(mode => {
                document.getElementById(`btn-kondisi-${mode}`).style.border = "2px solid transparent";
            });

            let modal = document.getElementById('modalVerifikasiQRIS');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalVerifikasiQRIS() {
            let modal = document.getElementById('modalVerifikasiQRIS');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // FUNGSI LOGIKA SMART BUTTON KLIK
        function pilihKondisiQRIS(kondisi) {
            currentQrisMode = kondisi;
            let totalTagihan = parseInt(document.getElementById('qris-tagihan-raw').value);
            let orderId = document.getElementById('qris-order-id').value;
            let form = document.getElementById('form-verifikasi-qris');

            let wrapperInput = document.getElementById('wrapper-input-qris-dinamis');
            let labelInput = document.getElementById('label-input-dinamis');
            let inputDinamis = document.getElementById('input-nominal-qris-dinamis');
            let btnSubmit = document.getElementById('btn-submit-qris-prasmanan');

            // Beri highlight border pada tombol yang aktif diklik
            ['pas', 'kurang', 'lebih'].forEach(mode => {
                document.getElementById(`btn-kondisi-${mode}`).style.borderColor = (mode === kondisi) ? 'inherit' : 'transparent';
            });

            inputDinamis.value = "";
            document.getElementById('info-kalkulasi-dinamis').innerText = "";

            if (kondisi === 'pas') {
                wrapperInput.classList.add('hidden');
                document.getElementById('qris-real-uang-bayar').value = totalTagihan;

                // Set action lunas dengan Order ID yang benar
                form.action = "/kasir/lunas/" + orderId;
                btnSubmit.disabled = false;
            }
            else if (kondisi === 'kurang') {
                wrapperInput.classList.remove('hidden');
                labelInput.innerText = "Berapa Nominal yang Ditransfer Pelanggan?";
                btnSubmit.disabled = true;
                setTimeout(() => inputDinamis.focus(), 100);
            }
            else if (kondisi === 'lebih') {
                wrapperInput.classList.remove('hidden');
                labelInput.innerText = "Berapa Nominal yang Ditransfer Pelanggan?";
                btnSubmit.disabled = true;
                setTimeout(() => inputDinamis.focus(), 100);
            }
        }

        // Fungsi Menghitung Angka Saat Kasir Mengetik Dinamis (Kurang/Lebih)
        function kalkulasiQRISDinamis() {
            let total = parseInt(document.getElementById('qris-tagihan-raw').value);
            let inputVal = parseInt(document.getElementById('input-nominal-qris-dinamis').value) || 0;
            let btnSubmit = document.getElementById('btn-submit-qris-prasmanan');
            let infoKalkulasi = document.getElementById('info-kalkulasi-dinamis');
            let form = document.getElementById('form-verifikasi-qris');

            // Ambil ID pesanan dengan aman
            let orderId = document.getElementById('qris-order-id').value;

            // Pasang angka ketikan ke form input tersembunyi
            document.getElementById('qris-real-uang-bayar').value = inputVal;

            if (currentQrisMode === 'kurang') {
                if (inputVal >= total) {
                    infoKalkulasi.innerText = "⚠️ Nominal salah! Angka harus kurang dari total tagihan.";
                    infoKalkulasi.style.color = "#DC0F11";
                    btnSubmit.disabled = true;
                } else if (inputVal <= 0) {
                    btnSubmit.disabled = true;
                    infoKalkulasi.innerText = "";
                } else {
                    let sisaKurang = total - inputVal;
                    infoKalkulasi.innerText = "Kekurangan: Rp " + sisaKurang.toLocaleString('id-ID');
                    infoKalkulasi.style.color = "#1A1A1A";
                    form.action = "/kasir/kurang-bayar/" + orderId;
                    btnSubmit.disabled = false;
                }
            }
            else if (currentQrisMode === 'lebih') {
                if (inputVal <= total) {
                    infoKalkulasi.innerText = "⚠️ Nominal salah! Angka harus lebih besar dari total tagihan.";
                    infoKalkulasi.style.color = "#DC0F11";
                    btnSubmit.disabled = true;
                } else {
                    let sisaLebih = inputVal - total;
                    infoKalkulasi.innerText = "Kembalian Cash: Rp " + sisaLebih.toLocaleString('id-ID') + " (Ambilkan dari laci kasir)";
                    infoKalkulasi.style.color = "#03913F";
                    form.action = "/kasir/lunas/" + orderId;
                    btnSubmit.disabled = false;
                }
            }
        }

        // Modal E-Struk
        function bukaModalQRStruk(urlStruk, meja) {
            document.getElementById('struk-info-meja').innerText = "Meja Pelanggan #" + meja;
            document.getElementById('qris-struk-image').src = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(urlStruk);
            let modal = document.getElementById('modalQRStruk');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function tutupModalQRStruk() {
            let modal = document.getElementById('modalQRStruk');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
