<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\DosenProfile;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipuma.test',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Dosen
        $dosen = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'dosen@sipuma.test',
            'password' => Hash::make('password'),
        ]);
        $dosen->assignRole('dosen');
        
        DosenProfile::create([
            'user_id' => $dosen->id,
            'nidn' => '1234567890',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Teknik Informatika',
            'expertise' => 'Machine Learning, Web Development',
        ]);

        // Create Mahasiswa
        $mahasiswa = User::create([
            'name' => 'Jane Smith',
            'email' => 'mahasiswa@sipuma.test',
            'password' => Hash::make('password'),
        ]);
        $mahasiswa->assignRole('mahasiswa');
        
        StudentProfile::create([
            'user_id' => $mahasiswa->id,
            'nim' => '202100123',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Teknik Informatika',
            'year' => 2021,
            'supervisor_id' => $dosen->id,
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@sipuma.test / password');
        $this->command->info('Dosen: dosen@sipuma.test / password');
        $this->command->info('Mahasiswa: mahasiswa@sipuma.test / password');
    }
}
