<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListingSeeder extends Seeder
{
    public function run()
    {
        // 20 listings with generated images (1-10 images)
        Listing::factory()->count(20)->create();

        // 10 listings using a random placeholder image 
        Listing::factory()->count(5)->withPlaceholderImage()->create();

        // Insert a static user_plant for admin's listing
        DB::table('user_plants')->insert([
            'id' => 50,
            'user_id' => 1,
            'plant_id' => 2,
            'stage_id' => 5,
            'count' => 4,
            'created_at' => Carbon::parse('2025-04-28 12:07:50'),
            'updated_at' => Carbon::parse('2025-04-28 12:07:50'),
        ]);

        // Insert the static listing linked to the user_plant
        DB::table('listings')->insert([
            'id' => 50,
            'user_plants_id' => 50, // link to the above inserted user_plant
            'title' => 'Egészséges szobanövények',
            'description' => "🌿 Egészséges szobanövények eladók! 🌿\nKülönféle méretű és fajtájú növények közvetlenül tőlünk – szeretettel nevelve, gondosan ápolva. Tökéletesek otthonod vagy irodád szebbé tételéhez! 🌱\nÁrak és fajták változók, érdeklődj üzenetben!\n\nTöbb vásárlás esetén kedvezmény!\n\n📍 Átvétel: személyesen vagy előzetes egyeztetéssel szállítás is megoldható.\n",
            'city' => 'Őrbottyán',
            'media' => '"[\"placeholders\\\/ViragBig.png\"]"',
            'price' => 4000,
            'created_at' => Carbon::parse('2025-04-28 12:21:25'),
            'updated_at' => Carbon::parse('2025-04-28 12:21:25'),
        ]);
    }
}