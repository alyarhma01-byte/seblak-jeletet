<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengeluaran - Pemilik Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

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
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Dashboard
            </a>
            <a href="{{ route('owner.menu') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Daftar Menu
            </a>
            <a href="{{ route('owner.kategori') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">
                <span class="text-[18px] opacity-70"></span> Kategori
            </a>
            <a href="{{ route('owner.pengeluaran') }}" class="flex items-center gap-4 px-4 py-3 bg-[#DC0F11] text-white rounded-[12px] font-[800] text-[14px] transition shadow-md">
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

    <main class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] relative">
        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] tracking-tight text-[#1A1A1A] m-0">Manajemen Pengeluaran</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariPengeluaran()" placeholder="Cari pengeluaran atau tgl..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[260px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <button onclick="bukaModalTambah()" class="bg-[#DC0F11] hover:bg-[#BF0103] text-white px-5 py-2 rounded-full font-[900] text-[12px] shadow-md transition flex items-center gap-2 shrink-0">
                    CATAT PENGELUARAN
                </button>
            </div>
        </header>

        <div class="p-8">
            @if(session('success'))
                <div id="toastSuccess" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] bg-[#03913F] text-white px-6 py-3 rounded-full font-[900] text-[14px] shadow-xl flex items-center gap-2 transition-opacity duration-500">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[20px] shadow-sm border border-black/5 overflow-hidden w-full">
                <table class="w-full text-left border-collapse" id="pengeluaranTable">
                    <thead>
                        <tr class="bg-[#f9f9f9] border-b border-black/5 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                            <th class="p-5 w-[150px]">Tanggal</th>
                            <th class="p-5">Nama Pengeluaran</th>
                            <th class="p-5 text-right w-[200px]">Total Biaya</th>
                            <th class="p-5 text-center w-[180px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        @forelse($pengeluaran as $p)
                        <tr class="border-b border-black/5 hover:bg-[#FAF7F4] transition data-row">
                            <td class="p-5 font-[900] text-[#1A1A1A]">{{ \Carbon\Carbon::parse($p->tanggal ?? $p->created_at)->format('d M Y') }}</td>
                            <td class="p-5 font-[800] text-[#5e5e5e]">{{ $p->nama_pengeluaran }}</td>
                            <td class="p-5 font-[900] text-[#DC0F11] text-right">Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</td>
                            <td class="p-5 text-center flex justify-center gap-2">
                                <button onclick="bukaModalEdit('{{ json_encode($p) }}')" class="bg-[#F3F3F3] hover:bg-black hover:text-white px-4 py-2 rounded-[8px] text-[11px] font-[900] transition">EDIT</button>

                                <form action="{{ route('owner.pengeluaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan pengeluaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-[#FDE7E7] text-[#DC0F11] hover:bg-[#DC0F11] hover:text-white px-4 py-2 rounded-[8px] text-[11px] font-[900] transition">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyStateRow">
                            <td colspan="4" class="p-10 text-center text-[#8A8A8E] font-[800]">Belum ada catatan pengeluaran.</td>
                        </tr>
                        @endforelse

                        <tr id="notFoundRow" class="hidden">
                            <td colspan="4" class="p-10 text-center text-[#8A8A8E] font-[800]">Catatan pengeluaran yang kamu cari tidak ditemukan 🔍</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalPengeluaran" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[400px] rounded-[24px] p-8 shadow-2xl relative">
            <button onclick="tutupModal()" class="absolute top-5 right-6 text-[24px] text-[#8A8A8E] hover:text-black font-bold">&times;</button>
            <h3 id="modal-title" class="text-[20px] font-[900] mb-6 text-[#1A1A1A]">Catat Pengeluaran</h3>

            <form id="form-pengeluaran" action="{{ route('owner.pengeluaran.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="mb-4">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="input-tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border-transparent focus:bg-white focus:border-black outline-none transition font-[700] text-[14px]">
                </div>

                <div class="mb-4">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-2">Nama Pengeluaran</label>
                    <input type="text" name="nama_pengeluaran" id="input-nama" required placeholder="Cth: Belanja Kerupuk & Kencur" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border-transparent focus:bg-white focus:border-black outline-none transition font-[700] text-[14px]">
                </div>

                <div class="mb-6">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-2">Total Biaya (Rp)</label>
                    <input type="number" name="total_biaya" id="input-biaya" required placeholder="Cth: 150000" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border-transparent focus:bg-white focus:border-black outline-none transition font-[700] text-[14px]">
                </div>

                <button type="submit" class="w-full bg-[#1A1A1A] text-white rounded-full py-4 font-[900] hover:bg-black transition shadow-lg text-[14px]">
                    SIMPAN DATA
                </button>
            </form>
        </div>
    </div>

    <script>
        if (toast) {
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
        // FITUR PENCARIAN LIVE
        function cariPengeluaran() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#pengeluaranTable tbody tr.data-row");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyStateRow = document.getElementById("emptyStateRow");
            let matchCount = 0;

            // Jangan filter jika belum ada data sama sekali dari database
            if(emptyStateRow && emptyStateRow.style.display !== 'none' && rows.length === 0) return;

            rows.forEach(row => {
                // Ambil text dari kolom Tanggal (index 0) dan Nama Pengeluaran (index 1)
                let tanggal = row.getElementsByTagName("td")[0].textContent.toLowerCase();
                let nama = row.getElementsByTagName("td")[1].textContent.toLowerCase();

                if (nama.includes(input) || tanggal.includes(input)) {
                    row.style.display = ""; // Munculkan baris
                    matchCount++;
                } else {
                    row.style.display = "none"; // Sembunyikan baris
                }
            });

            // Tampilkan pesan "Tidak ditemukan" jika hasil pencarian 0
            if(matchCount === 0 && rows.length > 0) {
                notFoundRow.classList.remove('hidden');
            } else {
                notFoundRow.classList.add('hidden');
            }
        }

        // FUNGSI BUKA MODAL TAMBAH
        function bukaModalTambah() {
            document.getElementById('modal-title').innerText = "Catat Pengeluaran";
            document.getElementById('form-pengeluaran').action = "{{ route('owner.pengeluaran.store') }}";
            document.getElementById('form-method').value = "POST";

            document.getElementById('input-nama').value = "";
            document.getElementById('input-biaya').value = "";
            document.getElementById('input-tanggal').value = "{{ date('Y-m-d') }}";

            document.getElementById('modalPengeluaran').classList.remove('hidden');
            document.getElementById('modalPengeluaran').classList.add('flex');
        }

        // FUNGSI BUKA MODAL EDIT
        function bukaModalEdit(pStr) {
            let p = JSON.parse(pStr);

            document.getElementById('modal-title').innerText = "Edit Pengeluaran";
            document.getElementById('form-pengeluaran').action = "/pemilik/pengeluaran/" + p.id;
            document.getElementById('form-method').value = "PUT";

            // Mengambil tanggal (memotong jamnya jika ada format datetime)
            let tanggal = p.tanggal ? p.tanggal.split(' ')[0] : p.created_at.split('T')[0];

            document.getElementById('input-tanggal').value = tanggal;
            document.getElementById('input-nama').value = p.nama_pengeluaran;
            document.getElementById('input-biaya').value = p.total_biaya;

            document.getElementById('modalPengeluaran').classList.remove('hidden');
            document.getElementById('modalPengeluaran').classList.add('flex');
        }

        // FUNGSI TUTUP MODAL
        function tutupModal() {
            document.getElementById('modalPengeluaran').classList.add('hidden');
            document.getElementById('modalPengeluaran').classList.remove('flex');
        }
    </script>
</body>
</html>
