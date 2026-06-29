<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function dashboard()
    {
        // Ambil bulan dan tahun saat ini
        $bulanIni = \Carbon\Carbon::now()->month;
        $tahunIni = \Carbon\Carbon::now()->year;

        // 1. METRIK KEUANGAN ATAS (Hanya Bulan Ini)
        $totalPendapatan = Order::where('status_pembayaran', 'Lunas')
                                ->whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->sum('total_harga');

        $pendapatanTunai = Order::where('status_pembayaran', 'Lunas')
                                ->where('metode_pembayaran', 'Tunai')
                                ->whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->sum('total_harga');

        $pendapatanQris  = Order::where('status_pembayaran', 'Lunas')
                                ->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])
                                ->whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->sum('total_harga');

        $totalTransaksi  = Order::where('status_pembayaran', 'Lunas')
                                ->whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->count();

        // 🔥 FITUR BARU: Hitung JUMLAH struk/transaksi untuk grafik Donat (Hanya Bulan Ini)
        $transaksiTunai = Order::where('status_pembayaran', 'Lunas')
                               ->where('metode_pembayaran', 'Tunai')
                               ->whereMonth('created_at', $bulanIni)
                               ->whereYear('created_at', $tahunIni)
                               ->count();

        $transaksiQris  = Order::where('status_pembayaran', 'Lunas')
                               ->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])
                               ->whereMonth('created_at', $bulanIni)
                               ->whereYear('created_at', $tahunIni)
                               ->count();

        // PERBAIKAN: Baca 'QRIS' atau 'Midtrans' agar aman jika ada data lama/baru
        $pendapatanQris  = Order::where('status_pembayaran', 'Lunas')
                                ->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])
                                ->sum('total_harga');

        $totalTransaksi  = Order::where('status_pembayaran', 'Lunas')->count();

        // 🔥 FITUR BARU: Hitung JUMLAH struk/transaksi untuk grafik Donat
        $transaksiTunai = Order::where('status_pembayaran', 'Lunas')->where('metode_pembayaran', 'Tunai')->count();
        $transaksiQris  = Order::where('status_pembayaran', 'Lunas')->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])->count();

        // 2. TRANSAKSI TERBARU (5 Data Kanan)
        $recentOrders = Order::where('status_pembayaran', 'Lunas')->latest('updated_at')->take(5)->get();

        // 3. PINTASAN MANAJEMEN
        $totalMenu = class_exists(Menu::class) ? Menu::count() : 0;
        $totalKategori = class_exists(Category::class) ? Category::count() : 0;

        // =======================================================
        // 4. LOGIKA DATA GRAFIK (CHART) DINAMIS BULANAN
        // =======================================================
        $currentYear = date('Y');

        $monthlySales = Order::select(
            DB::raw('MONTH(updated_at) as month'),
            'metode_pembayaran',
            DB::raw('SUM(total_harga) as total')
        )
        ->where('status_pembayaran', 'Lunas')
        ->whereYear('updated_at', $currentYear)
        ->groupBy('month', 'metode_pembayaran')
        ->get();

        $trendBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataTunai = array_fill(0, 12, 0);
        $dataQris = array_fill(0, 12, 0);

        foreach ($monthlySales as $sale) {
            $monthIndex = $sale->month - 1;
            if ($sale->metode_pembayaran == 'Tunai') {
                $dataTunai[$monthIndex] = $sale->total;
            } else {
                $dataQris[$monthIndex] = $sale->total;
            }
        }

        // 🔥 PERBAIKAN: Masukkan $transaksiTunai dan $transaksiQris ke compact
        return view('owner.dashboard', compact(
            'totalPendapatan', 'pendapatanTunai', 'pendapatanQris', 'totalTransaksi',
            'transaksiTunai', 'transaksiQris',
            'recentOrders', 'totalMenu', 'totalKategori',
            'trendBulan', 'dataTunai', 'dataQris'
        ));
    }


    // =======================================================
    // FUNGSI LAPORAN DENGAN FILTER RENTANG TANGGAL & METODE PEMBAYARAN
    // =======================================================
    public function laporan(Request $request)
    {
        $startDate = $request->get('start_date', \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', \Carbon\Carbon::today()->format('Y-m-d'));

        // 🔥 FITUR BARU: Tangkap pilihan metode pembayaran
        $metodePembayaran = $request->get('metode_pembayaran', 'Semua');

        // Query Pemasukan Utama (Saring berdasarkan tanggal)
        $baseQuery = Order::where('status_pembayaran', 'Lunas')
                          ->whereDate('created_at', '>=', $startDate)
                          ->whereDate('created_at', '<=', $endDate);

        // 🔥 FITUR BARU: Saring berdasarkan metode pembayaran (jika dipilih)
        if ($metodePembayaran == 'Tunai') {
            $baseQuery->where('metode_pembayaran', 'Tunai');
        } elseif ($metodePembayaran == 'QRIS') {
            $baseQuery->whereIn('metode_pembayaran', ['QRIS', 'Midtrans']);
        }

        $totalOmzet = (clone $baseQuery)->sum('total_harga');

        // Sengaja dibedakan querynya untuk dashboard kecil di atas tabel agar tetap akurat
        $totalTunai = (clone $baseQuery)->where('metode_pembayaran', 'Tunai')->sum('total_harga');
        $totalQris  = (clone $baseQuery)->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])->sum('total_harga');

        // Pisahkan data Prasmanan dan QR untuk Tabel
        $ordersPrasmanan = (clone $baseQuery)->with('details')->where('tipe_pesanan', 'Prasmanan Kasir')->orderBy('created_at', 'desc')->get();
        $ordersQr = (clone $baseQuery)->with('details')->where('tipe_pesanan', '!=', 'Prasmanan Kasir')->orderBy('created_at', 'desc')->get();

        // Query Pengeluaran
        $pengeluaran = \App\Models\Pengeluaran::whereDate('created_at', '>=', $startDate)
                                              ->whereDate('created_at', '<=', $endDate)
                                              ->orderBy('created_at', 'desc')
                                              ->get();

        // Ambil data biaya dengan fleksibel mencegah error nama kolom
        $totalPengeluaran = $pengeluaran->sum(function($item) {
            return $item->total_biaya ?? $item->nominal ?? 0;
        });

        // Laba Bersih
        $labaBersih = $totalOmzet - $totalPengeluaran;

        // Tutup Kasir
        $closingShifts = \App\Models\ClosingShift::whereDate('tanggal', '>=', $startDate)
                                                 ->whereDate('tanggal', '<=', $endDate)
                                                 ->orderBy('tanggal', 'desc')
                                                 ->get();

        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartDataTunai = [];
        $chartDataQris = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');
            $chartDataTunai[] = Order::where('status_pembayaran', 'Lunas')->where('metode_pembayaran', 'Tunai')->whereDate('created_at', $formattedDate)->sum('total_harga');
            $chartDataQris[] = Order::where('status_pembayaran', 'Lunas')->whereIn('metode_pembayaran', ['QRIS', 'Midtrans'])->whereDate('created_at', $formattedDate)->sum('total_harga');
        }

        return view('owner.laporan', compact(
            'totalOmzet', 'totalTunai', 'totalQris', 'ordersPrasmanan', 'ordersQr',
            'pengeluaran', 'totalPengeluaran', 'labaBersih',
            'closingShifts',
            'chartLabels', 'chartDataTunai', 'chartDataQris', 'startDate', 'endDate'
        ));
    }

    // =======================================================
    // FUNGSI EKSPOR LAPORAN (PDF & EXCEL)
    // =======================================================
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', \Carbon\Carbon::today()->format('Y-m-d'));
        $metodePembayaran = $request->get('metode_pembayaran', 'Semua');
        $type = $request->get('type', 'pdf'); // Tangkap apakah klik excel atau pdf

        // Filter Pemasukan sesuai request
        $query = Order::with('details')->where('status_pembayaran', 'Lunas')
                       ->whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate);

        if ($metodePembayaran == 'Tunai') {
            $query->where('metode_pembayaran', 'Tunai');
        } elseif ($metodePembayaran == 'QRIS') {
            $query->whereIn('metode_pembayaran', ['QRIS', 'Midtrans']);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $totalOmzet = $orders->sum('total_harga');

        // Pengeluaran
        $pengeluaran = \App\Models\Pengeluaran::whereDate('created_at', '>=', $startDate)
                                              ->whereDate('created_at', '<=', $endDate)
                                              ->orderBy('created_at', 'desc')->get();

        $totalPengeluaran = $pengeluaran->sum(function($item) {
            return $item->total_biaya ?? $item->nominal ?? 0;
        });

        $labaBersih = $totalOmzet - $totalPengeluaran;

        // 🔥 JIKA TOMBOL EXCEL DIKLIK
        if ($type === 'excel') {
            $fileName = "Laporan_Keuangan_Seblak_{$startDate}_sd_{$endDate}.csv";

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            // Tulis dan susun data excelnya
            $callback = function() use($orders, $totalOmzet, $totalPengeluaran, $labaBersih, $startDate, $endDate, $metodePembayaran) {
                $file = fopen('php://output', 'w');

                // Judul File Excel
                fputcsv($file, ['LAPORAN KEUANGAN SEBLAK JELETET MEDAN']);
                fputcsv($file, ['Periode', $startDate . ' s/d ' . $endDate]);
                fputcsv($file, ['Metode Filter', $metodePembayaran]);
                fputcsv($file, ['']); // Baris Kosong

                // Ringkasan Laba Bersih
                fputcsv($file, ['RINGKASAN LABA BERSIH']);
                fputcsv($file, ['Total Pemasukan (Omzet)', 'Rp ' . $totalOmzet]);
                fputcsv($file, ['Total Pengeluaran (Beban)', '- Rp ' . $totalPengeluaran]);
                fputcsv($file, ['LABA BERSIH', 'Rp ' . $labaBersih]);
                fputcsv($file, ['']); // Baris Kosong

                // Rincian Tabel Transaksi
                fputcsv($file, ['RINCIAN PEMASUKAN TRANSAKSI']);
                fputcsv($file, ['Tanggal', 'Waktu', 'ID Transaksi', 'Tipe Pesanan', 'Pelanggan', 'Metode Bayar', 'Total Tagihan (Rp)']);

                foreach ($orders as $order) {
                    fputcsv($file, [
                        \Carbon\Carbon::parse($order->created_at)->format('d/m/Y'),
                        \Carbon\Carbon::parse($order->created_at)->format('H:i'),
                        '#INV-' . $order->id,
                        $order->tipe_pesanan,
                        $order->nama_pelanggan,
                        $order->metode_pembayaran,
                        $order->total_harga
                    ]);
                }

                fclose($file);
            };

            // Stream langsung jadi file download
            return response()->stream($callback, 200, $headers);
        }

        // JIKA TOMBOL PDF YANG DIKLIK (DEFAULT VIEW)
        return view('owner.cetak_laporan', compact(
            'orders', 'totalOmzet', 'pengeluaran', 'totalPengeluaran', 'labaBersih', 'startDate', 'endDate'
        ));
    }
}
