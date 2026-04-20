<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $fillable = [
        'nama_alat',
        'slug',
        'kategori',
        'kategori_id',
        'deskripsi',
        'stok_total',
        'stok_tersedia',
        'foto',
        'harga_sewa',
        'kondisi'
    ];

    public function kategoriModel()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
