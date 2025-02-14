<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TypeSeeder::class,
            StagesSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PlantSeeder::class,
            ArticleSeeder::class,
            UserPlantSeeder::class,
            ListingSeeder::class,
        ]);
    }
}
