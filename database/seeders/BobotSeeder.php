<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\bobot;
use App\Models\kriteria;
use Illuminate\Support\Facades\DB;

class BobotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Data bobot berdasarkan tabel yang diberikan
        $dataBobot = [
            'Pengalaman' => 30,
            'Jarak' => 25,
            'Komunikasi' => 25,
            'Fleksibilitas' => 20,
        ];

        // Hapus data lama jika ada
        Bobot::query()->delete();

        // Reset auto increment (untuk MySQL)
        if (config('database.default') === 'mysql') {
            \DB::statement('ALTER TABLE bobots AUTO_INCREMENT = 1');
        }

        $insertedCount = 0;

        // Insert data bobot
        foreach ($dataBobot as $namaKriteria => $nilaiBobot) {
            // Cari kriteria berdasarkan nama
            $kriteria = Kriteria::where('nama', $namaKriteria)->first();
            
            if ($kriteria) {
                Bobot::create([
                    'kriteria_id' => $kriteria->id,
                    'bobot' => $nilaiBobot,
                ]);
                $insertedCount++;
            } else {
                $this->command->error("Kriteria '{$namaKriteria}' tidak ditemukan!");
                $this->command->error("Pastikan KriteriaSeeder sudah dijalankan terlebih dahulu!");
            }
        }

        $this->command->info("{$insertedCount} data bobot berhasil ditambahkan!");
    }
}
