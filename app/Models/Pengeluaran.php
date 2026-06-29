<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel
    protected $table = 'pengeluaran';

    // Kolom-kolom yang diizinkan untuk diisi data
    protected $fillable = [
        'nama_pengeluaran',
        'total_biaya',
        'tanggal',
    ];
}
