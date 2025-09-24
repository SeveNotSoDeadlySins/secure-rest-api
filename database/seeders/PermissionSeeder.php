<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superRole = Role::where('name', 'superuser')->firstOrFail();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $customerRole = Role::where('name', 'customer')->firstOrFail();
        $supplierRole = Role::where('name', 'supplier')->firstOrFail();

        $resources = [
            'customer', 'order', 'product', 'supplier', 'user'
        ];

        $verbs = [
            'view', 'viewAny', 'create', 'update', 'delete', 'restore', 'forceDelete'
        ];
        
        foreach ($resources as $resource) {
            foreach ($verbs as $verb) {
                $permission = \App\Models\Permission::create([
                    'name' => $verb . '-' . $resource
                ]);
                if ($resource == 'customer' || $resource == 'order') {
                    $customerRole->assignPermission($permission);
                }
                else if ($resource == 'supplier' || $resource == 'product') {
                    $supplierRole->assignPermission($permission);
                }
                else {
                    $adminRole->assignPermission($permission);
                }
            }
        }

    }
}
