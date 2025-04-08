<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

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
    public function definition(): array
    {
        $pictures = Storage::disk('public')->allFiles('placeholders');

        return [
            'username' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 3,
            'picture' => StorageHelper::getPlaceholderImage(), // Get a random placeholder image
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    //not done, mayhaps will not be done
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }


    public function withRole(int $roleId): static
    {
        //if admin, have shovel as profile pic
        return $this->state(fn (array $attributes) => [
            'role_id' => $roleId,
            'picture' => $roleId === 1 
                ? StorageHelper::getPlaceholderImage('Shovel.png') // Specific placeholder for admins
                : $attributes['picture'],
        ]);
    }
}