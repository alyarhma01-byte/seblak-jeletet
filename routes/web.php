<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerController;

// ==========================================
// AREA PELANGGAN (BEBAS AKSES / TANPA LOGIN)
// ==========================================

// 1. Halaman Welcome (Scan QR)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// 2. Proses Simpan Nama & Meja
Route::post('/start-order', [WelcomeController::class, 'store'])->name('start.order');

// 3. Halaman Katalog Menu (PERBAIKAN: Diubah jadi fungsi 'katalog' agar tidak nabrak Admin)
Route::get('/menu', [MenuController::class, 'katalog'])->name('menu.index');

// 4. Proses Simpan ke Database
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// 5. Halaman Sukses / Nota Pesanan
Route::get('/order/success/{id}', [CartController::class, 'success'])->name('order.success');
Route::post('/pesanan/{id}/upload-bukti', [CartController::class, 'uploadBukti'])->name('pesanan.upload_bukti');
// Rute untuk pelanggan melihat struk tanpa perlu login
Route::get('/pesanan/struk/{id}', [App\Http\Controllers\CartController::class, 'cetakStrukPelanggan'])->name('pelanggan.struk');
Route::get('/kasir/struk/{id}', [AdminController::class, 'cetakStruk'])->name('kasir.struk');

// ==========================================
// AREA AUTH (LOGIN & LOGOUT)
// ==========================================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// AREA KASIR (DIGEMBOK: WAJIB LOGIN & ROLE KASIR)
// ==========================================
Route::middleware(['auth', 'role:kasir,pemilik'])->group(function () {

    // Dashboard & Kasir Utama
    Route::get('/kasir/dashboard', [AdminController::class, 'kasirDashboard'])->name('kasir.dashboard');
    Route::get('/kasir', [AdminController::class, 'index'])->name('kasir.index'); // Halaman Prasmanan
    Route::get('/kasir/meja', [AdminController::class, 'pesananMeja'])->name('kasir.meja'); // Halaman QR Meja Baru
    Route::post('/kasir/kurang-bayar/{id}', [AdminController::class, 'kurangBayar'])->name('kasir.kurang_bayar');

    // Proses Transaksi
    Route::post('/kasir/lunas/{id}', [AdminController::class, 'lunas'])->name('kasir.lunas');
    Route::post('/kasir/merge', [AdminController::class, 'mergeBill'])->name('kasir.merge');
    Route::post('/kasir/selesai/{id}', [AdminController::class, 'selesaiPesanan'])->name('kasir.selesai');
    Route::post('/kasir/generate-qris/{id}', [AdminController::class, 'generateQris'])->name('kasir.generate_qris');
    Route::post('/kasir/proses-bayar/{id}', [AdminController::class, 'prosesPembayaran'])->name('kasir.proses_bayar');

    // Riwayat & Tutup Kasir
    Route::get('/kasir/riwayat', [AdminController::class, 'riwayat'])->name('kasir.riwayat');

    Route::post('/kasir/tolak-bukti/{id}', [AdminController::class, 'tolakBukti'])->name('kasir.tolak_bukti');
    Route::post('/kasir/proses-tutup', [AdminController::class, 'prosesTutupKasir'])->name('kasir.proses_tutup');

});


// ==========================================
// AREA PEMILIK (DIGEMBOK: WAJIB LOGIN & ROLE PEMILIK)
// ==========================================

Route::prefix('pemilik')->name('owner.')->middleware(['auth', 'role:pemilik'])->group(function () {

    // Rute utama dashboard pemilik
    Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');

    // RUTE MANAJEMEN MENU (PEMILIK)
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/kategori', [App\Http\Controllers\CategoryController::class, 'index'])->name('kategori');
    Route::post('/kategori', [App\Http\Controllers\CategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('kategori.destroy');

    // RUTE PENGELUARAN (PEMILIK)
    Route::get('/pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::post('/pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'store'])->name('pengeluaran.store');

    // TAMBAHKAN BARIS INI UNTUK FITUR EDIT PENGELUARAN:
    Route::put('/pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'update'])->name('pengeluaran.update');

    Route::delete('/pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // RUTE MANAJEMEN MEJA (PEMILIK)
    Route::get('/meja', [App\Http\Controllers\MejaController::class, 'index'])->name('meja');
    Route::post('/meja', [App\Http\Controllers\MejaController::class, 'store'])->name('meja.store');
    Route::delete('/meja/{id}', [App\Http\Controllers\MejaController::class, 'destroy'])->name('meja.destroy');
    Route::put('/meja/{id}', [App\Http\Controllers\MejaController::class, 'update'])->name('meja.update');


    // Pastikan baris ini ditaruh di dalam grup rute owner/admin milikmu
    Route::get('/laporan', [App\Http\Controllers\OwnerController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export', [App\Http\Controllers\OwnerController::class, 'export'])->name('laporan.export');

});
