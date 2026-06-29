<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Pemilik Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex h-screen overflow-hidden relative">


    @if(session('success'))
        <div id="toastSuccess" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] bg-[#03913F] text-white px-6 py-3 rounded-full font-[900] text-[14px] shadow-xl flex items-center gap-2 transition-opacity duration-500">
             {{ session('success') }}
        </div>
    @endif

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
            <a href="{{ route('owner.kategori') }}" class="flex items-center gap-4 px-4 py-3 bg-[#DC0F11] text-white rounded-[12px] font-[800] text-[14px] transition shadow-md">
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

    <!-- Ditambahin id="mainContent" buat nyatet posisi scroll -->
    <main id="mainContent" class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] relative">
        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] tracking-tight text-[#1A1A1A] m-0">Manajemen Kategori Menu</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariKategori()" placeholder="Cari nama kategori..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[260px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <button onclick="bukaModalTambah()" class="bg-[#DC0F11] hover:bg-[#BF0103] text-white px-5 py-2 rounded-full font-[900] text-[12px] shadow-md transition flex items-center gap-2 shrink-0">
                    TAMBAH KATEGORI
                </button>
            </div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-[20px] shadow-sm border border-black/5 overflow-hidden w-full">
                <table class="w-full text-left border-collapse" id="kategoriTable">
                    <thead>
                        <tr class="bg-[#f9f9f9] border-b border-black/5 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                            <th class="p-5 w-[80px] text-center">No</th>
                            <th class="p-5">Nama Kategori</th>
                            <th class="p-5 text-center w-[180px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        @forelse($categories as $index => $cat)
                        <tr class="border-b border-black/5 hover:bg-[#FAF7F4] transition data-row">
                            <td class="p-5 font-[900] text-[#8A8A8E] text-center">{{ $index + 1 }}</td>
                            <td class="p-5">
                                <div class="font-[900] text-[#1A1A1A] text-[15px]">{{ $cat->nama_kategori ?? $cat->name_kategori }}</div>
                            </td>
                            <td class="p-5 text-center flex justify-center gap-2">
                                <button onclick="bukaModalEdit('{{ json_encode($cat) }}')" class="bg-[#F3F3F3] hover:bg-black hover:text-white px-4 py-2 rounded-[8px] text-[11px] font-[900] transition">EDIT</button>
                                <form action="{{ route('owner.kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada menu yang terhubung.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-[#FDE7E7] text-[#DC0F11] hover:bg-[#DC0F11] hover:text-white px-4 py-2 rounded-[8px] text-[11px] font-[900] transition">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyStateRow">
                            <td colspan="3" class="p-10 text-center text-[#8A8A8E] font-[800]">Belum ada kategori yang dibuat.</td>
                        </tr>
                        @endforelse

                        <tr id="notFoundRow" class="hidden">
                            <td colspan="3" class="p-10 text-center text-[#8A8A8E] font-[800]">Kategori yang kamu cari tidak ditemukan 🔍</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalKategori" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[400px] rounded-[24px] p-8 shadow-2xl relative">
            <button onclick="tutupModal()" class="absolute top-5 right-6 text-[24px] text-[#8A8A8E] hover:text-black font-bold">&times;</button>
            <h3 id="modal-title" class="text-[20px] font-[900] mb-6 text-[#1A1A1A]">Tambah Kategori</h3>

            <form id="form-kategori" action="{{ route('owner.kategori.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="mb-6">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="input-nama" required placeholder="Contoh: Minuman Dingin" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border-transparent focus:bg-white focus:border-black outline-none transition font-[700] text-[14px]">
                </div>

                <button type="submit" class="w-full bg-[#1A1A1A] text-white rounded-full py-4 font-[900] hover:bg-black transition shadow-lg text-[14px]">
                    SIMPAN KATEGORI
                </button>
            </form>
        </div>
    </div>

    <script>
        // ==========================================
        // 1. SISTEM INGAT POSISI (SEARCH & SCROLL)
        // ==========================================
        const mainScroll = document.getElementById('mainContent');
        const searchInput = document.getElementById('searchInput');

        // Pas halaman pertama kebuka, cek ada gak memori sebelumnya
        document.addEventListener('DOMContentLoaded', () => {
            if (sessionStorage.getItem('kategoriSearch')) {
                searchInput.value = sessionStorage.getItem('kategoriSearch');
                cariKategori(); // Lanjutin pencarian otomatis
            }
            if (sessionStorage.getItem('kategoriScroll')) {
                mainScroll.scrollTop = sessionStorage.getItem('kategoriScroll');
            }
        });

        // Simpan setiap ketikan di memori
        searchInput.addEventListener('input', () => {
            sessionStorage.setItem('kategoriSearch', searchInput.value);
        });

        // Simpan setiap pergeseran scroll di memori
        mainScroll.addEventListener('scroll', () => {
            sessionStorage.setItem('kategoriScroll', mainScroll.scrollTop);
        });

        // ==========================================
        // 2. MENGHILANGKAN TOAST OTOMATIS
        // ==========================================
        let toast = document.getElementById('toastSuccess');
        if (toast) {
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 3000); // Hilang setelah 3 detik
        }

        // ==========================================
        // 3. FITUR PENCARIAN LIVE
        // ==========================================
        function cariKategori() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#kategoriTable tbody tr.data-row");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyStateRow = document.getElementById("emptyStateRow");
            let matchCount = 0;

            // Jangan filter jika belum ada data sama sekali dari database
            if(emptyStateRow && emptyStateRow.style.display !== 'none' && rows.length === 0) return;

            rows.forEach(row => {
                // Ambil text dari kolom Nama Kategori (index 1)
                let namaKategori = row.getElementsByTagName("td")[1].textContent.toLowerCase();

                if (namaKategori.includes(input)) {
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

        // ==========================================
        // 4. MODAL LOGIC
        // ==========================================
        function bukaModalTambah() {
            document.getElementById('modal-title').innerText = "Tambah Kategori";
            document.getElementById('form-kategori').action = "{{ route('owner.kategori.store') }}";
            document.getElementById('form-method').value = "POST";
            document.getElementById('input-nama').value = "";

            document.getElementById('modalKategori').classList.remove('hidden');
            document.getElementById('modalKategori').classList.add('flex');
        }

        function bukaModalEdit(catStr) {
            let cat = JSON.parse(catStr);

            document.getElementById('modal-title').innerText = "Edit Kategori";
            document.getElementById('form-kategori').action = "/pemilik/kategori/" + cat.id;
            document.getElementById('form-method').value = "PUT";

            // Mengambil nama dari database yang sudah ada
            document.getElementById('input-nama').value = cat.nama_kategori || cat.name_kategori;

            document.getElementById('modalKategori').classList.remove('hidden');
            document.getElementById('modalKategori').classList.add('flex');
        }

        function tutupModal() {
            document.getElementById('modalKategori').classList.add('hidden');
            document.getElementById('modalKategori').classList.remove('flex');
        }
    </script>
</body>
</html>
