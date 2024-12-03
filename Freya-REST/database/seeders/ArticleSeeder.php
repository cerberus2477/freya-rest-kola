<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Article::create([
                'title' => $faker->sentence,
                'plant_id' => $faker->optional()->numberBetween(1, 10), // Optional association with plants
                'source' => $faker->url,
            ]);
        }
    }
}

