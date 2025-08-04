<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasTransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaldoAkhirController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
Route::middleware(['auth', 'role:bendahara'])->group(function () {
    Route::get('/kas/create', [KasTransaksiController::class, 'create'])->name('kas.create');
    Route::post('/kas', [KasTransaksiController::class, 'store'])->name('kas.store');
    Route::delete('/kas/{kas}', [KasTransaksiController::class, 'destroy'])->name('kas.destroy');
    Route::get('/api/saldo-tunai', [\App\Http\Controllers\SaldoAkhirController::class, 'getSaldoTunai']);
    Route::post('/tunai', [SaldoAkhirController::class, 'storeTunai'])->name('saldo-akhir.tunai.store');
    Route::get('/saldo-akhir/tunai/create', [SaldoAkhirController::class, 'createTunai'])->name('saldo-akhir.tunai.create');
    Route::get('/api/saldo-non-tunai', [SaldoAkhirController::class, 'getSaldoNonTunai']);
    Route::get('/saldo-akhir/non-tunai/create', [SaldoAkhirController::class, 'createNonTunai'])->name('saldo-akhir.nontunai.create');
    Route::post('/nontunai', [SaldoAkhirController::class, 'storeNonTunai'])->name('saldo-akhir.nontunai.store');
    Route::delete('/saldo-akhir/{id}', [SaldoAkhirController::class, 'destroy'])->name('saldo-akhir.destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kas', [KasTransaksiController::class, 'index'])->name('kas.index');
    Route::get('/kas/{kas}', [KasTransaksiController::class, 'show'])->name('kas.show');
    // Saldo Akhir Tunai
    Route::get('/saldo-akhir/tunai', [SaldoAkhirController::class, 'indexTunai'])->name('saldo-akhir.tunai');
    Route::get('/saldo-akhir/tunai/{id}', [SaldoAkhirController::class, 'showTunai'])->name('saldo-akhir.showTunai');
    Route::get('/saldo-akhir/tunai/pdf/{id}', [SaldoAkhirController::class, 'cetakPdfTunai'])->name('saldo-akhir.pdf.tunai');
    // Saldo Akhir Non Tunai
    Route::get('/saldo-akhir/non-tunai', [SaldoAkhirController::class, 'indexNonTunai'])->name('saldo-akhir.non-tunai');
    Route::get('/saldo-akhir/non-tunai/{id}', [SaldoAkhirController::class, 'showNonTunai'])->name('saldo-akhir.showNontunai');
    Route::get('/saldo-akhir/non-tunai/pdf/{id}', [SaldoAkhirController::class, 'cetakNonTunai'])->name('saldo-akhir.cetakNonTunai');
});
