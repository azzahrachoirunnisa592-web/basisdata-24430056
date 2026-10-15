<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing DashboardController@index...\n";
    $dash = app(App\Http\Controllers\DashboardController::class)->index();
    $dashHtml = $dash->render();
    echo "-> Dashboard OK (" . strlen($dashHtml) . " bytes)\n";

    echo "Testing PosController@index...\n";
    $pos = app(App\Http\Controllers\PosController::class)->index();
    $posHtml = $pos->render();
    echo "-> POS OK (" . strlen($posHtml) . " bytes)\n";

    echo "Testing BarangController@index...\n";
    $barang = app(App\Http\Controllers\BarangController::class)->index(request());
    $barangHtml = $barang->render();
    echo "-> Barang OK (" . strlen($barangHtml) . " bytes)\n";

    echo "Testing AnggotaController@index...\n";
    $anggota = app(App\Http\Controllers\AnggotaController::class)->index(request());
    $anggotaHtml = $anggota->render();
    echo "-> Anggota OK (" . strlen($anggotaHtml) . " bytes)\n";

    echo "Testing TransaksiController@index...\n";
    $trx = app(App\Http\Controllers\TransaksiController::class)->index(request());
    $trxHtml = $trx->render();
    echo "-> Transaksi OK (" . strlen($trxHtml) . " bytes)\n";

    echo "ALL VIEWS COMPILED & RENDERED PERFECTLY!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
