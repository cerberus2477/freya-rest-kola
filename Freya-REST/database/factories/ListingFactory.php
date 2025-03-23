<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\UserPlant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition()
    {
        return [
            'user_plants_id' => UserPlant::factory(),
            'city' => $this->faker->city,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'media' => $this->generateRandomImages(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }

    /**
     * Generate an array of random image URLs.
     *
     * @return array
     */
    private function generateRandomImages()
    {
        $images = [];
        $numberOfImages = rand(1, 5); // Generate between 1 and 5 images

        for ($i = 0; $i < $numberOfImages; $i++) {
            // Generate a random image and save it to the storage
            $imagePath = $this->faker->image(storage_path('app/public/listings'), 640, 480, null, false);
            $images[] = Storage::url('public/listings/' . $imagePath);
        }

        return $images;
    }
}