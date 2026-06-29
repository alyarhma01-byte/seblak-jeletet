<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel yang tepat sesuai migration kita
    protected $table = 'meja';

    // Kolom-kolom yang diizinkan untuk diisi data
    protected $fillable = [
        'nomor_meja',
        'status_meja',
        'link_qr_code',
    ];

    // Relasi: Satu meja bisa memiliki banyak pesanan (seperti di Class Diagram)
    public function orders()
    {
        return $this->hasMany(Order::class, 'meja_id');
    }
}
