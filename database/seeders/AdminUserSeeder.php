<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipuma.com',
            'password' => bcrypt('password'),
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);

        // Create sample dosen
        $dosen = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'dosen@sipuma.com',
            'password' => bcrypt('password'),
        ]);

        // Assign dosen role
        $dosenRole = Role::where('name', 'dosen')->first();
        $dosen->assignRole($dosenRole);

        // Create sample mahasiswa
        $mahasiswa = User::create([
            'name' => 'Jane Smith',
            'email' => 'mahasiswa@sipuma.com',
            'password' => bcrypt('password'),
        ]);

        // Assign mahasiswa role
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswa->assignRole($mahasiswaRole);
    }
}
