<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('syarat_fasilitas', function (Blueprint $table) {
            $table->id('syarat_id');
            $table->unsignedBigInteger('fasilitas_id');
            $table->string('nama_syarat');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('fasilitas_id')
                  ->references('fasilitas_id')
                  ->on('fasilitas_umum')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_fasilitas');
    }
};

