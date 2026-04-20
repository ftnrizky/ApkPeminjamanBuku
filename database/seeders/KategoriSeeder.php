<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Gaming',
                'icon' => 'fa-gamepad',
                'deskripsi' => 'Laptop dengan performa tinggi untuk gaming dan rendering',
                'warna' => 'purple'
            ],
            [
                'nama' => 'Business',
                'icon' => 'fa-briefcase',
                'deskripsi' => 'Laptop profesional untuk pekerjaan kantoran dan produktivitas',
                'warna' => 'cyan'
            ],
            [
                'nama' => 'Design',
                'icon' => 'fa-pen-fancy',
                'deskripsi' => 'Laptop untuk desain grafis, editing, dan konten kreatif',
                'warna' => 'pink'
            ]
        ];

        foreach ($kategoris as $kategori) {
            Kategori::updateOrCreate(
                ['nama' => $kategori['nama']],
                $kategori
            );
        }
    }
}
