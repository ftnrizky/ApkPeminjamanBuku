<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'alat_id',
        'jumlah',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_dikembalikan',
        'tujuan',
        'status',
        'kondisi',      // sekarang JSON array, bukan ENUM string
        'total_denda',
        'is_denda_lunas',
        'denda_dibayar',
        'metode_bayar',
        'bukti_bayar',
        'tanggal_bayar',
        'status_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tgl_pinjam'        => 'datetime',
        'tgl_kembali'       => 'datetime',
        'tgl_dikembalikan'  => 'datetime',
        'tanggal_bayar'     => 'datetime',
        'is_denda_lunas'    => 'boolean',
        'kondisi'           => 'array',   // ← FIX UTAMA: cast JSON ↔ PHP array otomatis
    ];

    /**
     * Relasi ke User (Siapa yang meminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Alat (Barang apa yang dipinjam)
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_id');
    }
}
