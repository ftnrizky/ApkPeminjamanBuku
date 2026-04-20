<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'activity_type',
        'activity_description',
        'related_model',
        'related_id',
        'data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log aktivitas secara otomatis dari user yang sedang login
     */
    public static function log(
        string $activityType,
        string $description,
        ?string $relatedModel = null,
        mixed $relatedId = null,
        mixed $data = null,
        ?int $userId = null,
        ?string $userName = null,
        ?string $userRole = null
    ): self {
        // Boleh override user (berguna untuk login/logout sebelum session penuh)
        $user = auth()->user();

        return self::create([
            'user_id'              => $userId ?? $user?->id,
            'user_name'            => $userName ?? $user?->name,
            'user_role'            => $userRole ?? $user?->role,
            'activity_type'        => $activityType,
            'activity_description' => $description,
            'related_model'        => $relatedModel,
            'related_id'           => $relatedId,
            'data'                 => $data,
            'ip_address'           => request()->ip(),
            'user_agent'           => request()->userAgent(),
        ]);
    }

    // ── Badge & Icon ──────────────────────────────────────────────────────────

    public function getActivityBadgeColorAttribute(): string
    {
        return match ($this->activity_type) {
            'login'           => 'bg-cyan-100 text-cyan-700',
            'logout'          => 'bg-slate-100 text-slate-600',
            'pinjam'          => 'bg-teal-100 text-teal-700',
            'kembali'         => 'bg-blue-100 text-blue-700',
            'setujui_pinjam'  => 'bg-emerald-100 text-emerald-700',
            'setujui_kembali' => 'bg-green-100 text-green-700',
            'tolak'           => 'bg-rose-100 text-rose-700',
            'tambah_alat'     => 'bg-violet-100 text-violet-700',
            'edit_alat'       => 'bg-amber-100 text-amber-700',
            'hapus_alat'      => 'bg-red-100 text-red-700',
            'register'        => 'bg-indigo-100 text-indigo-700',
            default           => 'bg-slate-100 text-slate-700',
        };
    }

    public function getActivityIconAttribute(): string
    {
        return match ($this->activity_type) {
            'login'           => 'fa-sign-in-alt',
            'logout'          => 'fa-sign-out-alt',
            'pinjam'          => 'fa-arrow-down',
            'kembali'         => 'fa-arrow-up',
            'setujui_pinjam'  => 'fa-check-circle',
            'setujui_kembali' => 'fa-check-double',
            'tolak'           => 'fa-times-circle',
            'tambah_alat'     => 'fa-plus-circle',
            'edit_alat'       => 'fa-edit',
            'hapus_alat'      => 'fa-trash',
            'register'        => 'fa-user-plus',
            default           => 'fa-info-circle',
        };
    }
}