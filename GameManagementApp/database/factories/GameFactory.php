<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    //  Nem biztos hogy jó
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->type,
            'levelCount' => $this->faker->levelCount,
            'description' => $this->faker->description
        ];
    }
}
