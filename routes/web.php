<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OverdueListController;
use App\Models\Alat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $alats = Alat::all();
    return view('/welcome', ['alats' => $alats]);
});

Auth::routes();

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/pdf', [AdminController::class, 'exportPdf'])->name('admin.dashboard.pdf');

    // CRUD USER
    Route::get('/admin/users', [UserController::class, 'users'])->name('admin.kelola_user');
    Route::get('/admin/users/export-pdf', [UserController::class, 'exportPdf'])->name('admin.users.export_pdf');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // CRUD KATEGORI
    Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/admin/kategori/store', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::put('/admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // CRUD ALAT
    Route::get('/admin/alat', [AlatController::class, 'alat'])->name('admin.alat');
    Route::get('/admin/alat/export-pdf', [AlatController::class, 'exportPdf'])->name('admin.alat.export_pdf');
    Route::post('/admin/alat/store', [AlatController::class, 'storeAlat'])->name('admin.alat.store');
    Route::put('/admin/alat/{id}', [AlatController::class, 'update'])->name('admin.alat.update');
    Route::delete('/admin/alat/{id}', [AlatController::class, 'destroy'])->name('admin.alat.destroy');

    // CRUD PEMINJAMAN
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'peminjaman'])->name('admin.peminjaman');
    Route::get('/admin/peminjaman/export-pdf', [PeminjamanController::class, 'exportPeminjamanPdf'])->name('admin.peminjaman.export_pdf');
    Route::post('/admin/peminjaman/store', [PeminjamanController::class, 'storePeminjaman'])->name('admin.peminjaman.store');
    Route::patch('/admin/peminjaman/verifikasi/{id}', [PeminjamanController::class, 'verifikasiPeminjaman'])->name('admin.peminjaman.verifikasi');
    Route::delete('/admin/peminjaman/{id}', [PeminjamanController::class, 'destroyPeminjaman'])->name('admin.peminjaman.destroy');
    Route::patch('/admin/peminjaman/update/{id}', [PeminjamanController::class, 'updatePeminjaman'])->name('admin.peminjaman.update');
    Route::patch('/admin/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikanPeminjaman'])->name('admin.peminjaman.kembalikan');
    
    Route::get('/admin/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('admin.pengembalian');
    Route::get('/admin/pengembalian/export-pdf', [PeminjamanController::class, 'exportPengembalianPdf'])->name('admin.pengembalian.export_pdf');

    // ACTIVITY LOG
    Route::get('/admin/activity-log', [ActivityLogController::class, 'index'])->name('admin.activity_log');
    Route::get('/admin/activity-log/export-pdf', [ActivityLogController::class, 'exportPdf'])->name('admin.activity_log.export_pdf');

    // OVERDUE LIST
    Route::get('/admin/overdue-list', [OverdueListController::class, 'index'])->name('admin.overdue_list');
    Route::post('/admin/overdue-list/reminder/{id}', [OverdueListController::class, 'sendReminder'])->name('admin.overdue_reminder');
});

// PETUGAS
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');

    //Menyetujui Peminjaman
    Route::get('/petugas/menyetujui_peminjaman', [PetugasController::class, 'menyetujuiPeminjaman'])->name('petugas.menyetujui_peminjaman');
    Route::patch('/petugas/peminjaman/{id}/proses', [PetugasController::class, 'prosesPersetujuanPinjam'])->name('petugas.pinjam.proses');

    //Menyetujui Pengembalian
    Route::get('/petugas/menyetujui_pengembalian', [PetugasController::class, 'menyetujuiPengembalian'])->name('petugas.menyetujui_kembali');
    Route::patch('/petugas/kembali/proses/{id}', [PetugasController::class, 'prosesKonfirmasiKembali'])->name('petugas.kembali.proses');
    
    // Pengingat Pengembalian
    Route::post('/petugas/pengingat/{id}', [PetugasController::class, 'kirimPengingat'])->name('petugas.pengingat');
    
    Route::get('/petugas/laporan', [PetugasController::class, 'cetakLaporan'])->name('petugas.laporan');
    Route::get('/petugas/laporan/pdf', [PetugasController::class, 'exportPdf'])->name('petugas.laporan.pdf');

    // OVERDUE LIST
    Route::get('/petugas/overdue-list', [OverdueListController::class, 'staffIndex'])->name('petugas.overdue_list');
    Route::post('/petugas/overdue-list/reminder/{id}', [OverdueListController::class, 'sendReminder'])->name('petugas.overdue_reminder');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');
});

// PEMINJAM
Route::prefix('peminjam')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PeminjamController::class, 'index'])->name('peminjam.dashboard');
    
    // Katalog Alat
    Route::get('/katalog', [PeminjamController::class, 'katalog'])->name('peminjam.katalog');
    
    // Peminjaman
    Route::get('/ajukan/{id}', [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjam.ajukan');
    Route::post('/pinjam', [PeminjamController::class, 'storePeminjaman'])->name('peminjam.store');
    
    // Pengembalian
    Route::get('/pengembalian', [PeminjamController::class, 'kembali'])->name('peminjam.kembali');
    Route::post('/kembali/{id}', [PeminjamController::class, 'prosesKembali'])->name('peminjam.proses_kembali');
    
    // Riwayat
    Route::get('/riwayat', [PeminjamController::class, 'riwayat'])->name('peminjam.riwayat');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');