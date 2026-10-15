<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        $anggota = Anggota::where('status', 'aktif')->orderBy('nama')->get();
        $kasir = User::first();

        // Generate No Faktur Otomatis
        $today = Carbon::now()->format('Ymd');
        $lastTrx = Transaksi::whereDate('tgl_transaksi', Carbon::today())->latest('id_transaksi')->first();
        $nextNumber = 1;
        if ($lastTrx && preg_match('/TRX-\d{8}-(\d+)/', $lastTrx->no_faktur, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $noFakturBaru = sprintf("TRX-%s-%03d", $today, $nextNumber);

        return view('pos.index', compact('kategori', 'barang', 'anggota', 'kasir', 'noFakturBaru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.kode_barang' => 'required|exists:barang,kode_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'nominal_uang' => 'required|numeric|min:0',
        ]);

        $kasir = User::first();

        try {
            DB::beginTransaction();

            $totalBelanja = 0;
            $itemsToProcess = [];

            // 1. Validasi stok untuk setiap barang dan hitung total
            foreach ($request->items as $item) {
                $barang = Barang::where('kode_barang', $item['kode_barang'])->lockForUpdate()->first();

                if (!$barang) {
                    throw new \Exception("Barang dengan kode {$item['kode_barang']} tidak ditemukan.");
                }

                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak mencukupi untuk {$barang->nama_barang}. Stok tersedia: {$barang->stok}, diminta: {$item['jumlah']}.");
                }

                $subtotal = $barang->harga_jual * $item['jumlah'];
                $totalBelanja += $subtotal;

                $itemsToProcess[] = [
                    'barang' => $barang,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $barang->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $diskon = intval($request->diskon ?? 0);
            $totalBayar = max(0, $totalBelanja - $diskon);
            $nominalUang = intval($request->nominal_uang);

            if ($nominalUang < $totalBayar) {
                throw new \Exception("Nominal uang yang dibayarkan (Rp " . number_format($nominalUang, 0, ',', '.') . ") kurang dari total belanja (Rp " . number_format($totalBayar, 0, ',', '.') . ").");
            }

            $kembalian = $nominalUang - $totalBayar;

            // 2. Simpan Transaksi Master
            $transaksi = Transaksi::create([
                'no_faktur' => $request->no_faktur,
                'id_anggota' => $request->id_anggota ?: null,
                'user_id' => $kasir ? $kasir->id : null,
                'tgl_transaksi' => now(),
                'total_belanja' => $totalBelanja,
                'diskon' => $diskon,
                'total_bayar' => $totalBayar,
                'nominal_uang' => $nominalUang,
                'kembalian' => $kembalian,
            ]);

            // 3. Simpan Detail Transaksi dan Potong Stok Otomatis
            foreach ($itemsToProcess as $proc) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'kode_barang' => $proc['barang']->kode_barang,
                    'harga_satuan' => $proc['harga_satuan'],
                    'jumlah' => $proc['jumlah'],
                    'subtotal' => $proc['subtotal'],
                ]);

                // Pemotongan stok otomatis
                $proc['barang']->decrement('stok', $proc['jumlah']);
            }

            DB::commit();

            return redirect()->route('pos.receipt', $transaksi->id_transaksi)
                ->with('success', "Transaksi {$transaksi->no_faktur} berhasil diproses dan stok barang telah dipotong otomatis!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        $transaksi = Transaksi::with(['anggota', 'kasir', 'details.barang'])->findOrFail($id);
        return view('pos.receipt', compact('transaksi'));
    }
}
