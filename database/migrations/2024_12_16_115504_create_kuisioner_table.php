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
        Schema::create('kuisioner', function (Blueprint $table) {
            $kelas = [
                'X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XI RPL 3',  'XII RPL 1', 'XII RPL 2', 'XII RPL 3',
                'X TJKT 1', 'X TJKT 2', 'X TJKT 3', 'XI TKJ 1', 'XI TKJ 2', 'XII TKJ 1', 'XII TKJ 2',
                'X DKV 1', 'X DKV 2', 'X DKV 3', 'X DKV 4', 
                'XI MM 1', 'XI MM 2', 'XI MM 3', 'XI MM 4',
                'XII MM 1', 'XII MM 2', 'XII MM 3', 'XII MM 4',
                'X LPB 1', 'X LPB 2', 'XI PKM 1', 'XI PKM 2', 'XII PKM 1', 'XII PKM 2'
            ];
            $table->id();
            $table->string('nama_wali_siswa');
            $table->string('nama_siswa');
            $table->enum('kelas',$kelas);
            $table->enum('tampilan_produk', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('tampilan_stand', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('penjelasan_produk', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->enum('hiburan', ['baik', 'cukup', 'kurang', 'buruk']);
            $table->string('kritik_saran');
            $table->string('user_ip')->nullable();
            $table->timestamps();

            //FK
            $table->index('user_ip');
            $table->foreign('user_ip')->references('user_ip')->on('user_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuisioner');
    }
};
