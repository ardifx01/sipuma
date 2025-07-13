<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\DosenProfile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // Create Dosen Users
        $dosen1 = User::create([
            'name' => 'Dr. Ahmad Supriyadi, S.Kom., M.Kom.',
            'email' => 'ahmad.supriyadi@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $dosen1->assignRole($dosenRole);

        DosenProfile::create([
            'user_id' => $dosen1->id,
            'nidn' => '0005018501',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'expertise' => 'Sistem Informasi, Database, Web Development',
        ]);

        $dosen2 = User::create([
            'name' => 'Dr. Siti Nurhaliza, S.Si., M.Si.',
            'email' => 'siti.nurhaliza@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $dosen2->assignRole($dosenRole);

        DosenProfile::create([
            'user_id' => $dosen2->id,
            'nidn' => '0020038601',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'expertise' => 'Data Science, Machine Learning, Artificial Intelligence',
        ]);

        // Create Mahasiswa Users
        $mahasiswa1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa1->assignRole($mahasiswaRole);

        StudentProfile::create([
            'user_id' => $mahasiswa1->id,
            'nim' => '2021001',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'year' => 2021,
            'supervisor_id' => $dosen1->id, // Assign to Dr. Ahmad
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Dewi Sartika',
            'email' => 'dewi.sartika@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa2->assignRole($mahasiswaRole);

        StudentProfile::create([
            'user_id' => $mahasiswa2->id,
            'nim' => '2021002',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'year' => 2021,
            'supervisor_id' => $dosen2->id, // Assign to Dr. Siti
        ]);

        // Create additional test users for more variety
        $mahasiswa3 = User::create([
            'name' => 'Rizki Pratama',
            'email' => 'rizki.pratama@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa3->assignRole($mahasiswaRole);

        StudentProfile::create([
            'user_id' => $mahasiswa3->id,
            'nim' => '2021003',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'year' => 2021,
            'supervisor_id' => $dosen1->id, // Assign to Dr. Ahmad
        ]);

        $mahasiswa4 = User::create([
            'name' => 'Nina Safitri',
            'email' => 'nina.safitri@sipuma.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa4->assignRole($mahasiswaRole);

        StudentProfile::create([
            'user_id' => $mahasiswa4->id,
            'nim' => '2021004',
            'faculty' => 'Fakultas Teknik',
            'major' => 'Sistem Informasi',
            'year' => 2021,
            'supervisor_id' => $dosen2->id, // Assign to Dr. Siti
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@sipuma.test / password');
        $this->command->info('Dosen 1: ahmad.supriyadi@sipuma.test / password');
        $this->command->info('Dosen 2: siti.nurhaliza@sipuma.test / password');
        $this->command->info('Mahasiswa 1: budi.santoso@sipuma.test / password');
        $this->command->info('Mahasiswa 2: dewi.sartika@sipuma.test / password');
        $this->command->info('Mahasiswa 3: rizki.pratama@sipuma.test / password');
        $this->command->info('Mahasiswa 4: nina.safitri@sipuma.test / password');
    }
}
