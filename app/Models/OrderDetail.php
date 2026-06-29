<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BARIS INI ---
    // Ini ngasih izin ke Laravel untuk mengisi kolom-kolom ini secara otomatis
    protected $fillable = [
        'order_id',
        'menu_name',
        'harga',
        'qty',
        'level',   // Tambahkan ini jika di tabelmu ada kolom level
        'kencur',  // Tambahkan ini jika di tabelmu ada kolom kencur
        'kuah',    // Tambahkan ini jika di tabelmu ada kolom kuah
    ];
}
