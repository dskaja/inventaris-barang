<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PemeliharaanController;


Route::get('/', function () {
    return view ('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('lokasi', LokasiController::class);
    
    // Routes Barang
    Route::get('/barang/laporan', [BarangController::class, 'cetakLaporan'])->name('barang.laporan');
    Route::resource('barang', BarangController::class);

    // Routes Peminjaman - URUTAN PENTING! Spesifik route harus di ATAS resource
    Route::get('/peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::put('/peminjaman/{id}/update-kembali', [PeminjamanController::class, 'updateKembali'])->name('peminjaman.update-kembali');
    Route::get('/api/barang/{id}/info', [PeminjamanController::class, 'getBarangInfo'])->name('barang.info');
    Route::resource('peminjaman', PeminjamanController::class);

    // ========================================
    // ROUTES PEMELIHARAAN
    // ========================================
    // Route spesifik HARUS di ATAS {id}
    Route::get('/pemeliharaan/laporan', [PemeliharaanController::class, 'laporan'])->name('pemeliharaan.laporan');
    Route::get('/pemeliharaan/create', [PemeliharaanController::class, 'create'])->name('pemeliharaan.create');
    Route::post('/pemeliharaan', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
    Route::get('/pemeliharaan/{id}', [PemeliharaanController::class, 'show'])->name('pemeliharaan.show');
    Route::post('/pemeliharaan/{id}/selesai', [PemeliharaanController::class, 'selesai'])->name('pemeliharaan.selesai');
    Route::delete('/pemeliharaan/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
    Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan.index');
});

require __DIR__.'/auth.php';