<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\UserPlant;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\StorageHelper;

class ListingFactory extends Factory
{

    //creates a new userplant with it. this ensures that every listing has a userplant, but every userplant is inly in one listing.
    protected $model = Listing::class;

    public function definition()
    {
        return [
            'user_plants_id' => UserPlant::factory()->create()->id, // Always create a new UserPlant
            'city' => $this->faker->city,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'media' => json_encode(StorageHelper::generateImagesToFolder('listings'), 1, 10), // Default to generated images
            'price' => $this->faker->numberBetween(5, 500) * 100
        ];
    }


    public function withPlaceholderImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'media' => json_encode([StorageHelper::getPlaceholderImage()]), // Single random placeholder
        ]);
    }   
}