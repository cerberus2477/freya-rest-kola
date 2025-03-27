<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main admin with specific details
        User::create([
            'username' => 'admin',
            'email' => 'admin@freyasgarden.com',
            'city' => 'bottyan',
            'birthdate' => now()->subYears(30)->format('Y-m-d'),
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'picture' => 'placeholders/Shovel.png',
        ]);

        // Create 4 additional admins
        User::factory()->withRole(1)->count(4)->create();

        // Create 20 staff users (role_id = 2)
        User::factory()->withRole(2)->count(20)->create();

        // Create 50 regular users (role_id = 3)
        User::factory()->count(50)->create();
    }
}