<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('dendas')) {
            return;
        }

        Schema::table('dendas', function (Blueprint $table) {
            if (!Schema::hasColumn('dendas', 'tgl_bayar')) {
                $table->timestamp('tgl_bayar')->nullable()->after('metode_bayar');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('dendas') || !Schema::hasColumn('dendas', 'tgl_bayar')) {
            return;
        }

        Schema::table('dendas', function (Blueprint $table) {
            $table->dropColumn('tgl_bayar');
        });
    }
};
