<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DosenProfile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DosenAhmadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role dosen sudah ada
        $dosenRole = Role::firstOrCreate(['name' => 'dosen']);

        // Cari atau buat user dosen Ahmad Suprijadi
        $dosen = User::firstOrCreate(
            ['email' => 'ahmad.suprijadi@example.com'],
            [
                'name' => 'Ahmad Suprijadi',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role dosen
        $dosen->assignRole($dosenRole);

        // Buat atau update profile dosen
        DosenProfile::updateOrCreate(
            ['user_id' => $dosen->id],
            [
                'nidn' => '0001018501',
                'faculty' => 'Fakultas Teknik',
                'major' => 'Teknik Informatika',
                'expertise' => 'Teknologi Informasi, Sistem Informasi, Database',
            ]
        );

        $this->command->info('Dosen Ahmad Suprijadi berhasil dibuat/diupdate!');
        $this->command->info('Email: ahmad.suprijadi@example.com');
        $this->command->info('Password: password123');
    }
}
