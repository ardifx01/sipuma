<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions if they don't exist
        $permissions = [
            'view publications',
            'create publications',
            'edit publications',
            'delete publications',
            'download publications'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        
        // Create roles if they don't exist
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $dosen = Role::firstOrCreate(['name' => 'dosen']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        
        // Assign permissions to roles
        $mahasiswa->givePermissionTo([
            'view publications',
            'create publications',
            'edit publications',
            'delete publications',
            'download publications'
        ]);
        
        $dosen->givePermissionTo([
            'view publications',
            'download publications'
        ]);
        
        $admin->givePermissionTo([
            'view publications',
            'create publications',
            'edit publications',
            'delete publications',
            'download publications'
        ]);
    }
}
