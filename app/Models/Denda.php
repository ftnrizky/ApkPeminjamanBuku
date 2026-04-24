<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'dendas';

    public const STATUS_BELUM_BAYAR   = 'belum_bayar';
    public const STATUS_PENDING       = 'pending';
    public const STATUS_MENUNGGU_CASH = 'menunggu_cash';
    public const STATUS_DITERIMA      = 'diterima';
    public const STATUS_DITOLAK       = 'ditolak';
    public const STATUS_LUNAS         = 'lunas';

    public const STATUSES = [
        self::STATUS_BELUM_BAYAR,
        self::STATUS_PENDING,
        self::STATUS_MENUNGGU_CASH,
        self::STATUS_DITERIMA,
        self::STATUS_DITOLAK,
        self::STATUS_LUNAS,
    ];

    // ─────────────────────────────────────────────
    // DEFAULT VALUE (ANTI NULL ERROR)
    // ─────────────────────────────────────────────
    protected $attributes = [
        'status_pembayaran' => self::STATUS_BELUM_BAYAR,
        'is_denda_lunas'    => false,
    ];

    protected $fillable = [
        'peminjaman_id',
        'hari_terlambat',
        'total_denda',
        'metode_bayar',        // cash | qris
        'status_pembayaran',   // belum_bayar | pending | menunggu_cash | diterima | ditolak | lunas
        'bukti_bayar',
        'catatan',
        'catatan_penolakan',
        'tgl_bayar',
        'is_denda_lunas',
    ];

    protected $casts = [
        'is_denda_lunas' => 'boolean',
        'tgl_bayar'      => 'datetime',
        'total_denda'    => 'integer',
        'hari_terlambat' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $denda): void {
            if (blank($denda->status_pembayaran)) {
                $denda->status_pembayaran = self::STATUS_BELUM_BAYAR;
            }

            if (is_null($denda->is_denda_lunas)) {
                $denda->is_denda_lunas = false;
            }
        });
    }

    // ─────────────────────────────────────────────
    // RELASI
    // ─────────────────────────────────────────────
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // ─────────────────────────────────────────────
    // ACCESSOR
    // ─────────────────────────────────────────────

    public function getTotalDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_denda, 0, ',', '.');
    }

    /**
     * Bisa bayar jika:
     * - belum lunas
     * - tidak sedang pending
     */
    public function getBisaBayarAttribute(): bool
    {
        return !$this->is_denda_lunas
            && !in_array($this->status_pembayaran, [self::STATUS_PENDING, self::STATUS_MENUNGGU_CASH], true);
    }

    /**
     * Label status untuk UI
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_denda_lunas || $this->status_pembayaran === self::STATUS_LUNAS) {
            return 'Lunas';
        }

        return match ($this->status_pembayaran) {
            self::STATUS_BELUM_BAYAR   => 'Belum Bayar',
            self::STATUS_PENDING       => 'Menunggu Verifikasi',
            self::STATUS_MENUNGGU_CASH => 'Menunggu Cash',
            self::STATUS_DITERIMA      => 'Diterima',
            self::STATUS_DITOLAK       => 'Ditolak',
            default          => 'Belum Bayar',
        };
    }

    /**
     * Helper status warna (opsional tapi bagus untuk UI)
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status_pembayaran) {
            self::STATUS_BELUM_BAYAR   => 'red',
            self::STATUS_PENDING       => 'blue',
            self::STATUS_MENUNGGU_CASH => 'yellow',
            self::STATUS_DITERIMA      => 'green',
            self::STATUS_DITOLAK       => 'red',
            self::STATUS_LUNAS         => 'green',
            default         => 'gray',
        };
    }
}
