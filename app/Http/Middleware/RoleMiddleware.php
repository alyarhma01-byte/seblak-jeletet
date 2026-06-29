<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // 🔥 PERBAIKAN: Gunakan titik tiga (...) untuk menangkap banyak role sekaligus
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user ADA DI DALAM daftar role yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {

            // Jika kasir mencoba masuk ke area khusus pemilik
            if (Auth::user()->role === 'kasir') {
                return redirect()->route('kasir.dashboard')->with('error', 'Akses Ditolak! Kamu bukan pemilik.');
            }

            // Jika pemilik mencoba masuk ke area murni kasir (opsional)
            if (Auth::user()->role === 'pemilik') {
                return redirect()->route('owner.dashboard')->with('error', 'Akses Ditolak! Ini halaman kasir.');
            }

            // Jika role tidak dikenali sama sekali
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk halaman ini.');
        }

        // 3. Jika rolenya cocok, silakan lewat!
        return $next($request);
    }
}
