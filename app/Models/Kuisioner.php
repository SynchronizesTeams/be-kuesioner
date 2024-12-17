<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuisioner extends Model
{
    protected $table = 'kuisioner';

    protected $fillable = [
        'nama_wali_siswa',
        'nama_siswa',
        'kelas',
        'tampilan_produk',
        'tampilan_stand',
        'penjelasan_produk',
        'hiburan',
        'kritik_saran',
        'user_ip',
    ];
}
