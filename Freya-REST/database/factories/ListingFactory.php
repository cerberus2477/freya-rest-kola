<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition()
    {
        return [
            'user_plants_id' => \App\Models\UserPlant::factory(),
            'city' => $this->faker->city,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'media' => [$this->faker->imageUrl()],
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
