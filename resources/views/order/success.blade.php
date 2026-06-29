<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan #{{ $order->id }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; } </style>

    @if(in_array($order->status_pembayaran, ['Belum Bayar', 'Kurang Bayar']))
        <script>
            // 1. Simpan timer di dalam variabel 'autoRefresh'
            let autoRefresh = setTimeout(function(){
                location.reload();
            }, 10000);

            // 2. Fungsi ini akan dipanggil saat pelanggan milih foto
            function hentikanRefresh() {
                clearTimeout(autoRefresh);
                console.log("Auto-refresh dimatikan karena pelanggan sedang upload foto.");
            }
        </script>
    @endif
</head>
<body class="min-h-screen flex items-center justify-center p-5 flex-col">

    <div class="w-full max-w-[420px] mb-4">
        @if(session('success'))
            <div class="bg-[#e6f4ea] border border-[#03913F] text-[#03913F] px-4 py-3 rounded-[12px] font-[800] text-[13px] text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-[#FDE7E7] border border-[#DC0F11] text-[#DC0F11] px-4 py-3 rounded-[12px] font-[800] text-[13px] shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="bg-white w-full max-w-[420px] rounded-[16px] shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-black/5 p-6 md:p-8">

        @if($order->status_pembayaran == 'Lunas')
            <div class="w-[64px] h-[64px] rounded-full bg-black text-white flex items-center justify-center text-[32px] mx-auto mb-4 shadow-sm">✓</div>
            <h1 class="text-[24px] font-[900] text-center tracking-tight leading-none mb-4">Pembayaran Berhasil!</h1>

            @if($order->metode_pembayaran == 'QRIS' && $order->uang_bayar > $order->total_harga)
                <div class="bg-[#e6f4ea] border border-[#03913F]/30 p-3 rounded-[12px] mb-6 text-center">
                    <span class="block text-[11px] font-[900] text-[#03913F] uppercase tracking-wider mb-1">Ada Kembalian Anda</span>
                    <span class="text-[18px] font-[900] text-[#03913F]">Rp {{ number_format($order->uang_bayar - $order->total_harga, 0, ',', '.') }}</span>
                    <p class="text-[11px] font-[700] text-[#03913F]/80 mt-1.5 leading-snug">Uang kembalian transfer akan diantarkan ke meja Anda secara Tunai beserta pesanan.</p>
                </div>
            @else
                <p class="text-[13px] font-[600] text-[#8A8A8E] text-center mb-6 leading-snug">Pesananmu sedang disiapkan<br>Mohon tunggu sebentar</p>
            @endif

        <!-- 🔥 PERBAIKAN: BLOK FITUR RE-UPLOAD SISA KEKURANGAN QRIS 🔥 -->
        @elseif($order->status_pembayaran == 'Kurang Bayar')
            <div class="w-[64px] h-[64px] rounded-full bg-[#FDE7E7] text-[#DC0F11] flex items-center justify-center text-[32px] mx-auto mb-4 border border-[#DC0F11]/20">⚠️</div>
            <h1 class="text-[24px] font-[900] text-[#DC0F11] text-center tracking-tight leading-none mb-4">Kurang Bayar!</h1>

            @php
                // Hitung selisih kekurangan secara dinamis
                $sisaKekurangan = $order->total_harga - ($order->uang_bayar ?? 0);
            @endphp

            <div class="bg-[#FDE7E7] border border-[#DC0F11]/30 p-4 rounded-[16px] mb-6 text-center shadow-sm">
                <span class="block text-[11px] font-[900] text-[#DC0F11] uppercase tracking-wider mb-1">Sisa yang harus ditransfer</span>
                <span class="text-[22px] font-[900] text-[#DC0F11]">Rp {{ number_format($sisaKekurangan, 0, ',', '.') }}</span>
                <p class="text-[12px] font-[700] text-[#DC0F11]/80 mt-1.5 leading-snug">
                    Nominal transfer sebelumnya belum pas. Silakan scan QRIS di bawah untuk membayar <b>kekurangannya saja</b>, lalu unggah kembali buktinya.
                </p>
            </div>

            <!-- Jika field foto kosong (Kasir baru saja menekan tombol Kurang Bayar) -->
            @if(!$order->bukti_bayar)
                <div class="text-center mb-6">
                    <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS" class="mx-auto w-48 h-48 border-[3px] border-[#DC0F11] rounded-[16px] shadow-sm mb-2 object-cover">
                </div>
                <form action="{{ route('pesanan.upload_bukti', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 bg-[#F3F3F3] p-4 rounded-[12px]">
                    @csrf
                    <div>
                        <label class="block text-[13px] font-[900] text-[#1A1A1A] mb-2">Upload Bukti Transfer Kekurangan</label>
                        <input type="file" name="bukti_bayar" accept="image/*" required class="w-full text-[12px] bg-white border border-black/10 rounded-[8px] p-1" onchange="hentikanRefresh()">
                    </div>
                    <button type="submit" class="w-full bg-[#DC0F11] hover:bg-[#BF0103] text-white rounded-full py-3 font-[900] text-[14px] transition shadow-md tracking-wide">
                        KIRIM BUKTI KEKURANGAN
                    </button>
                </form>
            @else
                <!-- Jika pelanggan sudah mengunggah foto kekurangan kedua kalinya -->
                <div class="text-center p-5 bg-[#e6f4ea] rounded-[12px] border border-[#03913F]/20 animate-pulse mb-6">
                    <div class="text-[32px] mb-2">⏳</div>
                    <h3 class="font-[900] text-[#03913F] text-[15px] mb-1">Bukti Kekurangan Terkirim!</h3>
                    <p class="text-[13px] font-[700] text-[#03913F]/80">Mohon tunggu sebentar, kasir kami sedang memverifikasi pelunasanmu...</p>
                </div>
            @endif

        @elseif($order->status_pembayaran == 'Belum Bayar' && $order->bukti_bayar && $order->bukti_bayar !== 'DITOLAK')
            <div class="w-[64px] h-[64px] rounded-full bg-[#e6f4ea] text-[#03913F] flex items-center justify-center text-[32px] mx-auto mb-4 border border-[#03913F]/20">🔎</div>
            <h1 class="text-[24px] font-[900] text-center tracking-tight leading-none mb-2">Menunggu Konfirmasi Kasir</h1>
            <p class="text-[13px] font-[600] text-[#8A8A8E] text-center mb-6 leading-snug">Pesanan tidak akan dimasak<br>sebelum verifikasi kasir</p>

        @else
            <div class="w-[64px] h-[64px] rounded-full bg-[#F3F3F3] text-[#1A1A1A] flex items-center justify-center text-[32px] mx-auto mb-4 border border-black/10">⏳</div>
            <h1 class="text-[24px] font-[900] text-center tracking-tight leading-none mb-2">Menunggu Pembayaran</h1>
            <p class="text-[13px] font-[600] text-[#8A8A8E] text-center mb-6 leading-snug">Pesanan tidak akan dimasak<br>sebelum pembayaran diselesaikan</p>
        @endif

        <div class="text-[36px] font-[900] text-[#DC0F11] text-center mb-6 tracking-widest">
            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </div>

        @if($order->status_pembayaran == 'Belum Bayar')
            @if($order->metode_pembayaran == 'Tunai')
                <div class="p-4 rounded-[12px] bg-[#FDE7E7] text-[#DC0F11] text-center text-[13px] font-[800] mb-6 border border-[#DC0F11]/20">
                    Silakan ke kasir dan tunjukkan nomor pesanan ini untuk bayar tunai agar pesanan segera dimasak
                </div>
            @else
                @if($order->bukti_bayar === 'DITOLAK')
                    <div class="p-4 rounded-[16px] bg-[#FDE7E7] text-[#DC0F11] text-center text-[13px] font-[800] mb-6 border-[2px] border-[#DC0F11]/40 shadow-sm animate-pulse">
                        <span class="text-[20px] block mb-1">⚠️</span>
                        <b class="text-[14px] uppercase tracking-wider block mb-1">Bukti Transfer Ditolak!</b>
                        Gambar sebelumnya tidak valid atau buram. Silakan unggah ulang bukti transfer yang benar agar pesanan dapat diproses.
                    </div>
                @else
                    <div class="p-4 rounded-[12px] bg-[#e6f4ea] text-[#03913F] text-center text-[13px] font-[800] mb-6 border border-[#03913F]/20">
                        Silakan scan QRIS di bawah dan upload bukti transfer.
                    </div>
                @endif
            @endif
        @endif

        <div class="bg-[#F3F3F3] border border-black/5 rounded-[12px] p-4 mb-6 flex justify-between">
            <div>
                <span class="block text-[10px] text-[#8A8A8E] font-[800] mb-1 uppercase tracking-wider">Pemesan</span>
                <b class="text-[14px] font-[800] text-[#1A1A1A]">{{ $order->nama_pelanggan }}</b>
            </div>
            <div class="text-right">
                <span class="block text-[10px] text-[#8A8A8E] font-[800] mb-1 uppercase tracking-wider">Tipe Pesanan</span>
                <b class="text-[14px] font-[800] text-[#1A1A1A]">{{ $order->tipe_pesanan }}</b>
            </div>
        </div>

        <div class="border-y border-black/10 py-5 mb-6 space-y-4">
            @foreach($order->details as $item)
            <div class="flex justify-between items-start text-[14px]">
                <div class="pr-4 flex-1">
                    <div class="font-[800] text-[#1A1A1A] leading-tight mb-1">{{ $item->qty }}x {{ $item->menu_name }}</div>

                    @if($item->catatan)
                        <div class="text-[12px] text-[#5e5e5e] mb-1 font-[600] italic leading-snug">
                            Isian: {{ $item->catatan }}
                        </div>
                    @endif

                    <div class="text-[11px] text-[#8A8A8E] flex flex-wrap gap-1.5">
                        @if($item->level !== null) <span class="bg-[#F3F3F3] px-2 py-0.5 rounded-[4px] font-[800]">Lv {{ $item->level }}</span> @endif
                        @if($item->kencur) <span class="bg-[#F3F3F3] px-2 py-0.5 rounded-[4px] font-[800]">{{ $item->kencur }}</span> @endif
                        @if($item->kuah) <span class="bg-[#F3F3F3] px-2 py-0.5 rounded-[4px] font-[800]">{{ $item->kuah }}</span> @endif
                    </div>
                </div>
                <div class="font-[900] text-[#1A1A1A] whitespace-nowrap pl-3">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center text-[18px] font-[900] mb-6">
            <span class="text-[#1A1A1A]">Total Tagihan</span>
            <span class="text-[#DC0F11]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>

        @if($order->status_pembayaran == 'Belum Bayar' && $order->metode_pembayaran != 'Tunai')
            <div class="border-t border-black/10 pt-6">

                @if($order->bukti_bayar && $order->bukti_bayar !== 'DITOLAK')
                    <div class="text-center p-5 bg-[#e6f4ea] rounded-[12px] border border-[#03913F]/20">
                        <div class="text-[32px] mb-2">📸</div>
                        <h3 class="font-[900] text-[#03913F] text-[15px] mb-1">Bukti Transfer Terkirim!</h3>
                        <p class="text-[13px] font-[700] text-[#03913F]/80">Mohon tunggu sebentar, kasir kami sedang mengecek mutasi dan memverifikasi pesananmu.</p>
                    </div>
                @else
                    <div class="text-center mb-6">
                        <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS" class="mx-auto w-48 h-48 border-[3px] border-[#03913F] rounded-[16px] shadow-sm mb-2 object-cover">
                    </div>
                    <form action="{{ route('pesanan.upload_bukti', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 bg-[#F3F3F3] p-4 rounded-[12px]">
                        @csrf
                        <div>
                            <label class="block text-[13px] font-[900] text-[#1A1A1A] mb-2">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_bayar" accept="image/*" required class="w-full text-[12px] bg-white border border-black/10 rounded-[8px] p-1" onchange="hentikanRefresh()">
                        </div>
                        <button type="submit" class="w-full bg-[#03913F] text-white rounded-full py-3 font-[900] text-[14px]">
                            KIRIM BUKTI BAYAR
                        </button>
                    </form>
                @endif

            </div>
        @elseif($order->status_pembayaran == 'Lunas')
            <div class="flex justify-end mt-2">
                <a href="{{ route('pelanggan.struk', $order->id) }}" class="w-full text-center bg-white border-[2px] border-[#1A1A1A] px-6 py-3 rounded-full font-[900] text-[14px] hover:bg-[#1A1A1A] hover:text-white transition">Lihat Struk Pesanan</a>
            </div>
        @endif

    </div>
</body>
</html>
