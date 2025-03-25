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
    //picks random user, plant, stage, ha nincs még egy se akor hoz létre

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id??User::factory()->create()->id,
            'plant_id' => Plant::inRandomOrder()->first()->id??Plant::factory()->create()->id,
            'stage_id' => Stage::inRandomOrder()->first()->id??Plant::factory()->create()->id,
            'count'=> $this->faker->randomNumber(1, 4),
        ];
    }
}
