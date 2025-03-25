<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\UserPlant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ListingFactory extends Factory
{
    //creates a userplant with it
    protected $model = Listing::class;

    public function definition()
    {
        return [
            'user_plants_id' => UserPlant::factory()->create()->id,
            'city' => $this->faker->city,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,

            //random 1-10 db GyümölcsBig kép.
            //TODO: legyen pl egy listingimages mappa külön csak az adatok szimulálása kedvéért. vagy valahonnan szedjünk kb végtelen különböző vállalható képet. lehetnek pixelek is xd
            'media' => json_encode(array_map(fn() => storage_path('app/public/placeholders/GyümölcsBig.png'), range(1, rand(1, 10)))),
            'price' => $this->faker->numberBetween(5, 500)*100
        ];
    }


    // private function generateRandomImages()
    // {
    //     $images = [];
    //     $numberOfImages = rand(1, 5); // Generate between 1 and 5 images

    //     for ($i = 0; $i < $numberOfImages; $i++) {
    //         // Generate a random image and save it to the storage
    //         //TODO: dont use faker->image
    //         $imagePath = $this->faker->image(storage_path('app/public/listings'), 640, 480, null, false);
    //         Storage::disk('public')->put('listings/', $imagePath);

    //         $images[] = $imagePath;
    //     }

    //     return $images;
    // }
}