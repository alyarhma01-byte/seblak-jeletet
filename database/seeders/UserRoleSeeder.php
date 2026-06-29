<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Bos / Owner
        User::create([
            'name' => 'Bos Seblak',
            'email' => 'seblakjeletetmarelan@gmail.com',
            'password' => Hash::make('seblakjeletetmedan12'),
            'role' => 'pemilik',
        ]);

        // 2. Akun Kasir
        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@seblak.com',
            'password' => Hash::make('kasirseblak123'),
            'role' => 'kasir',
        ]);
    }
}
