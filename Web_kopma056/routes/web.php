<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaDashboardController;
use App\Http\Controllers\SmartAnalysisController;
use App\Http\Controllers\PaymentGatewayController;
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTENTIKASI
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('login.quick');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// RUTE TERPROTEKSI (perlu login)
// ============================================================
Route::middleware(['web'])->group(function () {

    // 1. Dashboard Utama (Admin & Pengurus)
    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('role:admin,pengurus')
        ->name('dashboard');

    // 2. Dashboard Anggota (Anggota & Admin)
    Route::get('/dashboard-anggota', [AnggotaDashboardController::class, 'index'])
        ->middleware('role:anggota,admin,pengurus')
        ->name('anggota.dashboard');

    // 3. Transaksi Kasir POS (Kasir & Admin)
    Route::prefix('pos')->name('pos.')->middleware('role:kasir,admin')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/', [PosController::class, 'store'])->name('store');
        Route::post('/snap-token', [PosController::class, 'createSnapToken'])->name('snap');
        Route::get('/struk/{id}', [PosController::class, 'receipt'])->name('receipt');
    });

    // 4. Manajemen Barang (Admin & Pengurus)
    Route::prefix('barang')->name('barang.')->middleware('role:admin,pengurus')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::put('/{kode_barang}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{kode_barang}', [BarangController::class, 'destroy'])->name('destroy');
    });

    // 5. Data Anggota (Admin & Pengurus)
    Route::prefix('anggota')->name('anggota.')->middleware('role:admin,pengurus')->group(function () {
        Route::get('/', [AnggotaController::class, 'index'])->name('index');
        Route::post('/', [AnggotaController::class, 'store'])->name('store');
        Route::put('/{id_anggota}', [AnggotaController::class, 'update'])->name('update');
        Route::delete('/{id_anggota}', [AnggotaController::class, 'destroy'])->name('destroy');
    });

    // 6. Riwayat & Laporan Transaksi (Admin, Pengurus, Kasir)
    Route::prefix('transaksi')->name('transaksi.')->middleware('role:admin,pengurus,kasir')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/export/csv', [TransaksiController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/print', [TransaksiController::class, 'exportPrint'])->name('export.print');
        Route::get('/{id}', [TransaksiController::class, 'show'])->name('show');
    });

    // 7. Analisis Cerdas (Smart Analysis AI) (Admin & Pengurus)
    Route::get('/analisis', [SmartAnalysisController::class, 'index'])
        ->middleware('role:admin,pengurus')
        ->name('analisis.index');

    // 8. Payment Gateway Midtrans (Khusus Admin)
    Route::prefix('payment')->name('payment.')->middleware('role:admin')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
        Route::post('/save', [PaymentGatewayController::class, 'save'])->name('save');
        Route::post('/test-connection', [PaymentGatewayController::class, 'testConnection'])->name('test');
    });
});

// Webhook Midtrans (tidak perlu auth)
Route::post('/api/midtrans/notification', [PaymentGatewayController::class, 'notification'])
    ->name('midtrans.notification');
