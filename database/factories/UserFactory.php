<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $verifiedAt = fake()->boolean(90)
            ? fake()->dateTimeBetween('-1 year', 'now')
            : null;

        $isActive = $verifiedAt
            ? fake()->boolean(90)
            : false;

        $hasLoggedIn = $verifiedAt && fake()->boolean(80);

        $lastLoginAt = $hasLoggedIn
            ? fake()->dateTimeBetween($verifiedAt, 'now')
            : null;

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => $verifiedAt,
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => $isActive,
            'avatar' => null,
            'avatar_disk' => 'public',
            'last_login_at' => $lastLoginAt,
            'remember_token' => Str::random(10),
        ];
    }
}
