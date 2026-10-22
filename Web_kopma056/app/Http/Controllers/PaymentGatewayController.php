<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentGatewayController extends Controller
{
    /**
     * Halaman Pengaturan Payment Gateway (Midtrans)
     */
    public function index()
    {
        // Ambil konfigurasi dari env / config (atau default kosong)
        $config = [
            'environment'   => config('midtrans.environment', env('MIDTRANS_ENV', 'sandbox')),
            'merchant_id'   => env('MIDTRANS_MERCHANT_ID', ''),
            'server_key'    => config('midtrans.server_key', env('MIDTRANS_SERVER_KEY', '')),
            'client_key'    => config('midtrans.client_key', env('MIDTRANS_CLIENT_KEY', '')),
            'webhook_url'   => url('/api/midtrans/notification'),
            'finish_url'    => url('/transaksi'),
            'error_url'     => url('/pos'),
            'unfinish_url'  => url('/pos'),
        ];

        return view('payment.index', compact('config'));
    }

    /**
     * Simpan konfigurasi Midtrans ke file .env
     */
    public function save(Request $request)
    {
        $request->validate([
            'environment' => 'required|in:sandbox,production',
            'server_key'  => 'required|string|min:5',
            'client_key'  => 'required|string|min:5',
            'merchant_id' => 'nullable|string',
        ]);

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $updates = [
            'MIDTRANS_ENV'         => trim($request->environment),
            'MIDTRANS_SERVER_KEY'  => trim($request->server_key),
            'MIDTRANS_CLIENT_KEY'  => trim($request->client_key),
            'MIDTRANS_MERCHANT_ID' => trim($request->merchant_id ?? ''),
        ];

        foreach ($updates as $key => $value) {
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);

        return redirect()->route('payment.index')
            ->with('success', 'Konfigurasi Midtrans Payment Gateway berhasil disimpan ke file sistem!');
    }

    /**
     * Uji Langsung Koneksi API Midtrans
     */
    public function testConnection(Request $request)
    {
        $serverKey = $request->server_key ?: config('midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $environment = $request->environment ?: config('midtrans.environment', env('MIDTRANS_ENV', 'sandbox'));

        if (empty($serverKey)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Server Key masih kosong. Silakan isi Server Key terlebih dahulu.',
            ], 422);
        }

        $apiUrl = $environment === 'production'
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        try {
            // Cek status transaksi dummy untuk memvalidasi Basic Auth Server Key
            $response = Http::withBasicAuth(trim($serverKey), '')
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->timeout(8)
                ->get("{$apiUrl}/ping-test-auth-check/status");

            $statusCode = $response->status();

            // Midtrans merespons 401 jika Server Key salah/invalid
            if ($statusCode === 401) {
                return response()->json([
                    'status'   => 'invalid',
                    'message'  => 'Otorisasi Gagal (HTTP 401): Server Key tidak valid atau tidak cocok dengan environment (' . strtoupper($environment) . '). Periksa kembali Server Key dari Dashboard Midtrans.',
                ]);
            }

            // Jika status 404 (order not found) atau 200, berarti Basic Auth berhasil dilewati server Midtrans
            if (in_array($statusCode, [200, 404, 400])) {
                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Koneksi ke server Midtrans (' . strtoupper($environment) . ') BERHASIL! Server Key valid dan terhubung dengan aman.',
                    'response' => [
                        'environment' => strtoupper($environment),
                        'ping_url'    => $apiUrl,
                        'http_code'   => $statusCode,
                    ],
                ]);
            }

            return response()->json([
                'status'  => 'warning',
                'message' => 'Respon Midtrans tidak terduga (HTTP ' . $statusCode . '). Mohon pastikan koneksi internet server stabil.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage() . '. Pastikan server terhubung ke internet.',
            ], 500);
        }
    }

    /**
     * Endpoint webhook/notifikasi Midtrans
     */
    public function notification(Request $request)
    {
        return response()->json(['status' => 'ok', 'message' => 'Notifikasi diterima KOMPA'], 200);
    }
}
