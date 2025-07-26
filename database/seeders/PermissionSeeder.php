<?php

namespace Database\Seeders;

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
        // Xóa cache permission nếu có
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (config('role_permissions') as $roleName => $permissions) {
            // Dùng Spatie Role thay vì App\Models\Role
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            $role->syncPermissions($permissions);
        }
    }
}