<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;

class ListingSeeder extends Seeder
{
    public function run()
    {
        Listing::factory()->count(10)->create();
    }
}