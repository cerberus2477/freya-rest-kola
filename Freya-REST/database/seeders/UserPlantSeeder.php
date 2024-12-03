<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserPlant;
use Faker\Factory as Faker;

class UserPlantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            UserPlant::create([
                'user_id' => $faker->numberBetween(1, 10), // Assuming 10 users
                'plant_id' => $faker->numberBetween(1, 10), // Assuming 10 plants
            ]);
        }
    }
}
