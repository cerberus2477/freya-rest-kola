<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plant;
use Faker\Factory as Faker;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        //TODO daddin actual plants, connecting it with type id
        foreach (range(1, 10) as $index) {
            Plant::create([
                'name' => $faker->word,
                'latin_name' => $faker->words(2, true),
            ]);
        }
    }
}
