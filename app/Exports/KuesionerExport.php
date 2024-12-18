<?php

namespace App\Exports;

use App\Models\Kuisioner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KuesionerExport implements FromCollection, WithHeadings, WithMapping
{
    // Mengambil data dari database
    public function collection()
    {
        return Kuisioner::all();
    }

    // Menambahkan header kolom
    public function headings(): array
    {
        return [
            'No',
            'Nama Wali Kelas',
            'Nama Siswa',
            'Kelas',
            'Tampilan Produk',
            'Tampilan Stand',
            'Penjelasan Produk',
            'Hiburan',
            'Kritik & Saran'
        ];
    }

    public function map($kuesioner): array
    {
        static $nomor = 1; // Nomor urut dimulai dari 1

        return [
            $nomor++, 
            $kuesioner->nama_wali_siswa,
            $kuesioner->nama_siswa,
            $kuesioner->kelas,
            $kuesioner->tampilan_produk,
            $kuesioner->tampilan_stand,
            $kuesioner->penjelasan_produk,
            $kuesioner->hiburan,
            $kuesioner->kritik_saran
        ];
    }
}
