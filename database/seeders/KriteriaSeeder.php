<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Data kriteria berdasarkan data yang diberikan
        $dataKriteria = [
            [
                'nama' => 'Pengalaman',
                'jenis' => 'benefit', // Pengalaman biasanya benefit (semakin tinggi semakin baik)
            ],
            [
                'nama' => 'Jarak',
                'jenis' => 'cost', // Jarak biasanya cost (semakin kecil semakin baik)
            ],
            [
                'nama' => 'Komunikasi',
                'jenis' => 'benefit', // Kemampuan komunikasi benefit
            ],
            [
                'nama' => 'Fleksibilitas',
                'jenis' => 'benefit', // Fleksibilitas benefit
            ],
        ];

        // Hapus data lama jika ada (optional)
        // Kriteria::truncate(); // Hati-hati dengan foreign key constraint

        // Atau gunakan cara yang lebih aman dengan delete
        Kriteria::query()->delete();

        // Reset auto increment (untuk MySQL)
        if (config('database.default') === 'mysql') {
            \DB::statement('ALTER TABLE kriterias AUTO_INCREMENT = 1');
        }

        // Insert data kriteria
        foreach ($dataKriteria as $index => $kriteria) {
            Kriteria::create([
                'nama' => $kriteria['nama'],
                'jenis' => $kriteria['jenis'],
                // Tambahkan kode jika ada field kode
                // 'kode' => 'C' . ($index + 1),
            ]);
        }

        $this->command->info('Data kriteria berhasil ditambahkan!');
        $this->command->info('Total data: ' . Kriteria::count());
    }
}
