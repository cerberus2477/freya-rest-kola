<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserPlant;
use Faker\Factory as Faker;

class UserPlantSeeder extends Seeder
{
    public function run()
    {
        //these won't have listings associated with them
        UserPlant::factory()->count(10)->create();
    }
}
