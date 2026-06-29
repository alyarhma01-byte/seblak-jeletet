<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. BUAT KATEGORI
        $catSeblak = Category::create(['nama_kategori' => 'Seblak Jeletet']);
        $catBaso = Category::create(['nama_kategori' => 'Baso Aci']);
        $catMie = Category::create(['nama_kategori' => 'Mie Jebew']);
        $catPaket = Category::create(['nama_kategori' => 'Paket Nasi']);
        $catSnack = Category::create(['nama_kategori' => 'Snack & Toping']);
        $catMinuman = Category::create(['nama_kategori' => 'Minuman']);

        // 2. BUAT MENU SEBLAK (Bisa pilih Kencur & Kuah)
        Menu::create(['category_id' => $catSeblak->id, 'nama_menu' => 'Seblak Original', 'harga' => 15000, 'deskripsi' => 'Kerupuk, Makaroni, Mie, Sosis, Telur', 'foto' => '']);
        Menu::create(['category_id' => $catSeblak->id, 'nama_menu' => 'Seblak Seafood', 'harga' => 25000, 'deskripsi' => 'Isian ori + Udang, Cumi, Dumpling Keju', 'foto' => '']);

        // 3. BUAT MENU BASO ACI (Bisa pilih Kencur & Kuah)
        Menu::create(['category_id' => $catBaso->id, 'nama_menu' => 'Baso Aci Tulang Rangu', 'harga' => 20000, 'deskripsi' => 'Baso aci tulang rangu, cuanki lidah, pilus cikur', 'foto' => '']);

        // 4. BUAT MENU MIE (Hanya pilih Level)
        Menu::create(['category_id' => $catMie->id, 'nama_menu' => 'Mie Jebew Spesial', 'harga' => 18000, 'deskripsi' => 'Mie pedas dengan taburan ayam cincang dan pangsit', 'foto' => '']);

        // 5. BUAT PAKET NASI (Otomatis dapat Es Teh Melati)
        Menu::create(['category_id' => $catPaket->id, 'nama_menu' => 'Paket Nasi Ayam Geprek', 'harga' => 25000, 'deskripsi' => 'Nasi putih + Ayam geprek paha/dada + Lalapan', 'foto' => '']);

        // 6. BUAT SNACK / TOPING (Langsung masuk keranjang)
        Menu::create(['category_id' => $catSnack->id, 'nama_menu' => 'Sosis Bratwurst', 'harga' => 5000, 'deskripsi' => 'Toping tambahan sosis besar', 'foto' => '']);
        Menu::create(['category_id' => $catSnack->id, 'nama_menu' => 'Dumpling Keju (3pcs)', 'harga' => 5000, 'deskripsi' => 'Toping dumpling isi keju lumer', 'foto' => '']);

        // 7. BUAT MINUMAN (Langsung masuk keranjang)
        Menu::create(['category_id' => $catMinuman->id, 'nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'deskripsi' => 'Es teh manis segar', 'foto' => '']);
        Menu::create(['category_id' => $catMinuman->id, 'nama_menu' => 'Es Jeruk Peras', 'harga' => 8000, 'deskripsi' => 'Jeruk peras asli pakai es', 'foto' => '']);
    }
}
