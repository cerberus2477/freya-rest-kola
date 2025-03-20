<?php

namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    protected $model = Plant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'latin_name' => $this->faker->word,
            'type_id' => \App\Models\Type::inRandomOrder()->first()->id ?? \App\Models\Type::factory()->create()->id,
        ];
    }
}