<?php

namespace App\Support;

use App\Models\Permission;
use App\Models\Role;

class AccessCatalog
{
    public const SUPER_ADMIN = 'super-admin';

    public const ADMINISTRATOR = 'administrator';

    /**
     * @return list<array{slug: string, name: string, module: string, description: string}>
     */
    public static function permissions(): array
    {
        $modules = [
            'dashboard' => ['Dashboard', 'the dashboard'],
            'system' => ['System', 'system configuration'],
            'users' => ['Users', 'users'],
            'roles' => ['Roles', 'roles'],
            'permissions' => ['Permissions', 'permissions'],
            'sites' => ['Sites', 'sites'],
            'suppliers' => ['Suppliers', 'suppliers'],
            'products' => ['Products', 'products'],
            'product-suppliers' => ['Supplies', 'supplies'],
            'hydroponics' => ['Categories', 'categories'],
            'growth-logs' => ['Growth logs', 'growth logs'],
            'clients' => ['Clients', 'clients'],
            'visitors' => ['Visitors', 'visitors'],
            'staff-members' => ['Staff', 'staff'],
            'sales' => ['Sales', 'sales'],
        ];

        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
        ];

        $permissions = [];

        foreach ($modules as $prefix => [$module, $label]) {
            if ($prefix === 'dashboard') {
                $permissions[] = [
                    'slug' => 'dashboard.view',
                    'name' => 'View dashboard',
                    'module' => $module,
                    'description' => 'Open the admin dashboard.',
                ];

                continue;
            }

            if ($prefix === 'system') {
                $permissions[] = [
                    'slug' => 'system.configure',
                    'name' => 'Configure system',
                    'module' => $module,
                    'description' => 'Open system configuration.',
                ];

                continue;
            }

            foreach ($actions as $action => $verb) {
                $permissions[] = [
                    'slug' => $prefix.'.'.$action,
                    'name' => $verb.' '.$label,
                    'module' => $module,
                    'description' => $verb.' '.$label.'.',
                ];
            }
        }

        return $permissions;
    }

    public static function sync(): void
    {
        foreach (self::permissions() as $permission) {
            Permission::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'module' => $permission['module'],
                    'description' => $permission['description'],
                    'is_system' => true,
                ]
            );
        }

        $permissionIds = Permission::query()->where('is_system', true)->pluck('id');

        $superAdmin = Role::query()->updateOrCreate(
            ['slug' => self::SUPER_ADMIN],
            [
                'name' => 'Super Admin',
                'description' => 'Unrestricted access to users, roles, permissions, system configuration, and every module.',
                'is_system' => true,
            ]
        );
        $superAdmin->permissions()->sync($permissionIds);

        $administrator = Role::query()->updateOrCreate(
            ['slug' => self::ADMINISTRATOR],
            [
                'name' => 'Administrator',
                'description' => 'Full access through assigned permissions, without the Super Admin bypass.',
                'is_system' => true,
            ]
        );
        $administrator->permissions()->sync($permissionIds);
    }
}
