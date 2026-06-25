<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     * Menggunakan nim sebagai identifier dan Argon2id untuk password.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim'            => (string) fake()->unique()->numerify('##########'),
            'password'       => static::$password ??= Hash::make('password'),
            'role'           => 'mahasiswa',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * State untuk akun admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
