<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartAnalysisController extends Controller
{
    /**
     * Halaman Analisis Cerdas (Smart Analysis)
     * — Peringatan otomatis, grafik ramalan omzet, skenario bulanan
     */
    public function index()
    {
        // ---- 1. Peringatan Kredit Macet ----
        $kreditMacet = Pinjaman::with('anggota')
            ->where('status', 'macet')
            ->get();

        // ---- 2. Stok Barang Menipis (<= 10) ----
        $stokKritis = Barang::with('kategori')
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->get();

        // ---- 3. SHU Belum Dibagi ----
        $shuBelumDibagi = DB::table('shu')
            ->where('status', 'belum_dibagi')
            ->select('tahun', DB::raw('count(*) as jumlah_anggota'), DB::raw('sum(total_shu) as total'))
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->get();

        // ---- 4. Data Penjualan 7 Hari untuk grafik ----
        $omzetHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->toDateString();
            $omzet = Transaksi::whereDate('tgl_transaksi', $tanggal)->sum('total_bayar');
            $omzetHarian[] = [
                'tanggal' => Carbon::now()->subDays($i)->translatedFormat('D, d M'),
                'omzet'   => $omzet,
            ];
        }

        // ---- 5. Omzet Bulanan (12 bulan terakhir) untuk skenario ----
        $omzetBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan  = Carbon::now()->subMonths($i);
            $omzet  = Transaksi::whereYear('tgl_transaksi', $bulan->year)
                ->whereMonth('tgl_transaksi', $bulan->month)
                ->sum('total_bayar');
            $omzetBulanan[] = [
                'label'  => $bulan->translatedFormat('M Y'),
                'omzet'  => $omzet,
                // Skenario: optimis +15%, moderat +5%, pesimis -10%
                'optimis'  => round($omzet * 1.15),
                'moderat'  => round($omzet * 1.05),
                'pesimis'  => round($omzet * 0.9),
            ];
        }

        // ---- 6. Total Pendapatan & Rata-rata Harian ----
        $totalOmzetBulanIni = Transaksi::whereMonth('tgl_transaksi', Carbon::now()->month)
            ->whereYear('tgl_transaksi', Carbon::now()->year)
            ->sum('total_bayar');

        $rataHarian = Carbon::now()->day > 0
            ? round($totalOmzetBulanIni / Carbon::now()->day)
            : 0;

        // Proyeksi akhir bulan
        $proyeksiBulanIni = $rataHarian * Carbon::now()->daysInMonth;

        return view('analisis.index', compact(
            'kreditMacet',
            'stokKritis',
            'shuBelumDibagi',
            'omzetHarian',
            'omzetBulanan',
            'totalOmzetBulanIni',
            'rataHarian',
            'proyeksiBulanIni'
        ));
    }
}
