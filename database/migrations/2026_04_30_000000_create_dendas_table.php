<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('peminjaman_id');

            $table->foreign('peminjaman_id')
                ->references('id')
                ->on('peminjaman')
                ->onDelete('cascade');

            $table->integer('total_denda')->default(0);
            $table->boolean('is_denda_lunas')->default(false);

            $table->enum('status_pembayaran', [
                'belum_bayar',
                'pending',
                'menunggu_cash',
                'diterima',
                'ditolak',
                'lunas',
            ])->default('belum_bayar');

            $table->text('catatan')->nullable();
            $table->text('catatan_penolakan')->nullable();

            $table->string('metode_bayar')->nullable();
            $table->timestamp('tgl_bayar')->nullable();
            $table->string('bukti_bayar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
