<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('peminjaman', function (Blueprint $table) {
        $table->boolean('is_denda_lunas')->default(false);
        $table->integer('denda_dibayar')->default(0);
        $table->string('metode_bayar')->nullable();
        $table->string('bukti_bayar')->nullable();
        $table->timestamp('tanggal_bayar')->nullable();
        $table->enum('status_pembayaran', ['pending','diterima','ditolak'])->default('pending');
    });
}

public function down(): void
{
    Schema::table('peminjaman', function (Blueprint $table) {
        $table->dropColumn([
            'is_denda_lunas',
            'denda_dibayar',
            'metode_bayar',
            'bukti_bayar',
            'tanggal_bayar',
            'status_pembayaran'
        ]);
    });
}
};
