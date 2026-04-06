<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.kelola_user');

    // CRUD ALAT
    Route::get('/admin/alat', [AdminController::class, 'alat'])->name('admin.alat');
    Route::post('/admin/alat/store', [AdminController::class, 'storeAlat'])->name('admin.alat.store');
    Route::put('/admin/alat/{id}', [AdminController::class, 'update'])->name('admin.alat.update');
    Route::delete('/admin/alat/{id}', [AdminController::class, 'destroy'])->name('admin.alat.destroy');

    // CRUD PEMINJAMAN
    Route::get('/admin/peminjaman', [AdminController::class, 'peminjaman'])->name('admin.peminjaman');
    Route::post('/admin/peminjaman/store', [AdminController::class, 'storePeminjaman'])->name('admin.peminjaman.store');
    Route::patch('/admin/peminjaman/kembalikan/{id}', [AdminController::class, 'kembalikanAlat'])->name('admin.peminjaman.kembalikan');
    Route::delete('/admin/peminjaman/{id}', [AdminController::class, 'destroyPeminjaman'])->name('admin.peminjaman.destroy');
    Route::get('/admin/pengembalian', [AdminController::class, 'pengembalian'])->name('admin.pengembalian');
});

// PETUGAS
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');

    //Menyetujui Peminjaman
    Route::get('/petugas/menyetujui_peminjaman', [PetugasController::class, 'menyetujuiPeminjaman'])->name('petugas.menyetujui_pinjam');
    Route::patch('/petugas/peminjaman/{id}/setuju', [PetugasController::class, 'prosesPersetujuanPinjam'])->name('petugas.pinjam.proses');

    Route::get('/petugas/menyetujui_pengembalian', [PetugasController::class, 'menyetujuiPengembalian'])->name('petugas.menyetujui_kembali');
    Route::patch('/petugas/pengembalian/{id}/konfirmasi', [PetugasController::class, 'prosesKonfirmasiKembali'])->name('petugas.kembali.proses');
    
    Route::get('/petugas/laporan', [PetugasController::class, 'cetakLaporan'])->name('petugas.laporan');
});

// PEMINJAM
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/peminjam/dashboard', [PeminjamController::class, 'index'])->name('peminjam.dashboard');

    //Peminjaman Alat
    Route::get('/peminjam/ajukan/{id}', [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjam.ajukan');
    Route::post('/peminjam/pengajuan/store', [PeminjamController::class, 'storePeminjaman'])->name('peminjam.pengajuan.store');

    Route::get('/peminjam/pengembalian', [PeminjamController::class, 'kembali'])->name('peminjam.kembali');
    Route::put('/peminjam/kembali/{id}', [PeminjamController::class, 'prosesKembali'])->name('peminjam.proses_kembali');
});
