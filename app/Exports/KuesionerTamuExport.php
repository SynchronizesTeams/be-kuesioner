<?php

namespace App\Exports;

use App\Models\KuesionerTamu;
use Maatwebsite\Excel\Concerns\FromCollection;

class KuesionerTamuExport implements FromCollection
{
      /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return KuesionerTamu::select([
        'nama',
        'instansi',
        'tampilan_produk',
        'tampilan_stand',
        'penjelasan_produk',
        'hiburan',
        'kritik_saran',
        ])->get();
    }

    public function headings() {
        return [
            'Nama',
            'Instansi',
            'Tampilan Produk',
            'Penjelasan Produk',
            'Hiburan',
            'Kritik & Saran'
        ];
    }
}
