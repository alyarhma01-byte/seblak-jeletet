<!-- HEADER TABEL -->
<div class="grid grid-cols-12 gap-4 p-5 bg-[#fafafa] border-b border-black/5 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
    <div class="col-span-2">Waktu Selesai</div>
    <div class="col-span-2">No Meja</div>
    <div class="col-span-3">Nama Pelanggan</div>
    <div class="col-span-2">Metode</div>
    <div class="col-span-2 text-right">Total Tagihan</div>
    <div class="col-span-1 text-center">Aksi</div>
</div>

<!-- ISI TABEL QR -->
<div class="flex flex-col">
    @forelse($orders_qr as $order)
        <div class="grid grid-cols-12 gap-4 p-5 border-b border-black/5 items-center hover:bg-[#F3F3F3]/50 transition">
            <div class="col-span-2 text-[13px] font-[800] text-[#5e5e5e]">
                {{ $order->updated_at->format('d M Y') }}<br>
                <span class="text-[11px]">{{ $order->updated_at->format('H:i') }} WIB</span>
            </div>
            <div class="col-span-2">
                <span class="text-[16px] font-[900] text-[#DC0F11]">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="col-span-3 text-[14px] font-[800] text-[#1A1A1A]">
                👤 {{ $order->nama_pelanggan }}
            </div>
            <div class="col-span-2">
                <span class="bg-[#F3F3F3] text-[#5e5e5e] px-3 py-1.5 rounded-[6px] text-[11px] font-[900] tracking-wide">
                    {{ strtoupper($order->metode_pembayaran ?? 'TUNAI') }}
                </span>
            </div>
            <div class="col-span-2 text-right">
                <div class="text-[16px] font-[900] text-[#1A1A1A] mb-1">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                <span class="inline-block px-2.5 py-1 rounded-[6px] text-[10px] font-[900] tracking-wide bg-[#03913F]/10 text-[#03913F]">LUNAS</span>
            </div>
            <div class="col-span-1 text-center">
                <button onclick="bukaModalDetail('{{ json_encode($order) }}')" class="bg-[#F3F3F3] hover:bg-[#E85D32] hover:text-white px-3 py-1 rounded-[6px] text-[10px] font-[900] transition">DETAIL</button>
            </div>
        </div>
    @empty
        <div class="p-16 text-center text-[#8A8A8E]">
            <div class="text-[40px] mb-3 opacity-50">📭</div>
            <div class="font-[900] text-[16px] text-[#1A1A1A] mb-1">Belum Ada Transaksi QR</div>
            <div class="font-[700] text-[13px]">Pesanan dari HP pelanggan yang sudah lunas akan muncul di sini.</div>
        </div>
    @endforelse
</div>
