<?php

namespace Database\Factories;

use Str as Enter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
// database/factories/UserFactory.php
public function definition(): array
{
    $role = $this->faker->randomElement(['operator_sekolah', 'operator_yayasan']);
    
    return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('password123'), // password default
        'remember_token' => Str::random(10),
        'role' => $role,
        'no_hp' => $this->faker->phoneNumber,
        // npsn akan di-set di seeder, hanya kalau role == operator_sekolah
    ];
}


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
