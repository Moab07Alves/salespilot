<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guards = [
            'web'
        ];

        $resources = [
            'users',
            'roles',

            'employees',
            'departments',
            'positions',

            'work_schedules',
            'employee_work_schedules',
            'work_schedule_exceptions',
        ];

        $actions = [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'forceDelete'
        ];

        foreach ($guards as $guard) {
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'name' => "{$resource}.{$action}",
                        'guard_name' => $guard,
                    ]);
                }
            }
        }

    }
}
