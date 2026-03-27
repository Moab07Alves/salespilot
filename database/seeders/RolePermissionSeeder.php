<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guards = [
            'web'
        ];

        foreach ($guards as $guard) {
            $admin = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => $guard,
            ]);

            $permissions = Permission::where('guard_name', $guard)->get();

            $admin->syncPermissions($permissions);
        }
    }
}
