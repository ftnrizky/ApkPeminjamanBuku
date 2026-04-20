<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Alat;
use App\Models\Kategori;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing alats with kategori_id based on kategori name
        $alats = Alat::all();
        foreach ($alats as $alat) {
            if ($alat->kategori) {
                $kategori = Kategori::where('nama', $alat->kategori)->first();
                if ($kategori) {
                    $alat->kategori_id = $kategori->id;
                    $alat->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset kategori_id to null
        Schema::table('alats', function (Blueprint $table) {
            Alat::query()->update(['kategori_id' => null]);
        });
    }
};
