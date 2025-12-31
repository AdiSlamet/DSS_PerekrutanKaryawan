<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kandidat;
use Carbon\Carbon;

class KandidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data 30 kandidat dengan nama-nama Indonesia
        $kandidats = [
            // Periode 6 bulan lalu (2024-08)
            [
                'nama' => 'Ahmad Fauzi',
                'created_at' => Carbon::now()->subMonths(6),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'created_at' => Carbon::now()->subMonths(6),
            ],
            [
                'nama' => 'Bambang Sutrisno',
                'created_at' => Carbon::now()->subMonths(6),
            ],

            // Periode 5 bulan lalu (2024-09)
            [
                'nama' => 'Dewi Anggraini',
                'created_at' => Carbon::now()->subMonths(5),
            ],
            [
                'nama' => 'Eko Prasetyo',
                'created_at' => Carbon::now()->subMonths(5),
            ],
            [
                'nama' => 'Fitriani Rahmawati',
                'created_at' => Carbon::now()->subMonths(5),
            ],

            // Periode 4 bulan lalu (2024-10)
            [
                'nama' => 'Gunawan Santoso',
                'created_at' => Carbon::now()->subMonths(4),
            ],
            [
                'nama' => 'Hesti Purnama',
                'created_at' => Carbon::now()->subMonths(4),
            ],
            [
                'nama' => 'Irfan Maulana',
                'created_at' => Carbon::now()->subMonths(4),
            ],
            [
                'nama' => 'Julia Sari',
                'created_at' => Carbon::now()->subMonths(4),
            ],

            // Periode 3 bulan lalu (2024-11)
            [
                'nama' => 'Kurniawan Adi',
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'nama' => 'Lestari Wulandari',
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'nama' => 'Mulyadi Hartono',
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'nama' => 'Nina Melati',
                'created_at' => Carbon::now()->subMonths(3),
            ],

            // Periode 2 bulan lalu (2024-12)
            [
                'nama' => 'Oki Setiawan',
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'nama' => 'Putri Ayu',
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'nama' => 'Rahmat Hidayat',
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'nama' => 'Sari Dewi',
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'nama' => 'Teguh Prakoso',
                'created_at' => Carbon::now()->subMonths(2),
            ],

            // Periode 1 bulan lalu (2025-01)
            [
                'nama' => 'Umi Kulsum',
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'nama' => 'Vino Bastian',
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'nama' => 'Wahyu Nugroho',
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'nama' => 'Xavier Tanuwijaya',
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'nama' => 'Yuni Astuti',
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'nama' => 'Zainal Abidin',
                'created_at' => Carbon::now()->subMonths(1),
            ],

            // Periode bulan ini (2025-02)
            [
                'nama' => 'Aditya Saputra',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Bella Permata',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Cahya Ramadhan',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Dian Puspita',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Fajar Maulana',
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($kandidats as $kandidat) {
            Kandidat::create($kandidat);
        }

        $this->command->info('Seeder 30 Kandidat berhasil ditambahkan!');
    }
}