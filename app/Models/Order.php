<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = []; // Biar gampang nyimpan data

    // Tambahkan baris ini biar Pesanan nyambung ke Rincian Menu
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
