<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 15) as $index) {
            Post::create([
                'user_id' => $faker->numberBetween(1, 10), // Assuming 10 users
                'city' => $faker->city,
                'title' => $faker->sentence,
                'plant' => $faker->numberBetween(1, 10), // Assuming 10 plants
                'description' => $faker->paragraph,
                'media' => $faker->randomDigitNotNull, // Placeholder media IDs
                'sell' => $faker->boolean, // 0 = Buy, 1 = Sell
            ]);
        }
    }
}

