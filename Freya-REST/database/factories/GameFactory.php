<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;

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

    protected $model = Game::class;

    //  Nem biztos hogy jó
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'type' => $this->faker->word,
            'levelCount' => $this->faker->numberBetween(1, 6969),
            'description' => $this->faker->sentence()
        ];
    }
}
