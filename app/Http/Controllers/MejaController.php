<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    // 1. Menampilkan Halaman Daftar Meja
    public function index()
    {
        $mejas = Meja::orderBy('nomor_meja', 'asc')->get();
        return view('owner.meja', compact('mejas'));
    }


    // 2. Menyimpan Data Meja Baru & Otomatis Buat Link QR
    public function store(Request $request)
    {
        // 🔥 PERBAIKAN 1: Validasi hanya untuk nomor meja, status_meja dihapus
        $request->validate([
            'nomor_meja' => 'required|string|unique:meja,nomor_meja'
        ]);

        // KUNCI UTAMA: Membuat Link QR Code otomatis
        // Contoh hasil: http://127.0.0.1:8000/?meja=1
        $link = url('/?meja=' . $request->nomor_meja);

        Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'status_meja' => 'Tersedia', // 🔥 PERBAIKAN 2: Langsung dikunci otomatis 'Tersedia'
            'link_qr_code' => $link
        ]);

        return back()->with('success', 'Meja baru dan Link QR berhasil ditambahkan!');
    }

    // 3. Menghapus Data Meja
    public function destroy($id)
    {
        Meja::findOrFail($id)->delete();
        return back()->with('success', 'Data meja berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'nomor_meja' => 'required|string'
        ]);

        $cekKembar = \App\Models\Meja::where('nomor_meja', $request->nomor_meja)
                                     ->where('id', '!=', $id)
                                     ->first();

        if ($cekKembar) {
            return back()->withErrors(['nomor_meja' => 'Gagal! Nomor Meja sudah digunakan.']);
        }

        $meja = \App\Models\Meja::findOrFail($id);
        $meja->nomor_meja = $request->nomor_meja;

        // Kita kunci statusnya 'Tersedia' saja atau hapus field-nya kalau mau
        $meja->status_meja = 'Tersedia';

        $meja->link_qr_code = url('/?meja=' . $meja->nomor_meja);
        $meja->save();

        return back()->with('success', 'Data Meja berhasil diperbarui!');
    }
}
