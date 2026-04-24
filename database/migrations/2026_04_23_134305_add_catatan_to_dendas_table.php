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
            if (!Schema::hasColumn('dendas', 'catatan')) {
                $table->text('catatan')->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('dendas') || !Schema::hasColumn('dendas', 'catatan')) {
            return;
        }

        Schema::table('dendas', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });
    }
};
