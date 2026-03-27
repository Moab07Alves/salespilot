<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
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
            $adminRole = Role::where('name', 'admin')
                ->where('guard_name', $guard)
                ->first();

            if (!$adminRole) {
                continue;
            }

            $user = User::where('email', 'developer.suporte@admin.com')->first();

            if (!$user) {
                continue;
            }

            $user->syncRoles([$adminRole]);
        }
    }
}
