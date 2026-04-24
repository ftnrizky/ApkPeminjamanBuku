<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Safe migration: mengubah kolom kondisi dari ENUM ke JSON
     * tanpa merusak data lama.
     */
    public function up(): void
    {
        // Step 1: Backup nilai kondisi lama ke kolom sementara
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->text('kondisi_backup')->nullable()->after('kondisi');
        });

        // Step 2: Pindahkan data lama ke backup
        DB::statement('UPDATE peminjaman SET kondisi_backup = kondisi WHERE kondisi IS NOT NULL');

        // Step 3: Drop kolom kondisi lama (yang ENUM)
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });

        // Step 4: Tambah kolom kondisi baru sebagai JSON
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->json('kondisi')->nullable()->after('kondisi_backup');
        });

        // Step 5: Migrate data lama → format JSON array
        // Contoh: "baik" → ["baik"], "Baik:1, Rusak:1" → tetap disimpan dalam format readable
        DB::statement("
            UPDATE peminjaman 
            SET kondisi = JSON_ARRAY(kondisi_backup)
            WHERE kondisi_backup IS NOT NULL 
              AND kondisi_backup != ''
              AND kondisi_backup NOT LIKE '[%'
        ");

        // Step 6: Hapus kolom backup
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('kondisi_backup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke TEXT agar tidak kehilangan data
        // (tidak bisa kembali ke ENUM karena data sudah berubah format)
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->text('kondisi')->nullable();
        });
    }
};