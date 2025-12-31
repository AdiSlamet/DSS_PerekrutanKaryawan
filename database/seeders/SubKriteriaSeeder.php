<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\sub_kriteria;
use App\Models\kriteria;
use Illuminate\Support\Facades\DB;

class SubKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data sub kriteria untuk setiap kriteria
        $dataSubKriteria = [
            // 1. Pengalaman Kerja Sebagai Barista (benefit)
            'Pengalaman' => [
                [
                    'nama' => 'Tidak Ada Pengalaman - Sangat Kurang(Very Low)',
                    'nilai' => 1,
                ],
                [
                    'nama' => 'hanya ikut pelatihan dan belum pernah bekerja sebelumnya - Kurang(Low)',
                    'nilai' => 2,
                ],
                [
                    'nama' => 'Pernah bekerja < 6 bulan - Cukup (Medium)',
                    'nilai' => 3,
                ],
                [
                    'nama' => 'Pernah bekerja 6–12 bulan - Baik(High)',
                    'nilai' => 4,
                ],
                [
                    'nama' => 'Pernah bekerja > 1 tahun - Sangat Baik(Very High)',
                    'nilai' => 5,
                ],
            ],
            
            // 2. Jarak Tempat Tinggal ke Lokasi Kerja (cost)
            'Jarak' => [
                [
                    'nama' => 'Sangat Dekat < 5 km - Sangat Baik(Very High)',
                    'nilai' => 1, // TERDEKAT = TERBAIK = 1
                ],
                [
                    'nama' => 'Dekat 5 – 9 km - Baik(High)',
                    'nilai' => 2,
                ],
                [
                    'nama' => 'Cukup Dekat 10 – 14 km - Cukup (Medium)',
                    'nilai' => 3,
                ],
                [
                    'nama' => 'Jauh 15 – 20 km - Kurang(Low)',
                    'nilai' => 4,
                ],
                [
                    'nama' => 'Sangat Jauh > 20 km - Sangat Kurang(Very Low)',
                    'nilai' => 5, // TERJAUH = TERBURUK = 5
                ],
            ],
            
            // 3. Keaktifan Sosial & Kemampuan Komunikasi (benefit)
            'Komunikasi' => [
                [
                    'nama' => 'Tidak Aktif - Sangat Kurang(Very Low)',
                    'nilai' => 1,
                ],
                [
                    'nama' => 'Kurang Aktif - Kurang(Low)',
                    'nilai' => 2,
                ],
                [
                    'nama' => 'Cukup Aktif - Cukup (Medium)',
                    'nilai' => 3,
                ],
                [
                    'nama' => 'Aktif - Baik(High)',
                    'nilai' => 4,
                ],
                [
                    'nama' => 'Sangat Aktif - Sangat Baik(Very High)',
                    'nilai' => 5,
                ],
            ],
            
            // 4. Tingkat Kesibukan & Fleksibilitas Waktu (benefit)
            'Fleksibilitas' => [
                [
                    'nama' => 'Tidak Fleksibel - Sangat Kurang(Very Low)',
                    'nilai' => 1,
                ],
                [
                    'nama' => 'Kurang Fleksibel - Kurang(Low)',
                    'nilai' => 2,
                ],
                [
                    'nama' => 'Cukup Fleksibel - Cukup (Medium)',
                    'nilai' => 3,
                ],
                [
                    'nama' => 'Fleksibel - Baik(High)',
                    'nilai' => 4,
                ],
                [
                    'nama' => 'Sangat Fleksibel - Sangat Baik(Very High)',
                    'nilai' => 5,
                ],
            ],
        ];

        // Hapus data lama jika ada
        sub_kriteria::query()->delete();

        // Reset auto increment (untuk MySQL)
        if (config('database.default') === 'mysql') {
            \DB::statement('ALTER TABLE sub_kriterias AUTO_INCREMENT = 1');
        }

        $totalInserted = 0;
        $errors = [];

        // Insert data sub kriteria untuk setiap kriteria
        foreach ($dataSubKriteria as $namaKriteria => $subKriterias) {
            // Cari kriteria berdasarkan nama
            $kriteria = Kriteria::where('nama', $namaKriteria)->first();
            
            if ($kriteria) {
                foreach ($subKriterias as $data) {
                    sub_kriteria::create([
                        'kriteria_id' => $kriteria->id,
                        'nama' => $data['nama'],
                        'nilai' => $data['nilai'],
                    ]);
                    $totalInserted++;
                }
                
                $this->command->info("✓ Sub kriteria untuk '{$namaKriteria}' berhasil ditambahkan");
            } else {
                $errors[] = $namaKriteria;
                $this->command->error("✗ Kriteria '{$namaKriteria}' tidak ditemukan!");
            }
        }

        // Tampilkan ringkasan
        $this->command->info("\n=== RINGKASAN SUB KRITERIA ===");
        $this->command->info("Total data yang berhasil ditambahkan: {$totalInserted}");
        
        if (!empty($errors)) {
            $this->command->error("Kriteria yang tidak ditemukan: " . implode(', ', $errors));
            $this->command->error("Pastikan KriteriaSeeder sudah dijalankan terlebih dahulu!");
        }
    }
}
