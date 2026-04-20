<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    
    protected $fillable = [
        'nama',
        'icon',
        'deskripsi',
        'warna'
    ];

    public function alats()
    {
        return $this->hasMany(Alat::class);
    }
}
