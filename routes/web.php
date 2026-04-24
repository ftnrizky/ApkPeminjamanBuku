<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\UserBlacklistController;

use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OverdueListController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Models\Alat;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $alats = Alat::all();
    return view('welcome', ['alats' => $alats]);
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth', 'blacklist'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'blacklist', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/pdf', [AdminController::class, 'exportPdf'])->name('admin.dashboard.pdf');

    // User
    Route::get('/admin/users', [UserController::class, 'users'])->name('admin.kelola_user');
    Route::get('/admin/users/export-pdf', [UserController::class, 'exportPdf'])->name('admin.users.export_pdf');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Kategori
    Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/admin/kategori/store', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::put('/admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // Alat
    Route::get('/admin/alat', [AlatController::class, 'alat'])->name('admin.alat');
    Route::get('/admin/alat/export-pdf', [AlatController::class, 'exportPdf'])->name('admin.alat.export_pdf');
    Route::post('/admin/alat/store', [AlatController::class, 'storeAlat'])->name('admin.alat.store');
    Route::put('/admin/alat/{id}', [AlatController::class, 'update'])->name('admin.alat.update');
    Route::delete('/admin/alat/{id}', [AlatController::class, 'destroy'])->name('admin.alat.destroy');

    // Peminjaman
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'peminjaman'])->name('admin.peminjaman');
    Route::get('/admin/peminjaman/export-pdf', [PeminjamanController::class, 'exportPeminjamanPdf'])->name('admin.peminjaman.export_pdf');
    Route::post('/admin/peminjaman/store', [PeminjamanController::class, 'storePeminjaman'])->name('admin.peminjaman.store');
    Route::patch('/admin/peminjaman/verifikasi/{id}', [PeminjamanController::class, 'verifikasiPeminjaman'])->name('admin.peminjaman.verifikasi');
    Route::delete('/admin/peminjaman/{id}', [PeminjamanController::class, 'destroyPeminjaman'])->name('admin.peminjaman.destroy');
    Route::patch('/admin/peminjaman/update/{id}', [PeminjamanController::class, 'updatePeminjaman'])->name('admin.peminjaman.update');
    Route::patch('/admin/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikanPeminjaman'])->name('admin.peminjaman.kembalikan');

    // Pengembalian
    Route::get('/admin/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('admin.pengembalian');
    Route::get('/admin/pengembalian/export-pdf', [PeminjamanController::class, 'exportPengembalianPdf'])->name('admin.pengembalian.export_pdf');

    // Activity Log
    Route::get('/admin/activity-log', [ActivityLogController::class, 'index'])->name('admin.activity_log');
    Route::get('/admin/activity-log/export-pdf', [ActivityLogController::class, 'exportPdf'])->name('admin.activity_log.export_pdf');

    // Overdue
    Route::get('/admin/overdue-list', [OverdueListController::class, 'index'])->name('admin.overdue_list');
    Route::post('/admin/overdue-list/reminder/{id}', [OverdueListController::class, 'sendReminder'])->name('admin.overdue_reminder');

    // =========================================================
    // 💰 DENDA ADMIN
    // =========================================================
    Route::get('/admin/denda',                   [DendaController::class, 'index'])           ->name('admin.denda');
    Route::get('/admin/denda/json',              [DendaController::class, 'getDataJson'])     ->name('admin.denda.json');
    Route::get('/admin/denda/export-pdf',        [DendaController::class, 'exportPdf'])       ->name('admin.denda.export_pdf');
    Route::post('/admin/bayar-denda/{id}',       [DendaController::class, 'bayarDenda'])      ->name('admin.bayar.denda');
    Route::post('/admin/upload-bukti-denda/{id}',[DendaController::class, 'uploadBukti'])     ->name('admin.denda.upload_bukti');
    // PERBAIKAN: verifikasi pakai POST agar kompatibel dengan fetch JS (sebelumnya PATCH)
    Route::post('/admin/verifikasi-denda/{id}',  [DendaController::class, 'verifikasiDenda']) ->name('admin.verifikasi.denda');
    // Kirim notif reminder dari admin (BARU)
    Route::post('/admin/denda/{id}/kirim-notif', [DendaController::class, 'kirimNotifDenda']) ->name('admin.denda.kirim_notif');
});

Route::prefix('admin')->middleware(['auth', 'blacklist', 'role:admin'])->group(function () {
    Route::get('/users/blacklist', [UserBlacklistController::class, 'index'])->name('admin.users.blacklist.index');
    Route::put('/users/{id}/blacklist', [UserBlacklistController::class, 'blacklist'])->name('admin.users.blacklist');
    Route::put('/users/{id}/unblacklist', [UserBlacklistController::class, 'unblacklist'])->name('admin.users.unblacklist');
});


/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'blacklist', 'role:petugas'])->group(function () {

    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');

    Route::get('/petugas/menyetujui_peminjaman', [PetugasController::class, 'menyetujuiPeminjaman'])->name('petugas.menyetujui_peminjaman');
    Route::patch('/petugas/peminjaman/{id}/proses', [PetugasController::class, 'prosesPersetujuanPinjam'])->name('petugas.pinjam.proses');

    Route::get('/petugas/menyetujui_pengembalian', [PetugasController::class, 'menyetujuiPengembalian'])->name('petugas.menyetujui_kembali');
    Route::patch('/petugas/kembali/proses/{id}', [PetugasController::class, 'prosesKonfirmasiKembali'])->name('petugas.kembali.proses');

    Route::post('/petugas/pengingat/{id}', [PetugasController::class, 'kirimPengingat'])->name('petugas.pengingat');

    Route::get('/petugas/laporan', [PetugasController::class, 'cetakLaporan'])->name('petugas.laporan');
    Route::get('/petugas/laporan/pdf', [PetugasController::class, 'exportPdf'])->name('petugas.laporan.pdf');

    Route::get('/petugas/overdue-list', [OverdueListController::class, 'staffIndex'])->name('petugas.overdue_list');
    Route::post('/petugas/overdue-list/reminder/{id}', [OverdueListController::class, 'sendReminder'])->name('petugas.overdue_reminder');

    // =========================================================
    // 💰 DENDA PETUGAS
    // =========================================================
    Route::get('/petugas/denda',                   [PetugasController::class, 'denda'])           ->name('petugas.denda');
    Route::post('/petugas/denda/{id}/cash',        [PetugasController::class, 'catatBayarCash'])  ->name('petugas.denda.cash');
    Route::post('/petugas/denda/{id}/verifikasi',  [PetugasController::class, 'verifikasiBukti']) ->name('petugas.denda.verifikasi');
    // PERBAIKAN: route notif yang menyebabkan error "method does not exist"
    Route::post('/petugas/denda/{id}/notif',       [PetugasController::class, 'kirimNotifDenda']) ->name('petugas.denda.notif');
});


/*
|--------------------------------------------------------------------------
| NOTIFIKASI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'blacklist'])->group(function () {
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');
});


/*
|--------------------------------------------------------------------------
| PEMINJAM
|--------------------------------------------------------------------------
*/
Route::prefix('peminjam')->middleware(['auth', 'blacklist', 'role:peminjam'])->group(function () {

    Route::get('/dashboard',    [PeminjamController::class, 'index'])->name('peminjam.dashboard');
    Route::get('/katalog',      [PeminjamController::class, 'katalog'])->name('peminjam.katalog');
    Route::get('/ajukan/{id}',  [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjam.ajukan');
    Route::post('/pinjam',      [PeminjamController::class, 'storePeminjaman'])->name('peminjam.store');

    Route::get('/pengembalian', [PeminjamController::class, 'kembali'])->name('peminjam.kembali');
    Route::post('/kembali/{id}',[PeminjamController::class, 'prosesKembali'])->name('peminjam.proses_kembali');

    Route::get('/riwayat',      [PeminjamController::class, 'riwayat'])->name('peminjam.riwayat');

    // =========================================================
    // 💰 DENDA PEMINJAM
    // =========================================================

    // Halaman list denda milik peminjam
    Route::get('/denda',                    [PeminjamController::class, 'dendaSaya'])        ->name('peminjam.denda');

    // Form detail bayar (opsional — halaman terpisah, bisa dilewati jika pakai modal)
    Route::get('/bayar-denda/{dendaId}',    [PeminjamController::class, 'formBayarDenda'])   ->name('peminjam.bayar.denda');

    // BARU: proses bayar cash (POST dari modal, tandai niat bayar cash)
    Route::post('/denda/{id}/bayar',        [PeminjamController::class, 'prosesBayarDenda']) ->name('peminjam.denda.bayar');

    // Upload bukti QRIS/transfer (menggantikan route lama yang namanya berbeda)
    // Route lama : POST /upload-bukti-denda/{dendaId}  → name: peminjam.upload.bukti.denda
    // Route baru : POST /denda/{id}/bukti              → name: peminjam.denda.bukti
    // Kedua route AKTIF agar tidak breaking change pada kode lama yang masih pakai nama lama
    Route::post('/upload-bukti-denda/{dendaId}', [PeminjamController::class, 'uploadBuktiDenda'])->name('peminjam.upload.bukti.denda');
    Route::post('/denda/{id}/bukti',             [PeminjamController::class, 'uploadBuktiDenda'])->name('peminjam.denda.bukti');
});
