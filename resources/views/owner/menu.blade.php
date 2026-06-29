<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu - Pemilik Seblak Jeletet Medan</title>
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
            <div class="w-[40px] h-[40px] bg-[#1A1A1A] text-white rounded-full flex items-center justify-center text-[16px] font-bold">👑</div>
            <div>
                <div class="text-[10px] text-[#8A8A8E] font-[800] uppercase tracking-wider">Owner</div>
                <div class="text-[14px] font-[900] text-[#1A1A1A] leading-tight">{{ Auth::user()->name ?? 'Pemilik Warung' }}</div>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Dashboard</a>
            <a href="{{ route('owner.menu') }}" class="flex items-center gap-4 px-4 py-3 bg-[#DC0F11] text-white rounded-[12px] font-[800] text-[14px] transition shadow-md">Daftar Menu</a>
            <a href="{{ route('owner.kategori') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Kategori</a>
            <a href="{{ route('owner.pengeluaran') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Pengeluaran</a>
            <a href="{{ route('owner.meja') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Meja</a>
            <a href="{{ route('owner.laporan') }}" class="flex items-center gap-4 px-4 py-3 text-[#5e5e5e] hover:bg-[#F3F3F3] hover:text-black rounded-[12px] font-[800] text-[14px] transition">Laporan Transaksi</a>
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

    <main id="mainContent" class="flex-1 flex flex-col overflow-y-auto bg-[#FAF7F4] relative">
        <header class="bg-white/90 backdrop-blur-md px-8 py-5 border-b border-black/5 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-[20px] font-[900] tracking-tight text-[#1A1A1A] m-0">Manajemen Daftar Menu</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="cariMenu()" placeholder="Cari nama menu atau kategori..." class="pl-10 pr-4 py-2 rounded-full bg-[#F3F3F3] border border-transparent focus:bg-white focus:border-[#1A1A1A] outline-none text-[13px] font-[800] text-black transition w-[260px] shadow-sm">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 opacity-50 text-[14px]">🔍</span>
                </div>
                <button onclick="bukaModalTambah()" class="bg-[#DC0F11] hover:bg-[#BF0103] text-white px-5 py-2 rounded-full font-[900] text-[12px] shadow-md transition flex items-center gap-2 shrink-0">
                    TAMBAH MENU
                </button>
            </div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-[20px] shadow-sm border border-black/5 overflow-hidden">
                <table class="w-full text-left border-collapse" id="menuTable">
                    <thead>
                        <tr class="bg-[#f9f9f9] border-b border-black/5 text-[11px] font-[900] text-[#8A8A8E] uppercase tracking-wider">
                            <th class="p-5 w-[50px]">No</th>
                            <th class="p-5 w-[80px] text-center">Foto</th>
                            <th class="p-5">Nama Menu & Deskripsi</th>
                            <th class="p-5">Kategori</th>
                            <th class="p-5 text-right">Harga</th>
                            <th class="p-5 text-center w-[150px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        @forelse($menus as $index => $menu)
                        <tr class="border-b border-black/5 hover:bg-[#FAF7F4] transition data-row">
                            <td class="p-5 font-[900] text-[#8A8A8E]">{{ $index + 1 }}</td>
                            <td class="p-5 text-center">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto" class="w-[48px] h-[48px] rounded-[10px] object-cover shadow-sm border border-black/5 mx-auto">
                                @else
                                    <div class="w-[48px] h-[48px] bg-[#F3F3F3] text-[#8A8A8E] rounded-[10px] flex items-center justify-center text-[20px] mx-auto border border-black/5">🍲</div>
                                @endif
                            </td>
                            <td class="p-5">
                                <div class="font-[900] text-[#1A1A1A] text-[15px] mb-0.5">{{ $menu->nama_menu }}</div>
                                <div class="text-[12px] text-[#8A8A8E] font-[600]">{{ $menu->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                                <div class="flex gap-1 mt-1.5 flex-wrap">
                                    @if($menu->has_level) <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded-[4px] text-[9px] font-bold">LVL</span> @endif
                                    @if($menu->has_kencur) <span class="bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-[4px] text-[9px] font-bold">KENCUR</span> @endif
                                    @if($menu->has_kuah) <span class="bg-amber-100 text-amber-600 px-1.5 py-0.5 rounded-[4px] text-[9px] font-bold">KUAH</span> @endif
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="bg-[#F3F3F3] text-[#5e5e5e] px-3 py-1 rounded-[6px] text-[11px] font-[800]">{{ $menu->category->nama_kategori ?? 'Tanpa Kategori' }}</span>
                            </td>
                            <td class="p-5 text-right font-[900] text-[#1A1A1A]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td class="p-5 text-center flex justify-center gap-2 items-center h-full pt-6">
                                <button onclick="bukaModalEdit('{{ json_encode($menu) }}')" class="bg-[#F3F3F3] hover:bg-black hover:text-white px-3 py-1.5 rounded-[8px] text-[11px] font-[900] transition">EDIT</button>
                                <form action="{{ route('owner.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-[#FDE7E7] text-[#DC0F11] hover:bg-[#DC0F11] hover:text-white px-3 py-1.5 rounded-[8px] text-[11px] font-[900] transition">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyStateRow">
                            <td colspan="6" class="p-10 text-center text-[#8A8A8E] font-[800]">Belum ada menu yang didaftarkan.</td>
                        </tr>
                        @endforelse

                        <tr id="notFoundRow" class="hidden">
                            <td colspan="6" class="p-10 text-center text-[#8A8A8E] font-[800]">Menu yang kamu cari tidak ditemukan 🔍</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalMenu" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[500px] rounded-[24px] p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto scrollbar-none">
            <button onclick="tutupModal()" class="absolute top-5 right-6 text-[24px] text-[#8A8A8E] hover:text-black font-bold">&times;</button>
            <h3 id="modal-title" class="text-[20px] font-[900] mb-6 text-[#1A1A1A]">Tambah Menu Baru</h3>

            <form id="form-menu" action="{{ route('owner.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="mb-4">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-1">Nama Menu</label>
                    <input type="text" name="nama_menu" id="input-nama" required class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] border-transparent focus:bg-white focus:border-black outline-none transition font-[700] text-[14px]">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-1">Kategori</label>
                        <select name="category_id" id="input-kategori" required class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] outline-none font-[700] text-[14px]">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-1">Harga (Rp)</label>
                        <input type="number" name="harga" id="input-harga" required class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] outline-none font-[700] text-[14px]">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-1">Deskripsi Singkat</label>
                    <input type="text" name="deskripsi" id="input-deskripsi" class="w-full px-4 py-3 rounded-[12px] bg-[#F3F3F3] outline-none font-[700] text-[14px]">
                </div>

                <div class="bg-[#fafafa] border border-black/5 p-4 rounded-[12px] mb-4">
                    <label class="block text-[12px] font-[800] text-[#8A8A8E] mb-3 uppercase tracking-wider">Fitur Pilihan Pelanggan (Centang jika ada)</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer font-[700] text-[13px] text-[#1A1A1A]">
                            <input type="checkbox" name="has_level" id="input-level" value="1" class="w-4 h-4 accent-[#DC0F11]"> Level Pedas
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-[700] text-[13px] text-[#1A1A1A]">
                            <input type="checkbox" name="has_kencur" id="input-kencur" value="1" class="w-4 h-4 accent-[#DC0F11]"> Kencur
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-[700] text-[13px] text-[#1A1A1A]">
                            <input type="checkbox" name="has_kuah" id="input-kuah" value="1" class="w-4 h-4 accent-[#DC0F11]"> Kuah
                        </label>
                    </div>
                </div>

                <div class="mb-6 p-4 rounded-[12px] border-2 border-dashed border-black/10 bg-[#fafafa]">
                    <label class="block text-[12px] font-[800] text-[#1A1A1A] mb-2">Upload Foto Menu</label>
                    <input type="file" name="foto" id="input-foto" accept="image/*" class="w-full text-[13px] text-[#5e5e5e] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[12px] file:font-[800] file:bg-[#DC0F11] file:text-white hover:file:bg-[#BF0103] transition cursor-pointer">
                    <p class="text-[10px] text-[#8A8A8E] mt-2 font-[600]">Format: JPG, PNG. Kosongkan jika tidak ingin mengubah foto saat edit.</p>
                </div>

                <button type="submit" class="w-full bg-[#1A1A1A] text-white rounded-full py-4 font-[900] hover:bg-black transition shadow-lg text-[14px]">
                    SIMPAN MENU
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
            if (sessionStorage.getItem('menuSearch')) {
                searchInput.value = sessionStorage.getItem('menuSearch');
                cariMenu(); // Lanjutin pencarian otomatis
            }
            if (sessionStorage.getItem('menuScroll')) {
                mainScroll.scrollTop = sessionStorage.getItem('menuScroll');
            }
        });

        // Simpan setiap ketikan di memori
        searchInput.addEventListener('input', () => {
            sessionStorage.setItem('menuSearch', searchInput.value);
        });

        // Simpan setiap pergeseran scroll di memori
        mainScroll.addEventListener('scroll', () => {
            sessionStorage.setItem('menuScroll', mainScroll.scrollTop);
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
        function cariMenu() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#menuTable tbody tr.data-row");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyStateRow = document.getElementById("emptyStateRow");
            let matchCount = 0;

            if(emptyStateRow && emptyStateRow.style.display !== 'none' && rows.length === 0) return;

            rows.forEach(row => {
                let namaMenu = row.getElementsByTagName("td")[2].textContent.toLowerCase();
                let kategoriMenu = row.getElementsByTagName("td")[3].textContent.toLowerCase();

                if (namaMenu.includes(input) || kategoriMenu.includes(input)) {
                    row.style.display = "";
                    matchCount++;
                } else {
                    row.style.display = "none";
                }
            });

            if(matchCount === 0 && rows.length > 0) {
                notFoundRow.classList.remove('hidden');
            } else {
                notFoundRow.classList.add('hidden');
            }
        }

        // ==========================================
        // 4. FUNGSI MODAL
        // ==========================================
        function bukaModalTambah() {
            document.getElementById('modal-title').innerText = "Tambah Menu Baru";
            document.getElementById('form-menu').action = "{{ route('owner.menu.store') }}";
            document.getElementById('form-method').value = "POST";
            document.getElementById('form-menu').reset();

            document.getElementById('modalMenu').classList.remove('hidden');
            document.getElementById('modalMenu').classList.add('flex');
        }

        function bukaModalEdit(menuStr) {
            let menu = JSON.parse(menuStr);

            document.getElementById('modal-title').innerText = "Edit Menu";
            document.getElementById('form-menu').action = "/pemilik/menu/" + menu.id;
            document.getElementById('form-method').value = "PUT";

            document.getElementById('input-nama').value = menu.nama_menu;
            document.getElementById('input-kategori').value = menu.category_id;
            document.getElementById('input-harga').value = menu.harga;
            document.getElementById('input-deskripsi').value = menu.deskripsi || '';
            document.getElementById('input-foto').value = '';

            document.getElementById('input-level').checked = (menu.has_level == 1);
            document.getElementById('input-kencur').checked = (menu.has_kencur == 1);
            document.getElementById('input-kuah').checked = (menu.has_kuah == 1);

            document.getElementById('modalMenu').classList.remove('hidden');
            document.getElementById('modalMenu').classList.add('flex');
        }

        function tutupModal() {
            document.getElementById('modalMenu').classList.add('hidden');
            document.getElementById('modalMenu').classList.remove('flex');
        }
    </script>
</body>
</html>
