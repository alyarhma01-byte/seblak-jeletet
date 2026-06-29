<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;
use App\Models\AddOn;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Kategori Dasar
        $seblak = Category::create(['nama_kategori' => 'Seblak']);
        $basoAci = Category::create(['nama_kategori' => 'Baso Aci']);
        $mieJebew = Category::create(['nama_kategori' => 'Mie Jebew']);
        $snack = Category::create(['nama_kategori' => 'Snack']);
        $minuman = Category::create(['nama_kategori' => 'Minuman']);
        $paketAyam = Category::create(['nama_kategori' => 'Paket Nasi Ayam']);
        $wontonCat = Category::create(['nama_kategori' => 'Wonton']);

        // 2. Input Menu SEBLAK (15K) - Deskripsi sesuai foto
        $isiSeblak = [
            1 => "Seblak, Makaroni, Baso Ayam, Ker. Siomay, Telur",
            2 => "Seblak, Indomie, Baso Ayam, Ker. Siomay, Telur",
            3 => "Seblak, Indomie, Baso Udang, Sosis, Telur",
            4 => "Seblak, Tahu Walik, Baso Udang, Sosis, Telur",
            5 => "Seblak, Batagor, Makaroni, Baso Ayam, Telur",
            6 => "Seblak, Baso Ayam, Baso Udang, Sosis, Telur",
        ];

        foreach ($isiSeblak as $key => $desc) {
            Menu::create([
                'category_id' => $seblak->id,
                'nama_menu' => "Seblak $key",
                'deskripsi' => $desc,
                'harga' => 15000,
                'foto' => "seblak$key.jpg",
                'has_level' => true,
                'has_kencur' => true,
                'has_kuah' => true
            ]);
        }

        // 3. Input Menu BASO ACI (15K) - Deskripsi sesuai foto
        $isiBasoAci = [
            1 => "Baso Aci, Makaroni, Baso Ayam, Sosis, Pilus",
            2 => "Baso Aci, Makaroni, B. Udang, B. Ayam, Pilus",
            3 => "Baso Aci, Indomie, Baso Ayam, Sosis, Pilus",
            4 => "Baso Aci, Indomie, Baso Udang, Sosis, Pilus",
            5 => "Baso Aci, Batagor, Baso Ayam, Sosis, Pilus",
            6 => "Baso Aci, Batagor, Cuanki, Ker. Siomay, Pilus",
        ];

        foreach ($isiBasoAci as $key => $desc) {
            Menu::create([
                'category_id' => $basoAci->id,
                'nama_menu' => "Baso Aci $key",
                'deskripsi' => $desc,
                'harga' => 15000,
                'foto' => "basoaci$key.jpg",
                'has_level' => true,
                'has_kencur' => false, // Baso aci biasanya gak pakai kencur/kuah seblak
                'has_kuah' => false
            ]);
        }

        // 4. Input MIE JEBEW & WONTON
        Menu::create([
            'category_id' => $mieJebew->id,
            'nama_menu' => 'Mie Jebew Original',
            'deskripsi' => 'Mie pedas khas Garut dengan bumbu rahasia',
            'harga' => 10000,
            'foto' => 'jebew_ori.jpg',
            'has_level' => true
        ]);
        Menu::create([
            'category_id' => $wontonCat->id,
            'nama_menu' => 'Wonton Chili Oil',
            'deskripsi' => 'Pangsit ayam premium dengan bumbu chili oil pedas gurih',
            'harga' => 10000,
            'foto' => 'wonton_co.jpg',
            'has_level' => true
        ]);

        // 5. Input PAKET NASI AYAM (15K + FREE ES TEH)
        $daftarPaket = ['Cabe Ijo', 'Geprek', 'Telur Dadar', 'Saus Keju', 'Saus Sadis', 'Saus BBQ', 'Lada Hitam', 'Saus Mentai'];
        foreach ($daftarPaket as $p) {
            Menu::create([
                'category_id' => $paketAyam->id,
                'nama_menu' => "Nasi Ayam $p",
                'deskripsi' => "Paket kenyang sudah termasuk FREE Es Teh Melati",
                'harga' => 15000,
                'foto' => 'paket_ayam.jpg'
            ]);
        }

        // 6. Input TOPPING (Add-ons)
        $topings = [
            ['Sosis', 3000], ['Fish Roll', 3000], ['Ceker (2)', 3000],
            ['Bakso Jumbo', 5000], ['Tulang Ayam', 4000], ['Chikuwa', 2000],
            ['Pilus', 2000], ['Kerupuk Cuanki', 2000]
        ];
        foreach ($topings as $t) {
            AddOn::create(['nama_addon' => $t[0], 'harga' => $t[1]]);
        }
    }
}
