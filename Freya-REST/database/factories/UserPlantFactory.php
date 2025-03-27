<?php

namespace Database\Factories;

use App\Models\Plant;
use App\Models\User;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPlant>
 */
class UserPlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id??User::factory()->create()->id,
            'plant_id' => Plant::inRandomOrder()->first()->id,
            'stage_id' => Stage::inRandomOrder()->first()->id,
            'count'=> $this->faker->randomDigitNotNull(),
        ];
    }
}
