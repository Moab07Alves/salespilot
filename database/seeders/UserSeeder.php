<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'developer.suporte@admin.com'],
            [
                'name' => 'Developer Administrator',
                'email_verified_at' => now(),
                'password' => bcrypt('PE8tsD£2+6Qu8ZrXbCLU'),
                'is_active' => true,
                'avatar' => null,
                'avatar_disk' => 'public',
            ]
        );
    }
}
