<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Shu;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AnggotaDashboardController extends Controller
{
    /**
     * Dashboard khusus anggota — simpanan, pinjaman, SHU, transaksi terakhir
     * Untuk demo: menampilkan data Annisa (ANG-056)
     */
    public function index()
    {
        // Ambil data anggota yang sedang login (demo: Annisa ANG-056)
        $anggota = Anggota::with(['simpanan', 'pinjaman', 'shu'])
            ->find('ANG-056');

        if (!$anggota) {
            return redirect()->route('dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }

        // --- Simpanan ---
        $simpananPokok    = $anggota->simpanan->where('jenis_simpanan', 'pokok')->sum('nominal');
        $simpananWajib    = $anggota->simpanan->where('jenis_simpanan', 'wajib')->sum('nominal');
        $simpananSukarela = $anggota->simpanan->where('jenis_simpanan', 'sukarela')->sum('nominal');
        $totalSimpanan    = $simpananPokok + $simpananWajib + $simpananSukarela;

        // --- Pinjaman Aktif ---
        $pinjamanAktif = $anggota->pinjaman->where('status', 'aktif')->first();

        // --- SHU ---
        $shuTahunIni  = $anggota->shu->sortByDesc('tahun')->first();
        $shuSebelumnya = $anggota->shu->sortByDesc('tahun')->skip(1)->first();

        // --- Riwayat Transaksi (5 terakhir) ---
        $transaksiTerakhir = Transaksi::with(['details.barang'])
            ->where('id_anggota', 'ANG-056')
            ->orderBy('tgl_transaksi', 'desc')
            ->take(5)
            ->get();

        // --- Riwayat Simpanan (8 terakhir) ---
        $riwayatSimpanan = Simpanan::where('id_anggota', 'ANG-056')
            ->orderBy('tanggal', 'desc')
            ->take(8)
            ->get();

        return view('anggota.dashboard', compact(
            'anggota',
            'simpananPokok',
            'simpananWajib',
            'simpananSukarela',
            'totalSimpanan',
            'pinjamanAktif',
            'shuTahunIni',
            'shuSebelumnya',
            'transaksiTerakhir',
            'riwayatSimpanan'
        ));
    }
}
