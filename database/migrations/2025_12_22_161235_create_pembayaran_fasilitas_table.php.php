<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran_fasilitas', function (Blueprint $table) {
            $table->bigIncrements('bayar_id');

            $table->unsignedBigInteger('pinjam_id');

            $table->date('tanggal');
            $table->decimal('jumlah', 12, 2);
            $table->string('metode', 50); // cash / transfer / qris
            $table->string('keterangan')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('pinjam_id')
                ->references('pinjam_id')
                ->on('peminjaman_fasilitas')
                ->onDelete('cascade');

            $table->index(['pinjam_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_fasilitas');
    }
};
