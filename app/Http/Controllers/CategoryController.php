<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Tampilkan Halaman
    public function index()
    {
        $categories = Category::all();
        return view('owner.kategori', compact('categories'));
    }

    // 2. Simpan Data Baru
    public function store(Request $request)
    {
        // Ganti 'nama_kategori' menjadi 'name_kategori' jika nama kolom di databasemu bahasa inggris
        Category::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // 3. Edit Data
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return back()->with('success', 'Nama kategori berhasil diperbarui!');
    }

    // 4. Hapus Data
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    
}
