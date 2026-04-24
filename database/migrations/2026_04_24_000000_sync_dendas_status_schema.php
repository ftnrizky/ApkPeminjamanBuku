<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dendas')) {
            return;
        }

        if (!Schema::hasColumn('dendas', 'catatan_penolakan')) {
            DB::statement('ALTER TABLE dendas ADD COLUMN catatan_penolakan TEXT NULL AFTER catatan');
        }

        DB::statement("
            ALTER TABLE dendas
            MODIFY status_pembayaran ENUM(
                'belum_bayar',
                'pending',
                'menunggu_cash',
                'diterima',
                'ditolak',
                'lunas'
            ) NOT NULL DEFAULT 'belum_bayar'
        ");
    }

    public function down(): void
    {
        if (!Schema::hasTable('dendas')) {
            return;
        }

        DB::statement("
            ALTER TABLE dendas
            MODIFY status_pembayaran ENUM(
                'pending',
                'diterima',
                'ditolak'
            ) NOT NULL DEFAULT 'pending'
        ");

        if (Schema::hasColumn('dendas', 'catatan_penolakan')) {
            DB::statement('ALTER TABLE dendas DROP COLUMN catatan_penolakan');
        }
    }
};
