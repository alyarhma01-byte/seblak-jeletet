<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    // Tampilkan halaman
    public function index()
    {
        // Ambil data dari yang paling baru tanggalnya
        $pengeluaran = Pengeluaran::orderBy('tanggal', 'desc')->get();
        return view('owner.pengeluaran', compact('pengeluaran'));
    }

    // Simpan pengeluaran baru
    public function store(Request $request)
    {
        Pengeluaran::create([
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'total_biaya' => $request->total_biaya,
            'tanggal' => $request->tanggal,
        ]);

        return back()->with('success', 'Data pengeluaran berhasil dicatat!');
    }

    // Hapus pengeluaran
    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return back()->with('success', 'Data pengeluaran berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pengeluaran' => 'required|string|max:255',
            'total_biaya' => 'required|numeric'
        ]);

        $pengeluaran = \App\Models\Pengeluaran::findOrFail($id);

        // Cek jika field database kamu menggunakan 'keterangan' atau 'nama_pengeluaran'
        if (\Schema::hasColumn('pengeluaran', 'nama_pengeluaran')) {
            $pengeluaran->nama_pengeluaran = $request->nama_pengeluaran;
        } else {
            $pengeluaran->keterangan = $request->nama_pengeluaran;
        }

        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->total_biaya = $request->total_biaya;
        $pengeluaran->save();

        return back()->with('success', 'Catatan pengeluaran berhasil diperbarui!');
    }


}
