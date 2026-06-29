<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // 1. Ambil data JSON dari Frontend
        $cartData = json_decode($request->cart_data, true);

        if (!$cartData || count($cartData) == 0) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        // 2. Hitung Total Harga Dasar (Murni harga makanan saja)
        $totalHarga = 0;
        foreach ($cartData as $item) {
            $totalHarga += ($item['price'] * $item['qty']);
        }

        $metodePembayaran = $request->metode_pembayaran ?? 'Tunai';

        // 3. Simpan ke Tabel Orders
        $order = new Order();
        $order->no_meja = session('meja', 'Kasir');
        $order->nama_pelanggan = session('nama_pelanggan', 'Pelanggan');
        $order->tipe_pesanan = session('tipe_pesanan', 'Makan Sini');
        $order->metode_pembayaran = $metodePembayaran;
        $order->catatan = $request->catatan_payload;
        $order->total_harga = $totalHarga; // Langsung simpan total harga bersih
        $order->status_pembayaran = 'Belum Bayar';
        $order->status_pesanan = 'Menunggu';
        $order->save();

        // 4. Simpan ke Tabel OrderDetails (Menu yang dibeli)
        foreach ($cartData as $item) {
            $detail = new OrderDetail();
            $detail->order_id = $order->id;
            $detail->menu_name = $item['name'];
            $detail->harga = $item['price'];
            $detail->qty = $item['qty'];
            $detail->level = $item['optLevel'] ?? null;
            $detail->kencur = $item['optKencur'] ?? null;
            $detail->kuah = $item['optKuah'] ?? null;
            $detail->save();
        }

        // Langsung arahkan ke halaman nota/success
        return redirect()->route('order.success', $order->id);
    }

    public function success($id) {
        $order = Order::find($id);

        if (!$order) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan');
        }

        $details = OrderDetail::where('order_id', $id)->get();
        return view('order.success', compact('order', 'details'));
    }

    // ==================================================
    // FUNGSI MENERIMA UPLOAD FOTO BUKTI BAYAR QRIS STATIS
    // ==================================================
    public function uploadBukti(Request $request, $id)
    {
        // 1. Validasi file gambar yang diupload (Perhatikan: namanya jadi bukti_bayar)
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'bukti_bayar.required' => 'Mohon pilih file bukti pembayaran terlebih dahulu.',
            'bukti_bayar.image' => 'File harus berupa gambar.',
        ]);

        // 2. Cari data pesanan berdasarkan ID
        $pesanan = \App\Models\Order::findOrFail($id);

        // 3. Proses Upload File ke public/uploads/bukti_bayar
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');

            // Format nama file: timestamp_NamaAsliFile.jpg
            $nama_file = time() . '_' . $file->getClientOriginalName();

            // Tentukan lokasi folder: public/uploads/bukti_bayar
            $tujuan_upload = public_path('uploads/bukti_bayar');

            // Pindahkan file ke folder tujuan
            $file->move($tujuan_upload, $nama_file);

            // 4. Update data pesanan di database
            $pesanan->bukti_bayar = 'uploads/bukti_bayar/' . $nama_file;
            $pesanan->save();
        }

        // 5. Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi kasir.');
    }

    // Menampilkan struk untuk pelanggan (Bebas Akses)
    public function cetakStrukPelanggan($id)
    {
        // Cari data pesanan berdasarkan ID
        $order = \App\Models\Order::findOrFail($id);

        // Cari detail item apa saja yang dipesan
        $details = \App\Models\OrderDetail::where('order_id', $id)->get();

        // Tampilkan halaman struk (menggunakan tampilan struk yang sama dengan kasir)
        return view('kasir.struk', compact('order', 'details'));
    }
}
