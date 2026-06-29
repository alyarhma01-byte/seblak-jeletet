<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <a href="{{ route('kasir.meja') }}" class="flex items-center gap-3 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-full font-[800] text-[13px] transition">
                Pesanan Meja (QR)
            </a>
            <a href="{{ route('kasir.riwayat') }}" class="flex items-center gap-3 px-4 py-3 bg-black text-white rounded-full font-[800] text-[13px] transition shadow-md">
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
            <h1 class="text-[20px] font-[900] tracking-tight text-[#1A1A1A] m-0">Riwayat Transaksi</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariRiwayat()" placeholder="Cari nama atau meja..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[240px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <div class="bg-[#03913F]/10 text-[#03913F] px-4 py-1.5 rounded-full font-[900] text-[11px] border border-[#03913F]/20 tracking-widest shadow-sm shrink-0">
                    DATA TERSIMPAN
                </div>
            </div>
        </header>

        <div class="p-8 space-y-6">

            <div class="flex justify-between items-center bg-white p-2 rounded-[16px] border border-black/5 shadow-sm">
                <div class="flex gap-2">
                    <button type="button" onclick="switchTab('prasmanan')" id="btn-prasmanan" class="px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition bg-[#DC0F11] text-white shadow-md">Prasmanan</button>
                    <button type="button" onclick="switchTab('qr')" id="btn-qr" class="px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition text-[#8A8A8E] hover:bg-[#F3F3F3]">Pesanan QR</button>
                </div>

                <form action="{{ route('kasir.riwayat') }}" method="GET" class="flex items-center gap-2 pr-2">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-3 py-2 bg-[#F3F3F3] rounded-[8px] text-[13px] font-[800] text-[#1A1A1A] outline-none border border-transparent focus:border-black/20 cursor-pointer">
                    <button type="submit" class="bg-[#1A1A1A] text-white px-4 py-2 rounded-[8px] text-[12px] font-[900] hover:bg-black transition">CARI</button>

                    @if(request('tanggal'))
                        <a href="{{ route('kasir.riwayat') }}" class="bg-[#FDE7E7] text-[#DC0F11] px-3 py-2 rounded-[8px] text-[12px] font-[900] hover:bg-[#DC0F11] hover:text-white transition">RESET</a>
                    @endif
                </form>
            </div>

            @if(request('tanggal'))
                <div class="text-[13px] font-[800] text-[#8A8A8E]">
                    Menampilkan riwayat untuk tanggal: <span class="text-[#DC0F11]">{{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}</span>
                </div>
            @endif

            <div id="tab-prasmanan" class="bg-white rounded-[20px] shadow-sm border border-black/5 overflow-hidden block">
                <div class="p-5 bg-[#fafafa] border-b grid grid-cols-12 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                    <div class="col-span-2">Waktu Selesai</div><div class="col-span-2">No Meja</div><div class="col-span-3">Pelanggan</div><div class="col-span-2">Metode</div><div class="col-span-2 text-right">Total Tagihan</div><div class="col-span-1 text-center">Aksi</div>
                </div>
                <div class="flex flex-col" id="container-prasmanan">
                    @forelse($orders_prasmanan as $order)
                    <div class="grid grid-cols-12 gap-4 p-5 border-b border-black/5 items-center hover:bg-[#F3F3F3]/50 transition data-row">
                        <div class="col-span-2 text-[13px] font-[800] text-[#5e5e5e]">{{ $order->updated_at->format('d M Y') }}<br><span class="text-[11px]">{{ $order->updated_at->format('H:i') }} WIB</span></div>

                        <div class="col-span-2 font-[900] text-[#DC0F11] text-[16px] data-meja">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="col-span-3 font-[800] text-[#1A1A1A] text-[14px] data-nama">{{ $order->nama_pelanggan }}</div>

                        <div class="col-span-2"><span class="bg-[#F3F3F3] text-[#5e5e5e] px-3 py-1.5 rounded-[6px] text-[11px] font-[900] tracking-wide">{{ strtoupper($order->metode_pembayaran ?? 'TUNAI') }}</span></div>
                        <div class="col-span-2 text-right">
                            <div class="text-[16px] font-[900] text-[#1A1A1A] mb-1">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                            <span class="inline-block px-2.5 py-1 rounded-[6px] text-[10px] font-[900] tracking-wide bg-[#03913F]/10 text-[#03913F]">LUNAS</span>
                        </div>
                        <div class="col-span-1 text-center">
                            <button onclick="bukaModalDetail('{{ json_encode($order) }}')" class="bg-[#F3F3F3] hover:bg-[#E85D32] hover:text-white px-3 py-1 rounded-[6px] text-[10px] font-[900] transition text-[#1A1A1A]">DETAIL</button>
                        </div>
                    </div>
                    @empty
                        <div class="p-16 text-center text-[#8A8A8E] empty-state">
                            <div class="text-[40px] mb-3 opacity-50">📭</div>
                            <div class="font-[900] text-[16px] text-[#1A1A1A] mb-1">Belum Ada Transaksi</div>
                            <div class="font-[700] text-[13px]">Riwayat pesanan prasmanan akan muncul di sini.</div>
                        </div>
                    @endforelse

                    <div class="p-16 text-center text-[#8A8A8E] not-found-state hidden">
                        <div class="text-[32px] mb-2 opacity-50">🔍</div>
                        <div class="font-[900] text-[16px] text-[#1A1A1A]">Riwayat Tidak Ditemukan</div>
                    </div>
                </div>
            </div>

            <div id="tab-qr" class="bg-white rounded-[20px] shadow-sm border border-black/5 overflow-hidden hidden">
                <div class="p-5 bg-[#fafafa] border-b grid grid-cols-12 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                    <div class="col-span-2">Waktu Selesai</div><div class="col-span-2">No Meja</div><div class="col-span-3">Pelanggan</div><div class="col-span-2">Metode</div><div class="col-span-2 text-right">Total Tagihan</div><div class="col-span-1 text-center">Aksi</div>
                </div>
                <div class="flex flex-col" id="container-qr">
                    @forelse($orders_qr as $order)
                    <div class="grid grid-cols-12 gap-4 p-5 border-b border-black/5 items-center hover:bg-[#F3F3F3]/50 transition data-row">
                        <div class="col-span-2 text-[13px] font-[800] text-[#5e5e5e]">{{ $order->updated_at->format('d M Y') }}<br><span class="text-[11px]">{{ $order->updated_at->format('H:i') }} WIB</span></div>

                        <div class="col-span-2 font-[900] text-[#DC0F11] text-[16px] data-meja">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="col-span-3 font-[800] text-[#1A1A1A] text-[14px] data-nama">👤 {{ $order->nama_pelanggan }}</div>

                        <div class="col-span-2"><span class="bg-[#F3F3F3] text-[#5e5e5e] px-3 py-1.5 rounded-[6px] text-[11px] font-[900] tracking-wide">{{ strtoupper($order->metode_pembayaran ?? 'TUNAI') }}</span></div>
                        <div class="col-span-2 text-right">
                            <div class="text-[16px] font-[900] text-[#1A1A1A] mb-1">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                            <span class="inline-block px-2.5 py-1 rounded-[6px] text-[10px] font-[900] tracking-wide bg-[#03913F]/10 text-[#03913F]">LUNAS</span>
                        </div>
                        <div class="col-span-1 text-center">
                            <button onclick="bukaModalDetail('{{ json_encode($order) }}')" class="bg-[#F3F3F3] hover:bg-[#E85D32] hover:text-white px-3 py-1 rounded-[6px] text-[10px] font-[900] transition text-[#1A1A1A]">DETAIL</button>
                        </div>
                    </div>
                    @empty
                        <div class="p-16 text-center text-[#8A8A8E] empty-state">
                            <div class="text-[40px] mb-3 opacity-50">📭</div>
                            <div class="font-[900] text-[16px] text-[#1A1A1A] mb-1">Belum Ada Transaksi QR</div>
                            <div class="font-[700] text-[13px]">Riwayat pesanan dari QR akan muncul di sini.</div>
                        </div>
                    @endforelse

                    <div class="p-16 text-center text-[#8A8A8E] not-found-state hidden">
                        <div class="text-[32px] mb-2 opacity-50">🔍</div>
                        <div class="font-[900] text-[16px] text-[#1A1A1A]">Riwayat Tidak Ditemukan</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div id="modalDetail" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[400px] rounded-[24px] p-8 shadow-2xl">
            <h3 class="text-[20px] font-[900] mb-6 text-[#1A1A1A]">Rincian Transaksi</h3>

            <div class="bg-[#F3F3F3] p-4 rounded-[16px] mb-6 space-y-2 text-[#1A1A1A]">
                <div class="flex justify-between text-[13px]">
                    <span class="text-[#8A8A8E] font-[800]">Metode:</span>
                    <span id="modal-metode" class="font-[900] uppercase"></span>
                </div>
                <div class="flex justify-between text-[14px]">
                    <span class="text-[#8A8A8E] font-[800]">Tagihan:</span>
                    <span id="modal-total" class="font-[900] text-[#DC0F11]"></span>
                </div>
                <div class="flex justify-between text-[14px] text-[#03913F]">
                    <span class="font-[800]">Uang Bayar:</span>
                    <span id="modal-uang" class="font-[900]"></span>
                </div>
                <div class="flex justify-between border-t border-black/10 pt-2 mt-2 text-[14px]">
                    <span class="text-[#8A8A8E] font-[800]">Kembalian:</span>
                    <span id="modal-kembalian" class="font-[900]"></span>
                </div>
            </div>

            <div id="detail-list" class="max-h-[200px] overflow-y-auto mb-6 pr-2"></div>

            <div class="flex gap-3">
                <button onclick="tutupModalDetail()" class="w-1/3 bg-[#efefef] text-[#1A1A1A] rounded-full py-3.5 font-[900] hover:bg-[#e2e2e2] transition text-[13px]">TUTUP</button>
                <a id="btn-cetak-struk" href="#" target="_blank" class="w-2/3 bg-[#DC0F11] text-white rounded-full py-3.5 font-[900] hover:bg-[#BF0103] transition text-[13px] text-center flex items-center justify-center gap-2 shadow-md">
                    LIHAT E-STRUK
                </a>
            </div>
        </div>
    </div>

    <script>
        let activeTabId = 'tab-prasmanan';

        function cariRiwayat() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let activeContainer = document.getElementById(activeTabId);
            let rows = activeContainer.querySelectorAll(".data-row");
            let notFoundState = activeContainer.querySelector(".not-found-state");
            let emptyState = activeContainer.querySelector(".empty-state");
            let matchCount = 0;

            if(emptyState && emptyState.style.display !== 'none' && rows.length === 0) return;

            rows.forEach(row => {
                let meja = row.querySelector(".data-meja").textContent.toLowerCase();
                let nama = row.querySelector(".data-nama").textContent.toLowerCase();
                let metode = row.querySelector(".col-span-2 > span.bg-\\[\\#F3F3F3\\]").textContent.toLowerCase();

                if (meja.includes(input) || nama.includes(input) || metode.includes(input)) {
                    row.style.display = "";
                    matchCount++;
                } else {
                    row.style.display = "none";
                }
            });

            if(matchCount === 0 && rows.length > 0) {
                notFoundState.classList.remove('hidden');
            } else {
                notFoundState.classList.add('hidden');
            }
        }

        function switchTab(tab) {
            const btnPrasmanan = document.getElementById('btn-prasmanan');
            const btnQr = document.getElementById('btn-qr');
            const tabPrasmanan = document.getElementById('tab-prasmanan');
            const tabQr = document.getElementById('tab-qr');

            document.getElementById("searchInput").value = "";

            if (tab === 'prasmanan') {
                activeTabId = 'tab-prasmanan';
                btnPrasmanan.className = "px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition bg-[#DC0F11] text-white shadow-md";
                tabPrasmanan.classList.remove('hidden');
                tabPrasmanan.classList.add('block');

                btnQr.className = "px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition bg-transparent text-[#8A8A8E] hover:bg-[#F3F3F3]";
                tabQr.classList.remove('block');
                tabQr.classList.add('hidden');
            } else {
                activeTabId = 'tab-qr';
                btnQr.className = "px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition bg-[#DC0F11] text-white shadow-md";
                tabQr.classList.remove('hidden');
                tabQr.classList.add('block');

                btnPrasmanan.className = "px-6 py-2.5 rounded-[12px] font-[900] text-[13px] transition bg-transparent text-[#8A8A8E] hover:bg-[#F3F3F3]";
                tabPrasmanan.classList.remove('block');
                tabPrasmanan.classList.add('hidden');
            }
            cariRiwayat();
        }

        function bukaModalDetail(orderJson) {
            let order = JSON.parse(orderJson);

            document.getElementById('modal-total').innerText = "Rp " + parseInt(order.total_harga).toLocaleString('id-ID');
            document.getElementById('modal-uang').innerText = "Rp " + parseInt(order.uang_bayar || 0).toLocaleString('id-ID');
            document.getElementById('modal-kembalian').innerText = "Rp " + parseInt(order.kembalian || 0).toLocaleString('id-ID');
            document.getElementById('modal-metode').innerText = order.metode_pembayaran;

            document.getElementById('btn-cetak-struk').href = "/kasir/struk/" + order.id;

            let list = document.getElementById('detail-list');
            list.innerHTML = '';
            order.details.forEach(item => {
                let catatanHTML = item.catatan ? `<div class="text-[12px] text-[#5e5e5e] italic font-[600] mt-0.5">Isian: ${item.catatan}</div>` : '';
                list.innerHTML += `
                    <div class="flex justify-between items-start mb-4 border-b border-black/5 pb-3">
                        <div class="flex-1 pr-2">
                            <b class="text-[#1A1A1A] leading-tight block">${item.qty}x ${item.menu_name}</b>
                            ${catatanHTML}
                        </div>
                        <div class="font-[900] text-[#1A1A1A]">Rp ${parseInt(item.harga * item.qty).toLocaleString('id-ID')}</div>
                    </div>
                `;
            });

            let modal = document.getElementById('modalDetail');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalDetail() {
            let modal = document.getElementById('modalDetail');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
