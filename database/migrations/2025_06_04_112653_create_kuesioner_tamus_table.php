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
        Schema::create('kuesioner_tamus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('instansi');
            $table->enum('tampilan_produk', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('tampilan_stand', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('penjelasan_produk', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('hiburan', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->string('kritik_saran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_tamus');
    }
};
