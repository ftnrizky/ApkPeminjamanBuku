<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $fillable = [
        'nama_alat',
        'slug',
        'kategori_id', 
        'stok_total',
        'stok_tersedia',
        'harga_sewa',
        'kondisi',
        'deskripsi',
        'foto',
    ];

    
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}