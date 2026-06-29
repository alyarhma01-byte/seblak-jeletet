<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingShift extends Model
{
    use HasFactory;

    protected $table = 'closing_shifts';
    protected $fillable = ['user_id', 'tanggal', 'total_system', 'uang_fisik', 'selisih'];
}
