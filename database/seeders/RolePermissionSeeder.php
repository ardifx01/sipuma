<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $mahasiswa = Role::create(['name' => 'mahasiswa']);
        $dosen = Role::create(['name' => 'dosen']);

        // Create Permissions
        $permissions = [
            // Publication permissions
            'view-publications',
            'create-publications',
            'edit-publications',
            'delete-publications',
            'approve-publications',
            'reject-publications',
            
            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Review permissions
            'review-publications',
            'view-reviews',
            
            // Dashboard permissions
            'view-dashboard',
            'view-admin-dashboard',
            'view-mahasiswa-dashboard',
            'view-dosen-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Mahasiswa permissions
        $mahasiswa->givePermissionTo([
            'view-publications',
            'create-publications',
            'edit-publications',
            'view-dashboard',
            'view-mahasiswa-dashboard',
        ]);

        // Dosen permissions
        $dosen->givePermissionTo([
            'view-publications',
            'review-publications',
            'view-reviews',
            'view-dashboard',
            'view-dosen-dashboard',
        ]);
    }
}
