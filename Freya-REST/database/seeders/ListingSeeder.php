<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use Faker\Factory as Faker;

class ListingSeeder extends Seeder
{
    public function run()
    {
        // $faker = Faker::create();

        // foreach (range(1, 10) as $index) {
        //     Listing::create([
        //         'user_plants_id' => $index,
        //         'city' => $faker->city,
        //         'title' => $faker->sentence,
        //         'description' => $faker->paragraph,
        //         'media' => json_encode(array_map(fn() => $faker->imageUrl(), range(1, rand(1, 5)))),
        //         'price' => $faker->numberBetween(5, 500)*100
        //     ]);
        // }

        Listing::factory()->count(10)->create();
    }
}