<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
        DB::table('users')->insert([
            'username' => $faker->userName,
            'email' => $faker->email,
            'city' => $faker->city,
            'birthdate' => $faker->date(),
            'password' => bcrypt('password'),
            'role_id' => $faker->numberBetween(1, 3),
            'active' => $faker->boolean,
            ]);
        }
    }
}
