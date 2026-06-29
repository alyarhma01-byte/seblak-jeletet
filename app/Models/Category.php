<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // Tambahkan ini: Satu kategori memiliki banyak menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
