<?php

namespace Database\Factories;

use App\Models\Plant;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    protected $model = Plant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'latin_name' => $this->faker->word,
            'type_id' => Type::inRandomOrder()->first()->id
        ];
    }
}