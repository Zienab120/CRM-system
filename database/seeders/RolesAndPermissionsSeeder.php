<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Permissions
        $permissions = [
            'manage users',
            'crud contacts',
            'crud deals',
            'approve discounts',
            'export reports',
            'configure pipelines',
            'manage integrations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Create Roles
        $roles = [
            'Super Admin' => [
                'manage users', 'crud contacts', 'crud deals', 'approve discounts', 'export reports', 'configure pipelines', 'manage integrations',
            ],
            'Admin' => [
                'manage users', 'crud contacts', 'crud deals', 'approve discounts', 'export reports', 'configure pipelines', 'manage integrations',
            ],
            'Sales Manager' => [
                'crud contacts', 'crud deals', 'approve discounts', 'export reports', 'configure pipelines',
            ],
            'Sales Rep' => [
                'crud contacts', 'crud deals', 'export reports',
            ],
            'Marketing' => [
                'crud contacts', 'export reports',
            ],
            'Support' => [
                'crud contacts', 'export reports',
            ],
            'Finance' => [
                'crud contacts', 'approve discounts', 'export reports',
            ],
            'Viewer' => [
                'crud contacts', 'crud deals', 'export reports',
            ],
        ];

        // 3. Create Roles and Assign Permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
