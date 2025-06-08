<?php

namespace App\Exports;

use App\Models\KuesionerTamu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KuesionerTamuExport implements FromCollection, WithHeadings, WithMapping
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

    public function headings(): array {
        return [
            'No',
            'Nama',
            'Instansi',
            'Tampilan Produk',
            'Penjelasan Produk',
            'Hiburan',
            'Kritik & Saran'
        ];
    }

    public function map($row): array
    {
        static $nomor = 1;

        return [
            $nomor++,
            $row->nama,
            $row->instansi,
            $row->tampilan_produk,
            $row->penjelasan_produk,
            $row->hiburan,
            $row->kritik_saran,
        ];
    }
}
