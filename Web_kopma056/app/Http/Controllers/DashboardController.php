<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik Utama
        $totalPendapatan = Transaksi::sum('total_bayar');
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $totalAnggota = Anggota::where('status', 'aktif')->count();
        $totalSemuaAnggota = Anggota::count();

        // 2. Daftar Transaksi Penjualan Terkini (dengan relasi)
        $transaksiTerkini = Transaksi::with(['anggota', 'kasir', 'details.barang'])
            ->orderBy('tgl_transaksi', 'desc')
            ->take(10)
            ->get();

        // 3. Verifikasi Koneksi Database MySQL
        $dbConnected = false;
        $dbName = config('database.connections.mysql.database');
        $dbHost = config('database.connections.mysql.host');
        $dbVersion = '';

        try {
            DB::connection()->getPdo();
            $dbConnected = true;
            $versionResult = DB::select("SELECT VERSION() as ver");
            $dbVersion = $versionResult[0]->ver ?? 'MySQL / MariaDB';
        } catch (\Exception $e) {
            $dbConnected = false;
        }

        // 4. Barang dengan stok minim (< 20) untuk informasi inventaris
        $stokMenipis = Barang::with('kategori')
            ->where('stok', '<', 25)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPendapatan',
            'totalBarang',
            'totalStok',
            'totalAnggota',
            'totalSemuaAnggota',
            'transaksiTerkini',
            'dbConnected',
            'dbName',
            'dbHost',
            'dbVersion',
            'stokMenipis'
        ));
    }
}
