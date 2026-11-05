<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Inventory permissions
            ['name' => 'inventory.view', 'display_name' => 'View Inventory', 'description' => 'Can view inventory items', 'module' => 'inventory'],
            ['name' => 'inventory.create', 'display_name' => 'Create Inventory', 'description' => 'Can create inventory items', 'module' => 'inventory'],
            ['name' => 'inventory.edit', 'display_name' => 'Edit Inventory', 'description' => 'Can edit inventory items', 'module' => 'inventory'],
            ['name' => 'inventory.delete', 'display_name' => 'Delete Inventory', 'description' => 'Can delete inventory items', 'module' => 'inventory'],
            
            // Task permissions
            ['name' => 'tasks.view', 'display_name' => 'View Tasks', 'description' => 'Can view tasks', 'module' => 'tasks'],
            ['name' => 'tasks.create', 'display_name' => 'Create Tasks', 'description' => 'Can create tasks', 'module' => 'tasks'],
            ['name' => 'tasks.edit', 'display_name' => 'Edit Tasks', 'description' => 'Can edit tasks', 'module' => 'tasks'],
            ['name' => 'tasks.delete', 'display_name' => 'Delete Tasks', 'description' => 'Can delete tasks', 'module' => 'tasks'],
            ['name' => 'tasks.assign', 'display_name' => 'Assign Tasks', 'description' => 'Can assign tasks to users', 'module' => 'tasks'],
            
            // Gallery permissions
            ['name' => 'gallery.view', 'display_name' => 'View Gallery', 'description' => 'Can view gallery', 'module' => 'gallery'],
            ['name' => 'gallery.upload', 'display_name' => 'Upload Files', 'description' => 'Can upload files', 'module' => 'gallery'],
            ['name' => 'gallery.delete', 'display_name' => 'Delete Files', 'description' => 'Can delete files', 'module' => 'gallery'],
            
            // User management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'Can view users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Can edit users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Can delete users', 'module' => 'users'],
            
            // System administration
            ['name' => 'system.admin', 'display_name' => 'System Admin', 'description' => 'Full system access', 'module' => 'system'],
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
        
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access',
                'is_active' => true
            ]
        );
        
        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Project and team management access',
                'is_active' => true
            ]
        );
        
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'User',
                'description' => 'Basic user access',
                'is_active' => true
            ]
        );
        
        $demoRole = Role::firstOrCreate(
            ['name' => 'demo'],
            [
                'display_name' => 'Demo User',
                'description' => 'Read-only demo access',
                'is_active' => true
            ]
        );
        
        // Assign permissions to roles
        $adminPermissions = Permission::all();
        $adminRole->permissions()->sync($adminPermissions->pluck('id'));
        
        $managerPermissions = Permission::whereIn('module', ['inventory', 'tasks', 'gallery'])->get();
        $managerRole->permissions()->sync($managerPermissions->pluck('id'));
        
        $userPermissions = Permission::whereIn('name', [
            'inventory.view', 'tasks.view', 'tasks.create', 'tasks.edit', 'gallery.view'
        ])->get();
        $userRole->permissions()->sync($userPermissions->pluck('id'));

        // Demo role gets ONLY view permissions
        $demoPermissions = Permission::where('name', 'like', '%.view')->get();
        $demoRole->permissions()->sync($demoPermissions->pluck('id'));
        
        // Assign admin role to first user if exists
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('admin');
        }
    }
}
