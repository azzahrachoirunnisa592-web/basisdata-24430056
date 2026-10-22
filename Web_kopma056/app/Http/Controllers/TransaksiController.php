<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['anggota', 'kasir', 'details.barang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function ($qa) use ($search) {
                      $qa->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_transaksi', $request->tanggal);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->paginate(15);
        $totalOmset = Transaksi::sum('total_bayar');

        return view('transaksi.index', compact('transaksi', 'totalOmset'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['anggota', 'kasir', 'details.barang'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Cetak Laporan Rekapitulasi Penjualan (Tampilan Cetak / PDF)
     */
    public function exportPrint(Request $request)
    {
        $query = Transaksi::with(['anggota', 'kasir', 'details.barang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function ($qa) use ($search) {
                      $qa->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_transaksi', $request->tanggal);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->get();
        $totalOmset = $transaksi->sum('total_bayar');
        $totalItemTerjual = $transaksi->reduce(function ($carry, $t) {
            return $carry + $t->details->sum('jumlah');
        }, 0);

        return view('transaksi.print', compact('transaksi', 'totalOmset', 'totalItemTerjual'));
    }

    /**
     * Ekspor Laporan Rekapitulasi Transaksi ke Format File CSV (Kompatibel Excel)
     */
    public function exportCsv(Request $request)
    {
        $query = Transaksi::with(['anggota', 'kasir', 'details.barang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function ($qa) use ($search) {
                      $qa->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_transaksi', $request->tanggal);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->get();
        $fileName = 'Laporan_Transaksi_KOPMA_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($transaksi) {
            $output = fopen('php://output', 'w');
            // Menuliskan BOM UTF-8 agar karakter bahasa Indonesia terbaca rapi di Microsoft Excel
            fputs($output, "\xEF\xBB\xBF");

            // Header Kolom CSV
            fputcsv($output, [
                'No',
                'No. Faktur',
                'Tanggal & Jam',
                'Nama Pembeli',
                'NIM / ID Anggota',
                'Nama Kasir',
                'Total Belanja (Rp)',
                'Diskon (Rp)',
                'Total Bayar (Rp)',
                'Nominal Uang Diterima (Rp)',
                'Kembalian (Rp)',
                'Jumlah Macam Barang',
                'Total Unit Barang',
            ]);

            $no = 1;
            foreach ($transaksi as $t) {
                $pembeli = $t->anggota ? $t->anggota->nama : 'Pelanggan Umum';
                $nim = $t->anggota ? ($t->anggota->nim ?: $t->anggota->id_anggota) : '-';
                $kasir = $t->kasir ? $t->kasir->name : 'Kasir Utama';

                fputcsv($output, [
                    $no++,
                    $t->no_faktur,
                    $t->tgl_transaksi->format('Y-m-d H:i:s'),
                    $pembeli,
                    $nim,
                    $kasir,
                    $t->total_belanja,
                    $t->diskon,
                    $t->total_bayar,
                    $t->nominal_uang,
                    $t->kembalian,
                    $t->details->count(),
                    $t->details->sum('jumlah'),
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}

