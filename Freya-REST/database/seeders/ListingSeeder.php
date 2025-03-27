<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;

class ListingSeeder extends Seeder
{
    public function run()
    {
        // 10 listings with generated images (1-10 images)
        Listing::factory()->count(10)->create();

        // 10 listings using a random placeholder image 
        Listing::factory()->count(5)->withPlaceholderImage()->create();
    }
}