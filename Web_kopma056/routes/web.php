<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// 1. Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// 2. Transaksi Kasir (POS)
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::post('/', [PosController::class, 'store'])->name('store');
    Route::get('/struk/{id}', [PosController::class, 'receipt'])->name('receipt');
});

// 3. Manajemen Barang
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::put('/{kode_barang}', [BarangController::class, 'update'])->name('update');
    Route::delete('/{kode_barang}', [BarangController::class, 'destroy'])->name('destroy');
});

// 4. Data Anggota
Route::prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/', [AnggotaController::class, 'index'])->name('index');
    Route::post('/', [AnggotaController::class, 'store'])->name('store');
    Route::put('/{id_anggota}', [AnggotaController::class, 'update'])->name('update');
    Route::delete('/{id_anggota}', [AnggotaController::class, 'destroy'])->name('destroy');
});

// 5. Riwayat & Laporan Transaksi
Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/{id}', [TransaksiController::class, 'show'])->name('show');
});
