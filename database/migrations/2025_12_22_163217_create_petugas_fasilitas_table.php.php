<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('petugas_fasilitas', function (Blueprint $table) {
            $table->id('petugas_id');
            $table->unsignedBigInteger('fasilitas_id');
            $table->unsignedBigInteger('petugas_warga_id');
            $table->string('peran'); // contoh: Penanggung Jawab, Admin Lapangan
            $table->timestamps();

            $table->foreign('fasilitas_id')
                  ->references('fasilitas_id')
                  ->on('fasilitas_umum')
                  ->cascadeOnDelete();

            $table->foreign('petugas_warga_id')
                  ->references('warga_id')
                  ->on('warga')
                  ->cascadeOnDelete();

            // satu warga tidak boleh double di fasilitas yang sama
            $table->unique(['fasilitas_id', 'petugas_warga_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_fasilitas');
    }
};

