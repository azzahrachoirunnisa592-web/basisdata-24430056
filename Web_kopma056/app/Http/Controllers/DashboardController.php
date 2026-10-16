<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $totalPendapatan  = Transaksi::sum('total_bayar');
        $totalBarang      = Barang::count();
        $totalStok        = Barang::sum('stok');
        $totalAnggota     = Anggota::where('status', 'aktif')->count();
        $totalSemuaAnggota = Anggota::count();

        // 2. Transaksi Terkini (10 terakhir)
        $transaksiTerkini = Transaksi::with(['anggota', 'kasir', 'details.barang'])
            ->orderBy('tgl_transaksi', 'desc')
            ->take(10)
            ->get();

        // 3. Verifikasi Koneksi Database MySQL
        $dbConnected = false;
        $dbName      = config('database.connections.mysql.database');
        $dbHost      = config('database.connections.mysql.host');
        $dbVersion   = '';

        try {
            DB::connection()->getPdo();
            $dbConnected = true;
            $versionResult = DB::select("SELECT VERSION() as ver");
            $dbVersion = $versionResult[0]->ver ?? 'MySQL / MariaDB';
        } catch (\Exception $e) {
            $dbConnected = false;
        }

        // 4. Stok Menipis (<= 10) untuk dashboard kasir
        $stokMenipis = Barang::with('kategori')
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->take(8)
            ->get();

        // 5. Penjualan 7 Hari Terakhir (untuk grafik kasir)
        $penjualan7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->toDateString();
            $omzet   = Transaksi::whereDate('tgl_transaksi', $tanggal)->sum('total_bayar');
            $jumlah  = Transaksi::whereDate('tgl_transaksi', $tanggal)->count();
            $penjualan7Hari[] = [
                'tanggal' => Carbon::now()->subDays($i)->translatedFormat('D d/m'),
                'omzet'   => $omzet,
                'jumlah'  => $jumlah,
            ];
        }

        // 6. Omzet hari ini
        $omzetHariIni = Transaksi::whereDate('tgl_transaksi', today())->sum('total_bayar');
        $transaksiHariIni = Transaksi::whereDate('tgl_transaksi', today())->count();

        // 7. Omzet bulan ini
        $omzetBulanIni = Transaksi::whereMonth('tgl_transaksi', now()->month)
            ->whereYear('tgl_transaksi', now()->year)
            ->sum('total_bayar');

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
            'stokMenipis',
            'penjualan7Hari',
            'omzetHariIni',
            'transaksiHariIni',
            'omzetBulanIni'
        ));
    }
}
