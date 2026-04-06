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
        'total_denda',
        'catatan'
    ];

        protected $casts = [
        'tgl_pinjam' => 'datetime',
        'tgl_kembali' => 'datetime',
        'tgl_dikembalikan' => 'datetime',
    ];

    /**
     * Relasi ke User (Siapa yang meminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Alat (Barang apa yang dipinjam)
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}
