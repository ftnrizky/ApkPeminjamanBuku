<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log activity dengan informasi user dan IP
     */
    public static function log(
        string $activityType,
        string $description,
        ?string $relatedModel = null,
        ?int $relatedId = null,
        array $data = []
    ): ActivityLog {
        $user = Auth::user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Guest',
            'user_role' => $user?->role ?? 'unknown',
            'activity_type' => $activityType,
            'activity_description' => $description,
            'related_model' => $relatedModel,
            'related_id' => $relatedId,
            'data' => !empty($data) ? $data : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    /**
     * Log login activity
     */
    public static function logLogin(string $username, string $role): ActivityLog
    {
        return self::log(
            'login',
            "User {$username} login ke sistem",
            'User',
            Auth::id(),
            ['role' => $role]
        );
    }

    /**
     * Log logout activity
     */
    public static function logLogout(string $username): ActivityLog
    {
        return self::log(
            'logout',
            "User {$username} logout dari sistem",
            'User',
            Auth::id()
        );
    }

    /**
     * Log peminjaman activity
     */
    public static function logPeminjaman(int $peminjamanId, string $alat, string $peminjam): ActivityLog
    {
        return self::log(
            'pinjam',
            "Peminjaman dibuat: {$peminjam} meminjam {$alat}",
            'Peminjaman',
            $peminjamanId,
            ['alat' => $alat, 'peminjam' => $peminjam]
        );
    }

    /**
     * Log approval peminjaman
     */
    public static function logApprovalPeminjaman(int $peminjamanId, string $peminjam, string $alat): ActivityLog
    {
        return self::log(
            'setujui_pinjam',
            "Peminjaman disetujui: {$peminjam} untuk {$alat}",
            'Peminjaman',
            $peminjamanId,
            ['peminjam' => $peminjam, 'alat' => $alat]
        );
    }

    /**
     * Log rejection peminjaman
     */
    public static function logRejectionPeminjaman(int $peminjamanId, string $peminjam, string $alat): ActivityLog
    {
        return self::log(
            'tolak_pinjam',
            "Peminjaman ditolak: {$peminjam} untuk {$alat}",
            'Peminjaman',
            $peminjamanId,
            ['peminjam' => $peminjam, 'alat' => $alat]
        );
    }

    /**
     * Log pengembalian
     */
    public static function logPengembalian(int $peminjamanId, string $peminjam, string $alat): ActivityLog
    {
        return self::log(
            'kembali',
            "Pengembalian: {$peminjam} mengembalikan {$alat}",
            'Peminjaman',
            $peminjamanId,
            ['peminjam' => $peminjam, 'alat' => $alat]
        );
    }

    /**
     * Log approval pengembalian
     */
    public static function logApprovalPengembalian(
        int $peminjamanId,
        string $peminjam,
        string $alat,
        string $kondisi,
        ?float $denda = null
    ): ActivityLog {
        return self::log(
            'setujui_kembali',
            "Pengembalian disetujui: {$peminjam} mengembalikan {$alat} (Kondisi: {$kondisi})",
            'Peminjaman',
            $peminjamanId,
            ['peminjam' => $peminjam, 'alat' => $alat, 'kondisi' => $kondisi, 'denda' => $denda]
        );
    }

    /**
     * Log reminder sent
     */
    public static function logReminder(int $peminjamanId, string $peminjam): ActivityLog
    {
        return self::log(
            'reminder',
            "Pengingat pengembalian dikirim untuk {$peminjam}",
            'Peminjaman',
            $peminjamanId,
            ['peminjam' => $peminjam]
        );
    }

    /**
     * Log user action
     */
    public static function logUserAction(string $action, string $targetUser, int $targetUserId, array $details = []): ActivityLog
    {
        return self::log(
            'user_action',
            "{$action}: {$targetUser}",
            'User',
            $targetUserId,
            $details
        );
    }
}
