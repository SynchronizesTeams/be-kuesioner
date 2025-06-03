<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElevenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/csv/XI.2024-2025.csv');

        if (!file_exists($path) || !is_readable($path)) {
            return;
        }

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Asumsikan urutan kolom: name, email, password
                DB::table('users')->insert([
                    'user_id' => uniqid('siswa_'),
                    'nis' => $row[0],
                    'name' => $row[1],
                    'kelas' => $row[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            fclose($handle);
        }
    }
}
