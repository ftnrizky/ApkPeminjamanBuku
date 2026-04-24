<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    // ── AUTH ─────────────────────────────────────────────────────────────────

    public static function login($user): void
    {
        ActivityLog::log(
            activityType: 'login',
            description: "{$user->name} berhasil login ke sistem.",
            relatedModel: 'User',
            relatedId: $user->id,
            userId: $user->id,
            userName: $user->name,
            userRole: $user->role,
        );
    }

    public static function logout($user): void
    {
        ActivityLog::log(
            activityType: 'logout',
            description: "{$user->name} logout dari sistem.",
            relatedModel: 'User',
            relatedId: $user->id,
            userId: $user->id,
            userName: $user->name,
            userRole: $user->role,
        );
    }

    public static function register($user): void
    {
        ActivityLog::log(
            activityType: 'register',
            description: "Akun baru terdaftar: {$user->name} ({$user->email}) sebagai {$user->role}.",
            relatedModel: 'User',
            relatedId: $user->id,
            userId: $user->id,
            userName: $user->name,
            userRole: $user->role,
        );
    }

    // ── PEMINJAMAN (PEMINJAM) ─────────────────────────────────────────────────

    public static function ajukanPeminjaman($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'pinjam',
            description: "{$user->name} mengajukan peminjaman '{$peminjaman->alat->nama_alat}' sebanyak {$peminjaman->jumlah} unit. Tgl kembali: {$peminjaman->tgl_kembali}.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'alat'        => $peminjaman->alat->nama_alat,
                'jumlah'      => $peminjaman->jumlah,
                'tgl_pinjam'  => $peminjaman->tgl_pinjam,
                'tgl_kembali' => $peminjaman->tgl_kembali,
                'tujuan'      => $peminjaman->tujuan,
            ],
        );
    }

    public static function ajukanKembali($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'kembali',
            description: "{$user->name} mengajukan pengembalian '{$peminjaman->alat->nama_alat}'.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'alat'   => $peminjaman->alat->nama_alat,
                'jumlah' => $peminjaman->jumlah,
            ],
        );
    }

    // ── PEMINJAMAN (PETUGAS) ──────────────────────────────────────────────────

    public static function setujuiPeminjaman($peminjaman, int $jumlahDisetujui): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'setujui_pinjam',
            description: "{$user->name} menyetujui peminjaman '{$peminjaman->alat->nama_alat}' untuk {$peminjaman->user->name} sebanyak {$jumlahDisetujui} unit.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam'         => $peminjaman->user->name,
                'alat'             => $peminjaman->alat->nama_alat,
                'jumlah_disetujui' => $jumlahDisetujui,
            ],
        );
    }

    public static function tolakPeminjaman($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'tolak',
            description: "{$user->name} menolak peminjaman '{$peminjaman->alat->nama_alat}' dari {$peminjaman->user->name}.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam' => $peminjaman->user->name,
                'alat'     => $peminjaman->alat->nama_alat,
            ],
        );
    }

    public static function konfirmasiKembali($peminjaman, float $totalDenda, string $ringkasan): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'setujui_kembali',
            description: "{$user->name} mengkonfirmasi pengembalian '{$peminjaman->alat->nama_alat}' dari {$peminjaman->user->name}. Total denda: Rp " . number_format($totalDenda, 0, ',', '.') . ".",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam'    => $peminjaman->user->name,
                'alat'        => $peminjaman->alat->nama_alat,
                'kondisi'     => $ringkasan,
                'total_denda' => $totalDenda,
            ],
        );
    }

    // ── PEMINJAMAN (ADMIN) ────────────────────────────────────────────────────

    public static function adminBuatPeminjaman($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'pinjam',
            description: "Admin {$user->name} membuat peminjaman untuk {$peminjaman->user->name}: '{$peminjaman->alat->nama_alat}' x {$peminjaman->jumlah} unit.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam'    => $peminjaman->user->name,
                'alat'        => $peminjaman->alat->nama_alat,
                'jumlah'      => $peminjaman->jumlah,
                'tgl_kembali' => $peminjaman->tgl_kembali,
            ],
        );
    }

    public static function adminSetujuiPeminjaman($peminjaman, int $jumlahDisetujui): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'setujui_pinjam',
            description: "Admin {$user->name} menyetujui peminjaman '{$peminjaman->alat->nama_alat}' untuk {$peminjaman->user->name} sebanyak {$jumlahDisetujui} unit.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam'         => $peminjaman->user->name,
                'alat'             => $peminjaman->alat->nama_alat,
                'jumlah_disetujui' => $jumlahDisetujui,
            ],
        );
    }

    public static function adminTolakPeminjaman($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'tolak',
            description: "Admin {$user->name} menolak peminjaman '{$peminjaman->alat->nama_alat}' dari {$peminjaman->user->name}.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam' => $peminjaman->user->name,
                'alat'     => $peminjaman->alat->nama_alat,
            ],
        );
    }

    public static function adminKembalikanAlat($peminjaman, string $kondisi, float $totalDenda): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'setujui_kembali',
            description: "Admin {$user->name} memproses pengembalian '{$peminjaman->alat->nama_alat}' dari {$peminjaman->user->name}. Kondisi: {$kondisi}. Total denda: Rp " . number_format($totalDenda, 0, ',', '.') . ".",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam'    => $peminjaman->user->name,
                'alat'        => $peminjaman->alat->nama_alat,
                'kondisi'     => $kondisi,
                'total_denda' => $totalDenda,
            ],
        );
    }

    public static function adminHapusPeminjaman($peminjaman): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'hapus_alat',
            description: "Admin {$user->name} menghapus data peminjaman '{$peminjaman->alat->nama_alat}' milik {$peminjaman->user->name}.",
            relatedModel: 'Peminjaman',
            relatedId: $peminjaman->id,
            data: [
                'peminjam' => $peminjaman->user->name,
                'alat'     => $peminjaman->alat->nama_alat,
                'status'   => $peminjaman->status,
            ],
        );
    }

    // ── ALAT (ADMIN) ──────────────────────────────────────────────────────────

    public static function tambahAlat($alat): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'tambah_alat',
            description: "Admin {$user->name} menambahkan alat baru: '{$alat->nama_alat}' (Kategori: {$alat->kategori}, Stok: {$alat->stok_total}).",
            relatedModel: 'Alat',
            relatedId: $alat->id,
            data: [
                'nama_alat'  => $alat->nama_alat,
                'kategori'   => $alat->kategori,
                'stok_total' => $alat->stok_total,
                'kondisi'    => $alat->kondisi,
                'harga_sewa' => $alat->harga_sewa,
            ],
        );
    }

    public static function editAlat($alat): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'edit_alat',
            description: "Admin {$user->name} memperbarui data alat: '{$alat->nama_alat}'.",
            relatedModel: 'Alat',
            relatedId: $alat->id,
            data: [
                'nama_alat'  => $alat->nama_alat,
                'kategori'   => $alat->kategori,
                'stok_total' => $alat->stok_total,
                'kondisi'    => $alat->kondisi,
                'harga_sewa' => $alat->harga_sewa,
            ],
        );
    }

    public static function hapusAlat($alat): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'hapus_alat',
            description: "Admin {$user->name} menghapus alat: '{$alat->nama_alat}' dari katalog.",
            relatedModel: 'Alat',
            relatedId: $alat->id,
            data: [
                'nama_alat' => $alat->nama_alat,
                'kategori'  => $alat->kategori,
            ],
        );
    }

    // ── USER (ADMIN) ──────────────────────────────────────────────────────────

    public static function tambahUser($targetUser): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'register',
            description: "Admin {$user->name} menambahkan user baru: '{$targetUser->name}' ({$targetUser->email}) sebagai {$targetUser->role}.",
            relatedModel: 'User',
            relatedId: $targetUser->id,
            data: [
                'name'  => $targetUser->name,
                'email' => $targetUser->email,
                'role'  => $targetUser->role,
            ],
        );
    }

    public static function editUser($targetUser): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'edit_alat',
            description: "Admin {$user->name} memperbarui data user: '{$targetUser->name}' ({$targetUser->email}), role: {$targetUser->role}.",
            relatedModel: 'User',
            relatedId: $targetUser->id,
            data: [
                'name'           => $targetUser->name,
                'email'          => $targetUser->email,
                'role'           => $targetUser->role,
                'is_blacklisted' => $targetUser->is_blacklisted,
            ],
        );
    }

    public static function hapusUser($targetUser): void
    {
        $user = auth()->user();
        ActivityLog::log(
            activityType: 'hapus_alat',
            description: "Admin {$user->name} menghapus user: '{$targetUser->name}' ({$targetUser->email}).",
            relatedModel: 'User',
            relatedId: $targetUser->id,
            data: [
                'name'  => $targetUser->name,
                'email' => $targetUser->email,
                'role'  => $targetUser->role,
            ],
        );
    }
}