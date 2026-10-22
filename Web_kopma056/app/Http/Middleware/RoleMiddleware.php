<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Administrator selalu memiliki hak akses penuh ke seluruh rute
        if ($userRole === 'admin' || in_array($userRole, $roles)) {
            return $next($request);
        }

        // Pengalihan hak akses spesifik per peran jika mencoba mengakses rute terlarang
        if ($userRole === 'anggota') {
            return redirect()->route('anggota.dashboard')->with('error', 'Akses dibatasi: Anda berada pada hak akses Anggota Koperasi.');
        } elseif ($userRole === 'kasir') {
            return redirect()->route('pos.index')->with('error', 'Akses dibatasi: Akun Kasir hanya berwenang pada modul kasir dan transaksi.');
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak untuk peran Anda.');
    }
}
