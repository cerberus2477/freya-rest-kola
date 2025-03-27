<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\UserPlant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
            'media' => json_encode($this->generateRandomImages()), // Generate images dynamically
            'price' => $this->faker->numberBetween(5, 500) * 100
        ];
    }

    private function generateAndStoreImages()
    {
        $imagePaths = [];
        $numImages = rand(1, 10); // Generate between 1 and 10 images

        // Ensure directory exists
        $folder = 'listings';
        Storage::disk('public')->makeDirectory($folder);

        for ($i = 0; $i < $numImages; $i++) {
            $filename = 'listing_' . Str::uuid() . '.webp';
            $path = "{$folder}/{$filename}";

            // Generate a blank image (or use a real placeholder image)
            $placeholder = imagecreatetruecolor(640, 480);
            $bgColor = imagecolorallocate($placeholder, rand(100, 255), rand(100, 255), rand(100, 255)); // Random color
            imagefill($placeholder, 0, 0, $bgColor);

            // Save image as WebP
            ob_start();
            imagewebp($placeholder, null, 80);
            $imageData = ob_get_clean();
            Storage::disk('public')->put($path, $imageData);

            // Free memory
            imagedestroy($placeholder);

            // Store only the filename
            $imagePaths[] = $filename;
        }

        return $imagePaths;
    }
    
}



    // private function generateRandomImages()
    // {
    //     $images = [];
    //     $numberOfImages = rand(1, 10); // Generate between 1 and 10 images

    //     for ($i = 0; $i < $numberOfImages; $i++) {
    //         // Generate a random image and save it to the storage
    //         //TODO: dont use faker->image
    //         $imagePath = $this->faker->image(storage_path('app/public/listings'), 640, 480, null, false);
    //         Storage::disk('public')->put('listings/', $imagePath);

    //         $images[] = $imagePath;
    //     }

    //     return $images;
    // }
