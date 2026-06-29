<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap nomor meja dari URL (contoh: ?meja=5)
        $meja = $request->query('meja');
        return view('welcome', compact('meja'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pelanggan' => 'required|string|max:50',
            'tipe_pesanan' => 'required|in:Makan Sini,Bungkus',
        ]);

        // Simpan data ke session
        session([
            'nama_pelanggan' => $request->nama_pelanggan,
            'tipe_pesanan' => $request->tipe_pesanan,
            'meja' => $request->meja ?? 'Kasir' // Kalau gak ada nomor meja, anggap pesan di kasir
        ]);

        // Lempar ke halaman menu
        return redirect()->route('menu.index');
    }
}
