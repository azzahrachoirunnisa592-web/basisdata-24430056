<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Payment Gateway Configuration
    |--------------------------------------------------------------------------
    | Konfigurasi untuk integrasi Midtrans Payment Gateway KOMPA
    | Annisa Choirunnisa - NIM: 24430056
    */

    'environment' => env('MIDTRANS_ENV', 'sandbox'),
    'server_key'  => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    | URL Snap Midtrans
    */
    'snap_url' => env('MIDTRANS_ENV', 'sandbox') === 'sandbox'
        ? 'https://app.sandbox.midtrans.com/snap/snap.js'
        : 'https://app.midtrans.com/snap/snap.js',

    'api_url' => env('MIDTRANS_ENV', 'sandbox') === 'sandbox'
        ? 'https://api.sandbox.midtrans.com/v2'
        : 'https://api.midtrans.com/v2',
];
