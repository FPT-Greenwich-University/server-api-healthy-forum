<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::insert([
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'doctor', 'guard_name' => 'web'],
        ]);
        $permissionsByRole = [
            'admin' => ['create user', 'detail user', 'edit user', 'delete user', 'ban user'],
            'doctor' => ['create a post', 'update a post', 'delete a post'],
            'customer' => ['view all posts', 'view a post'],
        ];

        $insertPermissions = fn($role) => collect($permissionsByRole[$role])
            ->map(fn($name) => DB::table('permissions')->insertGetId(['name' => $name, 'guard_name' => 'web']))
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'doctor' => $insertPermissions('doctor'),
            'customer' => $insertPermissions('customer'),
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }
    }
}
