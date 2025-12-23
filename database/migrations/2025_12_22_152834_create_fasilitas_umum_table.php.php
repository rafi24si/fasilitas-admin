<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fasilitas_umum', function (Blueprint $table) {
            $table->id('fasilitas_id');

            $table->string('nama');
            $table->string('jenis'); // aula, lapangan, dll
            $table->text('alamat')->nullable();

            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();

            $table->integer('kapasitas')->nullable();
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_umum');
    }
};
