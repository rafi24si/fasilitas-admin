<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjaman_fasilitas', function (Blueprint $table) {
            $table->bigIncrements('pinjam_id');

            $table->unsignedBigInteger('fasilitas_id');
            $table->unsignedBigInteger('warga_id');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->string('tujuan', 255)->nullable();

            // status: menunggu, disetujui, ditolak, selesai, batal
            $table->string('status', 20)->default('menunggu');

            $table->decimal('total_biaya', 12, 2)->default(0);

            $table->timestamps();

            // FK
            $table->foreign('fasilitas_id')
                ->references('fasilitas_id')->on('fasilitas_umum')
                ->onDelete('cascade');

            $table->foreign('warga_id')
                ->references('warga_id')->on('warga')
                ->onDelete('cascade');

            // Index bantu query
            $table->index(['fasilitas_id', 'warga_id']);
            $table->index(['status', 'tanggal_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_fasilitas');
    }
};
