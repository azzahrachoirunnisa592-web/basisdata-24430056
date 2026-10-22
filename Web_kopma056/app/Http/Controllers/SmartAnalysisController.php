<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartAnalysisController extends Controller
{
    /**
     * Halaman Analisis Cerdas & Rencana Strategis Operasional KOPMA
     * — Memberikan analisis laporan mendalam & rencana aksi masa depan untuk Admin
     */
    public function index()
    {
        // ============================================================
        // 1. DATA TRANSAKSI & REALISASI FINANSIAL
        // ============================================================
        $now = Carbon::now();
        $totalTransaksiBulanIni = Transaksi::whereMonth('tgl_transaksi', $now->month)
            ->whereYear('tgl_transaksi', $now->year)
            ->count();

        $totalOmzetBulanIni = Transaksi::whereMonth('tgl_transaksi', $now->month)
            ->whereYear('tgl_transaksi', $now->year)
            ->sum('total_bayar');

        // Jika bulan ini masih 0 (misal data dummy ada di bulan sebelumnya), ambil total 30 hari terakhir
        if ($totalOmzetBulanIni == 0) {
            $totalOmzetBulanIni = Transaksi::sum('total_bayar');
            $totalTransaksiBulanIni = Transaksi::count();
        }

        $rataHarian = $now->day > 0 ? round($totalOmzetBulanIni / max(1, $now->day)) : 0;
        $proyeksiBulanIni = $rataHarian * $now->daysInMonth;

        // Rata-rata nilai belanja per transaksi (Average Order Value / Basket Size)
        $avgBasketSize = $totalTransaksiBulanIni > 0 
            ? round($totalOmzetBulanIni / $totalTransaksiBulanIni) 
            : 0;

        // Estimasi Laba Kotor (Gross Profit) dari seluruh transaksi
        $estimasiLabaKotor = 0;
        $details = DetailTransaksi::with('barang')->get();
        foreach ($details as $d) {
            if ($d->barang && $d->barang->harga_beli > 0) {
                $keuntunganPerItem = $d->harga_satuan - $d->barang->harga_beli;
                $estimasiLabaKotor += ($keuntunganPerItem * $d->jumlah);
            } else {
                // Asumsi margin 15% jika harga beli 0 / tidak tercatat
                $estimasiLabaKotor += ($d->subtotal * 0.15);
            }
        }
        $marginPersen = $totalOmzetBulanIni > 0 
            ? round(($estimasiLabaKotor / max(1, $totalOmzetBulanIni)) * 100, 1) 
            : 18.5;

        // ============================================================
        // 2. RENCANA 1: PENGADAAN & ESTIMASI ANGGARAN RESTOCK STOK
        // ============================================================
        // Ambil data penjualan per barang
        $penjualanPerBarang = DetailTransaksi::select('kode_barang', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_omzet'))
            ->groupBy('kode_barang')
            ->pluck('total_terjual', 'kode_barang')
            ->toArray();

        $allBarang = Barang::with('kategori')->orderBy('stok', 'asc')->get();
        $rencanaRestock = [];
        $totalDanaRestockDibutuhkan = 0;
        $jumlahBarangPerluRestock = 0;

        foreach ($allBarang as $b) {
            $terjual = $penjualanPerBarang[$b->kode_barang] ?? 0;
            // Target stok aman koperasi (buffer 30 hari): minimal 25 pcs atau 2x penjualan
            $targetBuffer = max(20, $terjual * 1.5);
            $kekuranganStok = max(0, ceil($targetBuffer - $b->stok));

            // Jika stok menipis (<= 10) atau stok habis
            if ($b->stok <= 10 || $kekuranganStok > 0) {
                $qtyRekomendasi = max(10, $kekuranganStok);
                $estimasiBiaya = $qtyRekomendasi * ($b->harga_beli > 0 ? $b->harga_beli : ($b->harga_jual * 0.8));
                
                $tingkatUrgensi = 'aman';
                if ($b->stok <= 2) {
                    $tingkatUrgensi = 'kritis';
                } elseif ($b->stok <= 7) {
                    $tingkatUrgensi = 'waspada';
                } else {
                    $tingkatUrgensi = 'rekomendasi';
                }

                $rencanaRestock[] = [
                    'kode_barang'     => $b->kode_barang,
                    'nama_barang'     => $b->nama_barang,
                    'kategori'        => $b->kategori ? $b->kategori->nama_kategori : 'Umum',
                    'stok_sekarang'   => $b->stok,
                    'satuan'          => $b->satuan ?? 'pcs',
                    'total_terjual'   => $terjual,
                    'qty_rekomendasi' => $qtyRekomendasi,
                    'harga_beli'      => $b->harga_beli ?: ($b->harga_jual * 0.8),
                    'estimasi_biaya'  => $estimasiBiaya,
                    'urgensi'         => $tingkatUrgensi,
                    'estimasi_hari_habis' => $terjual > 0 ? max(1, round(($b->stok / max(1, $terjual / 30)))) : ($b->stok == 0 ? 0 : 30),
                ];

                $totalDanaRestockDibutuhkan += $estimasiBiaya;
                $jumlahBarangPerluRestock++;
            }
        }

        // Urutkan rencana restock dari yang paling mendesak
        usort($rencanaRestock, function ($a, $b) {
            $order = ['kritis' => 1, 'waspada' => 2, 'rekomendasi' => 3, 'aman' => 4];
            return ($order[$a['urgensi']] ?? 5) <=> ($order[$b['urgensi']] ?? 5);
        });

        // ============================================================
        // 3. RENCANA 2: HERO PRODUCTS (FAST-MOVING) VS SLOW-MOVING
        // ============================================================
        // Top 5 Produk Terlaris
        $heroProducts = DetailTransaksi::select('kode_barang', DB::raw('SUM(jumlah) as qty_terjual'), DB::raw('SUM(subtotal) as omzet_produk'))
            ->groupBy('kode_barang')
            ->orderByDesc('qty_terjual')
            ->take(5)
            ->with('barang.kategori')
            ->get();

        // Produk Lambat Bergerak (Stok menumpuk, penjualan rendah)
        $slowMovingProducts = [];
        foreach ($allBarang as $b) {
            $terjual = $penjualanPerBarang[$b->kode_barang] ?? 0;
            if ($b->stok > 12 && $terjual <= 2) {
                $slowMovingProducts[] = [
                    'nama'           => $b->nama_barang,
                    'stok'           => $b->stok,
                    'terjual'        => $terjual,
                    'nilai_tertahan' => $b->stok * ($b->harga_beli ?: ($b->harga_jual * 0.8)),
                    'saran_aksi'     => 'Bundling diskon dengan produk terlaris atau promo paket hemat anggota',
                ];
            }
        }

        // ============================================================
        // 4. RENCANA 3: PERAMALAN OMZET & ARUS KAS BULAN DEPAN
        // ============================================================
        // Skenario Finansial Bulan Depan
        $baselineForecast = max(5000000, $proyeksiBulanIni);
        $forecastBulanDepan = [
            'konservatif' => [
                'label'      => 'Konservatif (Musim Ujian / Libur)',
                'persentase' => '-10%',
                'nominal'    => round($baselineForecast * 0.90),
                'keterangan' => 'Asumsi aktivitas kampus sedikit menurun saat periode libur perkuliahan.',
            ],
            'moderat' => [
                'label'      => 'Moderat (Aktivitas Normal)',
                'persentase' => '+6%',
                'nominal'    => round($baselineForecast * 1.06),
                'keterangan' => 'Pertumbuhan stabil dengan pelanggan reguler & perputaran barang standar.',
            ],
            'agresif' => [
                'label'      => 'Agresif (Restock Lengkap & Promo)',
                'persentase' => '+18%',
                'nominal'    => round($baselineForecast * 1.18),
                'keterangan' => 'Tercapai jika rencana restock prioritas dan promo bundling anggota dijalankan.',
            ],
        ];

        // Cadangan Kas Operasional Minimum (Safety Cash Reserve)
        $targetCadanganKas = round($baselineForecast * 0.25); // 25% dari omzet bulanan
        // Alokasi Cadangan SHU
        $estimasiAlokasiSHU = round($estimasiLabaKotor * 0.30); // 30% dari laba kotor dialokasikan ke SHU

        // ============================================================
        // 5. RENCANA 4: POLA JAM SIBUK BELANJA & ALOKASI STAF KASIR
        // ============================================================
        $jamTransaksi = Transaksi::select(DB::raw('HOUR(tgl_transaksi) as jam'), DB::raw('count(*) as total'))
            ->groupBy('jam')
            ->pluck('total', 'jam')
            ->toArray();

        $polaWaktu = [
            'pagi' => ($jamTransaksi[8] ?? 0) + ($jamTransaksi[9] ?? 0) + ($jamTransaksi[10] ?? 0),
            'siang' => ($jamTransaksi[11] ?? 0) + ($jamTransaksi[12] ?? 0) + ($jamTransaksi[13] ?? 0),
            'sore' => ($jamTransaksi[14] ?? 0) + ($jamTransaksi[15] ?? 0) + ($jamTransaksi[16] ?? 0) + ($jamTransaksi[17] ?? 0),
            'malam' => ($jamTransaksi[18] ?? 0) + ($jamTransaksi[19] ?? 0) + ($jamTransaksi[20] ?? 0),
        ];

        // Tentukan waktu puncak
        $waktuPuncak = 'siang';
        $maxWaktu = 0;
        foreach ($polaWaktu as $w => $c) {
            if ($c > $maxWaktu) {
                $maxWaktu = $c;
                $waktuPuncak = $w;
            }
        }

        // ============================================================
        // 6. ANALISIS RISIKO KREDIT MACET & STATUS SHU
        // ============================================================
        $kreditMacet = Pinjaman::with('anggota')
            ->where('status', 'macet')
            ->get();
        $totalNominalMacet = $kreditMacet->sum('sisa_pinjaman');

        $shuBelumDibagi = DB::table('shu')
            ->where('status', 'belum_dibagi')
            ->select('tahun', DB::raw('count(*) as jumlah_anggota'), DB::raw('sum(total_shu) as total'))
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->get();

        // ============================================================
        // 7. GRAFIK OMZET 7 HARI & TREN 12 BULAN
        // ============================================================
        $omzetHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->toDateString();
            $omzet = Transaksi::whereDate('tgl_transaksi', $tanggal)->sum('total_bayar');
            $omzetHarian[] = [
                'tanggal' => Carbon::now()->subDays($i)->translatedFormat('D, d M'),
                'omzet'   => $omzet,
            ];
        }

        $omzetBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $omzet = Transaksi::whereYear('tgl_transaksi', $bulan->year)
                ->whereMonth('tgl_transaksi', $bulan->month)
                ->sum('total_bayar');
            $omzetBulanan[] = [
                'label'   => $bulan->translatedFormat('M Y'),
                'omzet'   => $omzet,
                'optimis' => round($omzet * 1.15),
                'moderat' => round($omzet * 1.05),
                'pesimis' => round($omzet * 0.90),
            ];
        }

        return view('analisis.index', compact(
            'totalOmzetBulanIni',
            'rataHarian',
            'proyeksiBulanIni',
            'totalTransaksiBulanIni',
            'avgBasketSize',
            'estimasiLabaKotor',
            'marginPersen',
            'rencanaRestock',
            'totalDanaRestockDibutuhkan',
            'jumlahBarangPerluRestock',
            'heroProducts',
            'slowMovingProducts',
            'forecastBulanDepan',
            'targetCadanganKas',
            'estimasiAlokasiSHU',
            'polaWaktu',
            'waktuPuncak',
            'kreditMacet',
            'totalNominalMacet',
            'shuBelumDibagi',
            'omzetHarian',
            'omzetBulanan'
        ));
    }
}
