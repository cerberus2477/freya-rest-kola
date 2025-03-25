<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            //hard coded, fix data. this is recommended for the application to work properly.
            TypeSeeder::class,
            StagesSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            PlantSeeder::class,

            //these simulate having an actual userbase. this is test data.
            UserSeeder::class,
            UserPlantSeeder::class,
            ListingSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}

// 1. fix dolgok: stage, category, type, role, plants (typera hagyatkozik) done

// 2. user: random role (jó lenne paraméterrel) 

// 3. userplant factory:  választ egy növényt, usert (generál újat ha nincs), staget 
// ebből legyen x darab, ehhez nem lesz listing

// 4. listingek generálása : letrehoz mindehez egy új userplantet (nem választ, hogy minden userplanthez csak egy listing legyen)
// (vagy userplantet választ azok közül, amire nem hivatkozik listing. ha nincs generál)
// kép !!!

// 5. articles - kiválasztunk (ha nincs generálsz) usert, akinek a roleja 'where 1 or 2', választunk categoryt (vagy null), választunk plantet (vagy null)
// - legyen kép a markdownban