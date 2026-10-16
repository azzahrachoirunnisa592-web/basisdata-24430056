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

    // 1. Dashboard Utama (Admin/Kasir/Pengurus)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Dashboard Anggota
    Route::get('/dashboard-anggota', [AnggotaDashboardController::class, 'index'])->name('anggota.dashboard');

    // 3. Transaksi Kasir (POS)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/', [PosController::class, 'store'])->name('store');
        Route::get('/struk/{id}', [PosController::class, 'receipt'])->name('receipt');
    });

    // 4. Manajemen Barang
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::put('/{kode_barang}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{kode_barang}', [BarangController::class, 'destroy'])->name('destroy');
    });

    // 5. Data Anggota
    Route::prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [AnggotaController::class, 'index'])->name('index');
        Route::post('/', [AnggotaController::class, 'store'])->name('store');
        Route::put('/{id_anggota}', [AnggotaController::class, 'update'])->name('update');
        Route::delete('/{id_anggota}', [AnggotaController::class, 'destroy'])->name('destroy');
    });

    // 6. Riwayat & Laporan Transaksi
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/{id}', [TransaksiController::class, 'show'])->name('show');
    });

    // 7. Analisis Cerdas (Smart Analysis)
    Route::get('/analisis', [SmartAnalysisController::class, 'index'])->name('analisis.index');

    // 8. Payment Gateway (Midtrans)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
        Route::post('/save', [PaymentGatewayController::class, 'save'])->name('save');
    });
});

// Webhook Midtrans (tidak perlu auth)
Route::post('/api/midtrans/notification', [PaymentGatewayController::class, 'notification'])
    ->name('midtrans.notification');
