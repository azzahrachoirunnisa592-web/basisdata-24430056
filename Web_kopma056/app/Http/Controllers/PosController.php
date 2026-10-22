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
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        // Load anggota dengan no_hp, nim, nama untuk pencarian kasir instan
        $anggota = Anggota::where('status', 'aktif')
            ->select('id_anggota', 'nama', 'no_hp', 'nim', 'jurusan', 'status')
            ->orderBy('nama')
            ->get();
        $kasir = User::first();

        // Generate No Faktur Otomatis
        $today = Carbon::now()->format('Ymd');
        $lastTrx = Transaksi::whereDate('tgl_transaksi', Carbon::today())->latest('id_transaksi')->first();
        $nextNumber = 1;
        if ($lastTrx && preg_match('/TRX-\d{8}-(\d+)/', $lastTrx->no_faktur, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $noFakturBaru = sprintf("TRX-%s-%03d", $today, $nextNumber);

        $midtransConfig = [
            'client_key'  => config('midtrans.client_key', env('MIDTRANS_CLIENT_KEY', '')),
            'server_key'  => config('midtrans.server_key', env('MIDTRANS_SERVER_KEY', '')),
            'snap_url'    => config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js'),
            'environment' => config('midtrans.environment', env('MIDTRANS_ENV', 'sandbox')),
        ];

        return view('pos.index', compact('kategori', 'barang', 'anggota', 'kasir', 'noFakturBaru', 'midtransConfig'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.kode_barang' => 'required|exists:barang,kode_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'nominal_uang' => 'nullable|numeric|min:0',
        ]);

        $kasir = User::first();
        $metode = $request->input('metode_pembayaran', 'tunai');

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

            // Jika non-tunai (QRIS / Midtrans / Debit / Bank VA), nominal uang sama dengan total bayar
            if (in_array($metode, ['qris', 'midtrans', 'debit', 'va', 'bank_va'])) {
                $nominalUang = $totalBayar;
                $kembalian = 0;
            } else {
                $nominalUang = intval($request->nominal_uang ?? 0);
                if ($nominalUang < $totalBayar) {
                    throw new \Exception("Nominal uang yang dibayarkan (Rp " . number_format($nominalUang, 0, ',', '.') . ") kurang dari total belanja (Rp " . number_format($totalBayar, 0, ',', '.') . ").");
                }
                $kembalian = $nominalUang - $totalBayar;
            }

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

            return redirect()->route('pos.receipt', [
                'id'        => $transaksi->id_transaksi,
                'metode'    => $metode,
                'autoprint' => '1',
            ])->with('success', "Transaksi {$transaksi->no_faktur} berhasil diproses dan stok barang telah dipotong otomatis!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Generate Midtrans Snap Token untuk Pembayaran Digital di Kasir
     */
    public function createSnapToken(Request $request)
    {
        $request->validate([
            'gross_amount' => 'required|numeric|min:100',
            'order_id'     => 'required|string',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
        ]);

        $serverKey = config('midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $isProduction = config('midtrans.environment', env('MIDTRANS_ENV')) === 'production';
        $apiUrl = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        // Jika Server Key belum diset di admin, sediakan mode simulasi instan
        if (empty($serverKey)) {
            return response()->json([
                'status'  => 'simulated',
                'message' => 'Simulasi Midtrans Aktif (Isi Server Key di Pengaturan Admin untuk mode live)',
                'token'   => 'SIMULATED-' . time() . '-' . rand(1000, 9999),
            ]);
        }

        try {
            $response = Http::withBasicAuth(trim($serverKey), '')
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(10)
                ->post($apiUrl, [
                    'transaction_details' => [
                        'order_id'     => $request->order_id . '-' . time(),
                        'gross_amount' => (int) $request->gross_amount,
                    ],
                    'customer_details' => [
                        'first_name' => $request->customer_name ?: 'Pelanggan KOPMA',
                        'phone'      => $request->customer_phone ?: '081234567890',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'status'       => 'success',
                    'token'        => $data['token'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                ]);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => $response->json()['error_messages'][0] ?? 'Respon error dari Midtrans Snap API',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koneksi ke Midtrans gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function receipt($id)
    {
        $transaksi = Transaksi::with(['anggota', 'kasir', 'details.barang'])->findOrFail($id);
        $metodePembayaran = request('metode', 'tunai');
        $autoPrint = request('autoprint', '0') === '1';
        return view('pos.receipt', compact('transaksi', 'metodePembayaran', 'autoPrint'));
    }
}
