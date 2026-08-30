<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. PROSES REGISTRASI
    public function register(Request $request)
    {
        // Validasi input data pendaftaran (email diganti nim)
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        // Simpan data ke database dengan default role 'pembeli'
        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);

        // Alur Baru: Setelah sukses daftar, arahkan ke halaman LOGIN dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
    }

    // 2. PROSES LOGIN MULTI-ROLE
    public function login(Request $request)
    {
        // Validasi input login menggunakan NIM
        $credentials = $request->validate([
            'nim' => 'required|string',
            'password' => 'required',
        ]);

        // Proses Autentikasi / Cek Akun berdasarkan NIM dan Password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil data user yang baru saja berhasil login
            $user = Auth::user();

            // Pengecekan Role untuk Pengalihan Halaman (Redirect)
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'panitia') {
                return redirect()->route('panitia.event');
            }

            // Jika rolenya 'pembeli', arahkan ke halaman pembeli
            return redirect()->route('pembeli.event');
        }

        // Jika NIM atau password salah, kembalikan ke halaman login dengan error berbasis field 'nim'
        return back()->withErrors([
            'nim' => 'NIM atau password yang Anda masukkan salah.',
        ])->onlyInput('nim');
    }

    // 3. PROSES LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
