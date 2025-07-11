<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PublicationType::create([
            'name' => 'Skripsi',
            'description' => 'Hasil penelitian akhir mahasiswa untuk memperoleh gelar sarjana'
        ]);

        \App\Models\PublicationType::create([
            'name' => 'Magang',
            'description' => 'Laporan dan hasil kerja magang mahasiswa'
        ]);

        \App\Models\PublicationType::create([
            'name' => 'Riset',
            'description' => 'Penelitian, paper, dan artikel ilmiah mahasiswa'
        ]);
    }
}
