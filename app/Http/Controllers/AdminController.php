<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Tampilkan pesanan khusus kasir (Prasmanan) yang belum selesai KHUSUS HARI INI
        $orders = Order::where('status_pesanan', '!=', 'Selesai')
                       ->where('tipe_pesanan', 'Prasmanan Kasir')
                       ->whereDate('created_at', \Carbon\Carbon::today()) // Filter khusus hari ini
                       ->orderBy('updated_at', 'desc')
                       ->get();

        $menus = Menu::all();

        return view('admin.index', compact('orders', 'menus'));
    }

    public function pesananMeja()
    {
        // 2. Tampilkan pesanan dari meja (QR Code Pelanggan) yang belum selesai KHUSUS HARI INI
        $orders = Order::where('status_pesanan', '!=', 'Selesai')
                       ->where('tipe_pesanan', '!=', 'Prasmanan Kasir')
                       ->whereDate('created_at', \Carbon\Carbon::today()) // Filter khusus hari ini
                       ->orderBy('updated_at', 'desc')
                       ->get();

        return view('admin.pesanan_meja', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($request->action == 'selesai') {
            $order->status_pesanan = 'Selesai';
        } elseif ($request->action == 'lunas') {
            $order->status_pembayaran = 'Lunas';
        }
        $order->save();
        return back()->with('success', 'Status pesanan diperbarui!');
    }

    // =======================================================
    // FUNGSI LUNAS (UNTUK TUNAI & QRIS) - SUDAH MENDUKUNG KURANG BAYAR
    // =======================================================
    public function lunas(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $metode = $request->metode_pembayaran ?? 'Tunai';
        $total_tagihan = $order->total_harga;

        // 1. Cek apakah ini pelunasan dari status 'Kurang Bayar'
        $uang_sebelumnya = ($order->status_pembayaran == 'Kurang Bayar') ? $order->uang_bayar : 0;

        // 2. Hitung sisa yang harus dibayar
        $sisa_tagihan = $total_tagihan - $uang_sebelumnya;

        // 3. Tangkap uang yang dikasih pelanggan saat ini (Jika kosong, otomatis dianggap pas)
        $uang_baru = $request->uang_bayar ?? $sisa_tagihan;

        // 4. Hitung total semua uang yang masuk (Uang lama + Uang baru)
        $total_uang_masuk = $uang_sebelumnya + $uang_baru;

        // 5. Validasi: Apakah total uangnya sudah cukup untuk melunasi total tagihan?
        if ($total_uang_masuk < $total_tagihan) {
            return back()->with('error', 'Gagal memproses! Uang bayar masih kurang dari sisa tagihan.');
        }

        // 6. Simpan pelunasan ke database
        $order->uang_bayar = $total_uang_masuk;
        $order->kembalian = $total_uang_masuk - $total_tagihan;
        $order->status_pembayaran = 'Lunas';

        // Jika pelunasan menggunakan Tunai, update metode bayarnya
        $order->metode_pembayaran = $metode;

        if ($order->status_pesanan == 'Menunggu') {
             $order->status_pesanan = 'Diproses';
        }

        $order->save();

        return back()->with('success', 'Pesanan Meja ' . $order->no_meja . ' berhasil dilunasi via ' . $metode);
    }


    // =====================================================================
    // FUNGSI KURANG BAYAR UNIVERSAL (SAKTI UNTUK MEJA QR & PRASMANAN)
    // =====================================================================
    public function kurangBayar(Request $request, $id)
    {
        // 1. Cari data pesanan berdasarkan ID
        $order = Order::findOrFail($id);

        // 2. Tangkap nominal uang yang SUDAH ADA di database sebelumnya (jika ini nyicil kedua)
        $uang_lama = ($order->status_pembayaran == 'Kurang Bayar') ? $order->uang_bayar : 0;

        // 3. Tangkap nominal uang transferan BARU yang baru saja diketik oleh kasir
        $uang_baru = $request->uang_bayar ?? 0;

        // 4. RUMUS AKUMULASI: Jumlahkan seluruh uang yang sudah masuk
        $total_uang_masuk = $uang_lama + $uang_baru;

        // 5. DETEKSI & HAPUS FILE FISIK (Langkah khusus untuk Meja QR)
        // Jika ada nama file gambar dan filenya beneran ada di laptop, hapus!
        if ($order->bukti_bayar && $order->bukti_bayar !== 'DITOLAK' && file_exists(public_path($order->bukti_bayar))) {
            unlink(public_path($order->bukti_bayar));
        }

        // 6. Perbarui data di dalam tabel database
        $order->status_pembayaran = 'Kurang Bayar';
        $order->uang_bayar = $total_uang_masuk; // Simpan hasil penjumlahannya!
        $order->kembalian = 0;
        $order->metode_pembayaran = 'QRIS';

        // Kosongkan nama file.
        // Efek di Meja QR: Form upload HP pembeli terbuka lagi.
        // Efek di Prasmanan: Tidak ada efek samping (karena aslinya memang null).
        $order->bukti_bayar = null;

        // 7. Simpan perubahan
        $order->save();

        return back()->with('error', 'Pesanan Meja #' . $order->no_meja . ' ditandai KURANG BAYAR! (Total Masuk: Rp ' . number_format($total_uang_masuk, 0, ',', '.') . '). Silakan lunasi sisanya.');
    }

    // =======================================================
    // FUNGSI GABUNG TAGIHAN PRASMANAN
    // =======================================================
    public function mergeBill(Request $request)
    {
        $no_meja = $request->no_meja;
        $total_prasmanan = $request->total_prasmanan;

        // 1. Validasi murni hanya untuk nomor meja
        $request->validate([
            'no_meja' => 'required|numeric'
        ]);

        // 2. Cek Keamanan: Jangan sampai pesanan kosong
        if ($total_prasmanan <= 0 && !$request->filled('minuman_id')) {
            return back()->with('error', 'Pesanan kosong! Hitung nominal prasmanan atau pilih minuman.');
        }

        // 3. Cek apakah meja tersebut sudah ada transaksi aktif yang belum bayar
        $order = Order::where('no_meja', $no_meja)->where('status_pembayaran', 'Belum Bayar')->first();

        if ($order) {
            // Jika meja sudah ada pesanan (misal dari QR), langsung akumulasikan total harganya
            $order->total_harga += $total_prasmanan;


            $order->metode_pembayaran = $request->metode_pembayaran ?? 'Tunai';

            $order->save();
            $targetOrder = $order;

            // Buat pesan flash khusus jika gabung dengan pesanan QR
            if ($order->tipe_pesanan != 'Prasmanan Kasir') {
                return redirect()->route('kasir.meja')->with('success', 'Harga ditambahkan ke pesanan QR Meja ' . $no_meja);
            }

        } else {
            // Jika meja kosong, buat baris transaksi baru
            $targetOrder = new Order();
            $targetOrder->no_meja = $no_meja;
            $targetOrder->nama_pelanggan = 'Pelanggan Kasir';
            $targetOrder->tipe_pesanan = 'Prasmanan Kasir';
            $targetOrder->metode_pembayaran = $request->metode_pembayaran ?? 'Tunai';
            $targetOrder->total_harga = $total_prasmanan;
            $targetOrder->status_pembayaran = 'Belum Bayar';
            $targetOrder->status_pesanan = 'Menunggu';
            $targetOrder->save();
        }

        // 4. Catat item "Seblak Prasmanan" ke detail pesanan (Hanya jika harganya > 0)
        if ($total_prasmanan > 0) {
            OrderDetail::create([
                'order_id' => $targetOrder->id,
                'menu_name' => 'Seblak Prasmanan (Kasir)',
                'harga' => $total_prasmanan,
                'qty' => 1
            ]);
        }

        // 5. Tambah minuman jika dipilih
        if ($request->filled('minuman_id') && $request->filled('qty_minuman')) {
            $minuman = Menu::find($request->minuman_id);
            if ($minuman) {
                // Tambah harga minuman ke total_harga
                $targetOrder->total_harga += ($minuman->harga * $request->qty_minuman);
                $targetOrder->save();

                // Simpan ke detail pesanan
                OrderDetail::create([
                    'order_id' => $targetOrder->id,
                    'menu_name' => $minuman->nama_menu,
                    'harga' => $minuman->harga,
                    'qty' => $request->qty_minuman,
                ]);
            }
        }

        return back()->with('success', 'Pesanan berhasil ditambahkan ke Meja ' . $no_meja);
    }

    public function cetakStruk($id)
    {
        $order = Order::with('details')->findOrFail($id);
        return view('kasir.struk', compact('order'));
    }

    public function selesaiPesanan($id)
    {
        $order = Order::findOrFail($id);
        $order->status_pesanan = 'Selesai';
        $order->save();

        return back()->with('success', 'Pesanan berhasil diselesaikan dan diarsip!');
    }


   // =======================================================
    // FUNGSI RIWAYAT KASIR (MENAMPILKAN DATA 1 BULAN TERAKHIR)
    // =======================================================
    public function riwayat(Request $request)
    {
        // Tangkap inputan tanggal dari form kasir
        $tanggal_filter = $request->get('tanggal');

        // Siapkan Query dasar
        $queryPrasmanan = Order::with('details')->where('status_pesanan', 'Selesai')->where('tipe_pesanan', 'Prasmanan Kasir');
        $queryQr = Order::with('details')->where('status_pesanan', 'Selesai')->where('tipe_pesanan', '!=', 'Prasmanan Kasir');

        // Jika kasir memilih tanggal, cari yang sesuai tanggal itu saja
        if ($tanggal_filter) {
            $queryPrasmanan->whereDate('updated_at', $tanggal_filter);
            $queryQr->whereDate('updated_at', $tanggal_filter);
        } else {
            // Jika tidak milih tanggal, tampilkan default 30 hari terakhir seperti biasa
            $batasBulan = \Carbon\Carbon::now()->subDays(30);
            $queryPrasmanan->whereDate('updated_at', '>=', $batasBulan);
            $queryQr->whereDate('updated_at', '>=', $batasBulan);
        }

        // Eksekusi data ke database
        $orders_prasmanan = $queryPrasmanan->orderBy('updated_at', 'desc')->get();
        $orders_qr = $queryQr->orderBy('updated_at', 'desc')->get();

        return view('admin.riwayat', compact('orders_prasmanan', 'orders_qr'));
    }

    public function kasirDashboard()
    {
        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');

        $totalTunai = \App\Models\Order::whereDate('created_at', $hariIni)
                        ->where('status_pembayaran', 'Lunas')
                        ->where('metode_pembayaran', 'Tunai')
                        ->sum('total_harga');

        $totalQris = \App\Models\Order::whereDate('created_at', $hariIni)
                        ->where('status_pembayaran', 'Lunas')
                        ->where('metode_pembayaran', 'QRIS')
                        ->sum('total_harga');

        $totalSystem = $totalTunai + $totalQris;

        // CEK APAKAH SUDAH TUTUP KASIR HARI INI
        $sudahTutup = \App\Models\ClosingShift::whereDate('tanggal', $hariIni)->exists();

        return view('admin.dashboard', compact('totalTunai', 'totalQris', 'totalSystem', 'sudahTutup'));
    }

    public function prosesTutupKasir(Request $request)
    {
        // 1. Validasi input, pastikan menangkap input tunai yang baru
        $request->validate([
            'uang_fisik' => 'required|numeric|min:0',
            'total_tunai_hidden' => 'required|numeric' // Berubah sesuai input di Blade
        ]);

        $totalTunaiSistem = $request->total_tunai_hidden;
        $uangFisik = $request->uang_fisik;

        // 2. Selisih = Uang Fisik di Laci - Ekspektasi Uang Tunai
        $selisih = $uangFisik - $totalTunaiSistem;

        // 3. Simpan ke Database
        // Catatan: Tetap menggunakan kolom 'total_system' agar tidak perlu mengubah struktur database,
        // tapi nilai yang dimasukkan murni hanya total pendapatan tunai.
        \App\Models\ClosingShift::create([
            'user_id' => \Auth::id(),
            'tanggal' => \Carbon\Carbon::now()->format('Y-m-d'),
            'total_system' => $totalTunaiSistem,
            'uang_fisik' => $uangFisik,
            'selisih' => $selisih
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan data sesi yang cocok
        return back()->with([
            'success_closing' => true,
            'totalTunaiSystem' => $totalTunaiSistem, // Harus sama dengan panggilan di Blade
            'uangFisik' => $uangFisik,
            'selisih' => $selisih,
            'tglHariIni' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);
    }

    public function tolakBukti($id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // Hapus file gambar lama jika ada agar server tidak kepenuhan file sampah
        if ($order->bukti_bayar && $order->bukti_bayar !== 'DITOLAK' && file_exists(public_path($order->bukti_bayar))) {
            unlink(public_path($order->bukti_bayar));
        }


        $order->bukti_bayar = 'DITOLAK';
        $order->save();

        return back()->with('error', 'Bukti pembayaran ditolak. Pelanggan dapat mengunggah ulang bukti yang benar.');
    }
}
