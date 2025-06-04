<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerTamu extends Model
{
    protected $fillable = [
        'nama',
        'instansi',
        'tampilan_produk',
        'tampilan_stand',
        'penjelasan_produk',
        'hiburan',
        'kritik_saran',
    ];
}
