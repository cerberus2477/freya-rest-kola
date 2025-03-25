<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@freyasgarden.com',
            'city' => 'bottyan',
            'birthdate' => $faker->date(),
            'password' => bcrypt('admin123'),
            'role_id' => 1,
            'picture' => storage_path('app/public/placeholders/Shovel.png'),
        ]);

        
        foreach (range(1, 100) as $index) {
            DB::table('users')->insert([
                'username' => $faker->userName,
                'email' => $faker->email,
                'city' => $faker->city,
                'birthdate' => $faker->date(),
                'password' => bcrypt('password'),
                'role_id' => $faker->numberBetween(1, 3),
                'picture' => storage_path('app/public/placeholders/GyümölcsBig.png'),
            ]);
        }
    }
}