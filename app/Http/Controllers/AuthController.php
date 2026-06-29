<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Kalau sudah login, langsung usir ke halamannya masing-masing
        if (Auth::check()) {
            if (Auth::user()->role === 'pemilik') {
                return redirect()->route('owner.dashboard');
            }
            return redirect()->route('kasir.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. PASTIKAN VALIDASI PAKAI 'email'
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. KREDENSIAL PAKAI 'email' BUKAN 'username'
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // 3. JIKA BERHASIL LOGIN
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pengecekan Role & Kirim Pesan Notifikasi Sukses
            if (Auth::user()->role === 'pemilik') {
                return redirect()->route('owner.dashboard')->with('success', 'Berhasil Login! Selamat datang kembali, Bos');
            }

            // Default ke Kasir
            return redirect()->route('kasir.dashboard')->with('success', 'Berhasil Login! Selamat bekerja dan semangat, Kasir!');
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah, Min!',
        ])->onlyInput('email');
    }

    // =====================================================================
    // FUNGSI LOGOUT (DENGAN PESAN DINAMIS BERDASARKAN ROLE)
    // =====================================================================
    public function logout(Request $request)
    {
        // 1. TANGKAP ROLE SEBELUM LOGOUT
        // Kita harus simpan role-nya sekarang, karena setelah Auth::logout(), Auth::user() akan jadi null
        $role = Auth::check() ? Auth::user()->role : 'guest';

        // 2. SIAPKAN PESAN NOTIFIKASI
        $pesanLogout = 'Berhasil logout! Sampai jumpa lagi 👋'; // Pesan default

        if ($role === 'pemilik') {
            $pesanLogout = 'Berhasil logout! Selamat beristirahat, Bos';
        } elseif ($role === 'kasir') {
            $pesanLogout = 'Berhasil logout! Terima kasih atas kerja keras hari ini, Kasir';
        }

        // 3. PROSES PENGHANCURAN SESI (LOGOUT)
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. REDIRECT DENGAN PESAN YANG SUDAH DISIAPKAN
        return redirect()->route('login')->with('success', $pesanLogout);
    }
}
