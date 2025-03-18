<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use Faker\Factory as Faker;

class ListingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Listing::create([
                'user_plants_id' => $faker->numberBetween(1, 10), // Assuming 10 user_plants
                'city' => $faker->city,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'media' => $faker->url, 
                'sell' => $faker->boolean, // 0 = Buy, 1 = Sell
                'price' => $faker->numberBetween(5, 500)*100
            ]);
        }
    }
}