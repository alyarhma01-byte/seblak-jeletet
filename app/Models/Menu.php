<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Pastikan semua kolom boleh diisi
    protected $guarded = [];

    // JEMBATAN KE TABEL KATEGORI
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
