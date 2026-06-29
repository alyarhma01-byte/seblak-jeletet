<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category; // Pastikan model Category juga dipanggil
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib dipanggil untuk mengurus file foto

class MenuController extends Controller
{
    // Menampilkan halaman daftar menu
    public function index()
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();

        // Alamat view tetap utuh sesuai permintaanmu!
        return view('owner.menu', compact('menus', 'categories'));
    }

    // Menyimpan menu baru
    public function store(Request $request)
    {
        // 1. Siapkan data dasar terlebih dahulu
        $data = [
            'nama_menu' => $request->nama_menu,
            'category_id' => $request->category_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            // Simpan checkbox sebagai angka 1 (dicentang) atau 0 (tidak dicentang)
            'has_level' => $request->has('has_level') ? 1 : 0,
            'has_kencur' => $request->has('has_kencur') ? 1 : 0,
            'has_kuah' => $request->has('has_kuah') ? 1 : 0,
        ];

        // 2. Jika ada file foto yang di-upload, simpan fotonya
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        // 3. Masukkan semua data ke database
        Menu::create($data);

        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    // Mengupdate menu yang ada
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // 1. Siapkan data dasar
        $data = [
            'nama_menu' => $request->nama_menu,
            'category_id' => $request->category_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'has_level' => $request->has('has_level') ? 1 : 0,
            'has_kencur' => $request->has('has_kencur') ? 1 : 0,
            'has_kuah' => $request->has('has_kuah') ? 1 : 0,
        ];

        // 2. Jika pemilik mengganti foto saat edit
        if ($request->hasFile('foto')) {
            // Hapus foto lama (jika sebelumnya sudah ada foto)
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            // Simpan foto yang baru
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        // 3. Update data di database
        $menu->update($data);

        return back()->with('success', 'Menu berhasil diperbarui!');
    }

    // Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus juga file fotonya dari folder komputermu agar tidak jadi sampah
        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus!');
    }

    // ==========================================
    // AREA PELANGGAN
    // ==========================================

    // Menampilkan halaman katalog menu untuk PELANGGAN
    public function katalog()
    {
        // Ambil semua menu dan kategori untuk ditampilkan ke pelanggan
        $menus = Menu::with('category')->get();
        $categories = Category::all();

        // Mengarahkan ke file tampilan/blade khusus pelanggan
        // Catatan: Jika file blade pelangganmu ada di dalam folder, sesuaikan namanya ya (misal: 'pelanggan.menu')
        return view('menu.index', compact('menus', 'categories'));
    }
}
