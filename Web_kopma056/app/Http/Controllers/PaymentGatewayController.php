<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Halaman Pengaturan Payment Gateway (Midtrans)
     */
    public function index()
    {
        // Ambil konfigurasi dari env / config (atau default kosong)
        $config = [
            'environment'   => config('midtrans.environment', 'sandbox'),
            'server_key'    => config('midtrans.server_key', ''),
            'client_key'    => config('midtrans.client_key', ''),
            'webhook_url'   => url('/api/midtrans/notification'),
            'finish_url'    => url('/payment/finish'),
            'error_url'     => url('/payment/error'),
            'unfinish_url'  => url('/payment/unfinish'),
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
        ]);

        // Tulis ke file .env (simulasi — dalam produksi gunakan env manager)
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $updates = [
            'MIDTRANS_ENV'        => $request->environment,
            'MIDTRANS_SERVER_KEY' => $request->server_key,
            'MIDTRANS_CLIENT_KEY' => $request->client_key,
        ];

        foreach ($updates as $key => $value) {
            if (str_contains($envContent, "{$key}=")) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);

        return redirect()->route('payment.index')
            ->with('success', 'Konfigurasi Midtrans berhasil disimpan! Server akan menggunakan pengaturan baru.');
    }

    /**
     * Endpoint webhook/notifikasi Midtrans (simulasi)
     */
    public function notification(Request $request)
    {
        // Dalam produksi: verifikasi signature key dan proses status pembayaran
        return response()->json(['status' => 'ok', 'message' => 'Notifikasi diterima KOMPA'], 200);
    }
}
