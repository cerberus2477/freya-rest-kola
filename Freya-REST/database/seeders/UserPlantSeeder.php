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
        $uniquePairs = [];  // Array to keep track of used user-plant pairs

        // Loop until we have 20 unique pairs
        while (count($uniquePairs) < 20) {
            $userId = $faker->numberBetween(1, 10);  // Random user ID between 1 and 10
            $plantId = $faker->numberBetween(1, 10);  // Random plant ID between 1 and 10

            // Check if this user-plant pair is already in the array
            if (!in_array([$userId, $plantId], $uniquePairs)) {
                // Insert the pair into the array and database
                $uniquePairs[] = [$userId, $plantId];

                UserPlant::create([
                    'user_id' => $userId,
                    'plant_id' => $plantId,
                ]);
            }
        }
    }
}
