<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login split-screen KOMPA
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak valid.',
        ])->onlyInput('email');
    }

    /**
     * Quick-login untuk akun demo (Admin, Pengurus, Kasir, Anggota)
     */
    public function quickLogin(Request $request)
    {
        $roleMap = [
            'admin'    => ['email' => 'annisa@kopma.ac.id',  'password' => 'password123'],
            'pengurus' => ['email' => 'pengurus@kopma.ac.id', 'password' => 'pengurus123'],
            'kasir'    => ['email' => 'kasir@kopma.ac.id',   'password' => 'kasir123'],
            'anggota'  => ['email' => 'anggota@kopma.ac.id', 'password' => 'anggota123'],
        ];

        $role = $request->input('role');

        if (!array_key_exists($role, $roleMap)) {
            return back()->withErrors(['email' => 'Akun demo tidak ditemukan.']);
        }

        $creds = $roleMap[$role];
        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Gagal login demo. Jalankan seeder terlebih dahulu.']);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
