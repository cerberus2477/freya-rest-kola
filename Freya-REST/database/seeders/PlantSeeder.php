<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plant;
use Faker\Factory as Faker;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        //TODO daddin actual plants, connecting it with type id
        // foreach (range(1, 10) as $index) {
        //     Plant::create([
        //         'name' => $faker->word,
        //         'latin_name' => $faker->words(2, true),
        //     ]);
        // }

        DB::table('plants')->insert([
            //Gyümölcs
            ['name' => 'Alma', 'latin_name' => 'Malus', 'type_id' => '1'],
            ['name' => 'Körte', 'latin_name' => 'Pyrus', 'type_id' => '1'],
            ['name' => 'Banán', 'latin_name' => 'Musa', 'type_id' => '1'],
            ['name' => 'Szőlő', 'latin_name' => 'Vitis', 'type_id' => '1'],
            ['name' => 'Gránátalma', 'latin_name' => 'Punica granatum', 'type_id' => '1'],
            ['name' => 'Eper', 'latin_name' => 'Fragaria', 'type_id' => '1'],
            ['name' => 'Áfonya', 'latin_name' => 'Vaccinium', 'type_id' => '1'],
            ['name' => 'Málna', 'latin_name' => 'Rubus idaeus', 'type_id' => '1'],
            ['name' => 'Füge', 'latin_name' => 'Ficus', 'type_id' => '1'],
            ['name' => 'Naspolya', 'latin_name' => 'Mespilus', 'type_id' => '1'],
            ['name' => 'Görögdinnye', 'latin_name' => 'Citrullus lanatus', 'type_id' => '1'],
            ['name' => 'Sárgadinnye', 'latin_name' => 'Cucumis melo', 'type_id' => '1'],
            ['name' => 'Cseresznye', 'latin_name' => 'Prunus subg. Cerasus', 'type_id' => '1'],
            ['name' => 'Meggy', 'latin_name' => 'Prunus cerasus', 'type_id' => '1'],
            ['name' => 'Citrom', 'latin_name' => 'Citrus limon', 'type_id' => '1'],
            ['name' => 'Narancs', 'latin_name' => 'Citrus sinensis', 'type_id' => '1'],
            ['name' => 'Mandarin', 'latin_name' => 'Citrus reticulata', 'type_id' => '1'],
            ['name' => 'Grapefruit', 'latin_name' => 'Citrus paradisi', 'type_id' => '1'],
            ['name' => 'Ananász', 'latin_name' => 'Ananas comosus', 'type_id' => '1'],
            ['name' => 'Szilva', 'latin_name' => 'Prunus domestica', 'type_id' => '1'],
            ['name' => 'Lime', 'latin_name' => 'Citrus aurantiifolia', 'type_id' => '1'],
            ['name' => 'Mangó', 'latin_name' => 'Mangifera indica', 'type_id' => '1'],
            ['name' => 'Datolya', 'latin_name' => 'Phoenix', 'type_id' => '1'],
            ['name' => 'Papaja', 'latin_name' => 'Carica papaya', 'type_id' => '1'],
            //Zöldség
            ['name' => 'Paradicsom', 'latin_name' => 'Solanum lycopersicum', 'type_id' => '1'],
            ['name' => 'Uborka', 'latin_name' => 'Cucumis sativus', 'type_id' => '2'],
            ['name' => 'Cukkini', 'latin_name' => 'pepo convar', 'type_id' => '2'],
            ['name' => 'Padlizsán', 'latin_name' => 'Solanum melongena', 'type_id' => '2'],
            ['name' => 'Sárgarépa', 'latin_name' => 'Daucus carota', 'type_id' => '2'],
            ['name' => 'Krumpli', 'latin_name' => 'Solanum tuberosum', 'type_id' => '2'],
            ['name' => 'Hagyma', 'latin_name' => 'Allium cepa', 'type_id' => '2'],
            ['name' => 'Fokhagyma', 'latin_name' => 'Allium sativum', 'type_id' => '2'],
            ['name' => 'Kaliforniai paprika', 'latin_name' => 'Capsicum annuum', 'type_id' => '2'],
            ['name' => 'Zeller', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Karalábé', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Káposzta', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Sütőtök', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Fehérrépa', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Édeskrumpli', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Kukorica', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Jégsaláta', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Rukkola', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Spenót', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Sóska', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Avokádó', 'latin_name' => '', 'type_id' => '1'],
            ['name' => 'Zöldbab', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Vajbab', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Tarkabab', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Zöldborsó', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Csicseriborsó', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Sárgaborsó', 'latin_name' => '', 'type_id' => '2'],
            ['name' => 'Olajbogyó', 'latin_name' => '', 'type_id' => '1'],

            //Fűszer
            ['name' => 'Gyömbér', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Bazsalikom', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Rozmaring', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Borsikafű', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Majoranna', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Kakkukfű', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Kurkuma', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Sáfrány', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Zsálya', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Chili paprika', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Szerecsendió', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Kömény', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Kapor', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Tárkony', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Oregánó', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Rózsabors', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Feketebors', 'latin_name' => '', 'type_id' => '3'],
            ['name' => 'Fehérbors', 'latin_name' => '', 'type_id' => '3'],
            //Szobanövény
            ['name' => 'Zöldike', 'latin_name' => 'Chlorophytum comosum', 'type_id' => '4'],
            ['name' => 'Buzogányvirág', 'latin_name' => 'Dieffenbachia', 'type_id' => '4'],
            ['name' => 'Sárkányfa', 'latin_name' => 'Dracaena', 'type_id' => '4'],
            ['name' => 'Anyósnyelv', 'latin_name' => 'Sansevieria', 'type_id' => '4'],
            ['name' => 'Csüngőágú fikusz', 'latin_name' => 'Ficus benjamina', 'type_id' => '4'],
            ['name' => 'Szobafikusz', 'latin_name' => 'Ficus elastica', 'type_id' => '4'],
            ['name' => 'Lantlevelű fikusz', 'latin_name' => 'Ficus lyrata', 'type_id' => '4'],
            ['name' => 'Szobapáfrány', 'latin_name' => 'Nephrolepis exaltata', 'type_id' => '4'],
            ['name' => 'Könnyezőpálma', 'latin_name' => 'Monstera deliciosa', 'type_id' => '4'],
            ['name' => 'Nyíllevél', 'latin_name' => 'Syngonium podophyllum', 'type_id' => '4'],
            ['name' => 'Aranypálma', 'latin_name' => 'Areca lutescens', 'type_id' => '4'],
            ['name' => 'Halfarokpálma', 'latin_name' => 'Caryota mitis', 'type_id' => '4'],
            ['name' => 'Fafojtó ördögfüge', 'latin_name' => 'Clusia rosea', 'type_id' => '4'],
            ['name' => 'Csodacserje', 'latin_name' => 'Croton petra', 'type_id' => '4'],
            ['name' => 'Kenciapálma', 'latin_name' => 'Kentia forsteriana', 'type_id' => '4'],
            ['name' => 'Pozsgafa', 'latin_name' => 'Crassula ovata', 'type_id' => '4'],
            ['name' => 'Óriás Kutyatej', 'latin_name' => 'Euphorbia ingens', 'type_id' => '4'],
            ['name' => 'Tompalevelű törpebors', 'latin_name' => 'Peperomia obtusifolia Optiban Bicolor', 'type_id' => '4'],
            ['name' => 'Vesszőkaktusz', 'latin_name' => 'Rhipsalis heteroclada', 'type_id' => '4'],
            ['name' => 'Szálkás Aloe', 'latin_name' => 'Aloe vera barbadensis', 'type_id' => '4'],
            ['name' => 'Aloe vera', 'latin_name' => 'Aloe vera', 'type_id' => '4'],
        ]);
    }
}
