<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plant;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

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
            ['name' => 'Zeller', 'latin_name' => 'Apium graveolens', 'type_id' => '2'],
            ['name' => 'Karalábé', 'latin_name' => 'Brassica oleracea Gongylodes', 'type_id' => '2'],
            ['name' => 'Káposzta', 'latin_name' => 'Brassica oleracea', 'type_id' => '2'],
            ['name' => 'Sütőtök', 'latin_name' => 'Cucurbita maxima convar', 'type_id' => '2'],
            ['name' => 'Fehérrépa', 'latin_name' => 'Pastinaca', 'type_id' => '2'],
            ['name' => 'Édeskrumpli', 'latin_name' => 'Ipomoea batatas', 'type_id' => '2'],
            ['name' => 'Kukorica', 'latin_name' => 'Zea mays', 'type_id' => '2'],
            ['name' => 'Jégsaláta', 'latin_name' => 'Lactuca sativa capitata ', 'type_id' => '2'],
            ['name' => 'Rukkola', 'latin_name' => 'Eruca vesicaria sativa', 'type_id' => '2'],
            ['name' => 'Spenót', 'latin_name' => 'Spinacia oleracea', 'type_id' => '2'],
            ['name' => 'Sóska', 'latin_name' => 'Rumex acetosa', 'type_id' => '2'],
            ['name' => 'Avokádó', 'latin_name' => 'Persea americana', 'type_id' => '1'],
            ['name' => 'Zöldbab', 'latin_name' => 'Phaseolus vulgaris', 'type_id' => '2'],
            ['name' => 'Vajbab', 'latin_name' => 'Phaseolus lunatus', 'type_id' => '2'],
            ['name' => 'Tarkabab', 'latin_name' => 'Phaseolus vulgaris', 'type_id' => '2'],
            ['name' => 'Zöldborsó', 'latin_name' => 'Pisum sativum', 'type_id' => '2'],
            ['name' => 'Csicseriborsó', 'latin_name' => 'Cicer arietinum', 'type_id' => '2'],
            ['name' => 'Sárgaborsó', 'latin_name' => 'Pisum sativum', 'type_id' => '2'],
            ['name' => 'Olajbogyó', 'latin_name' => 'Olea europaea', 'type_id' => '1'],

            //Fűszer
            ['name' => 'Gyömbér', 'latin_name' => 'Zingiber officinale', 'type_id' => '3'],
            ['name' => 'Bazsalikom', 'latin_name' => 'Ocimum basilicum', 'type_id' => '3'],
            ['name' => 'Rozmaring', 'latin_name' => 'Salvia rosmarinus', 'type_id' => '3'],
            ['name' => 'Borsikafű', 'latin_name' => 'Satureja hortensis', 'type_id' => '3'],
            ['name' => 'Majoranna', 'latin_name' => 'Origanum majorana', 'type_id' => '3'],
            ['name' => 'Kakukkfű', 'latin_name' => 'Thymus', 'type_id' => '3'],
            ['name' => 'Kurkuma', 'latin_name' => 'Curcuma longa', 'type_id' => '3'],
            ['name' => 'Sáfrány', 'latin_name' => 'Crocus', 'type_id' => '3'],
            ['name' => 'Zsálya', 'latin_name' => 'Salvia', 'type_id' => '3'],
            ['name' => 'Chili paprika', 'latin_name' => 'Capsicum', 'type_id' => '3'],
            ['name' => 'Szerecsendió', 'latin_name' => 'Myristica fragrans', 'type_id' => '3'],
            ['name' => 'Kömény', 'latin_name' => 'Carum carvi', 'type_id' => '3'],
            ['name' => 'Kapor', 'latin_name' => 'Anethum graveolens', 'type_id' => '3'],
            ['name' => 'Tárkony', 'latin_name' => 'Artemisia dracunculus', 'type_id' => '3'],
            ['name' => 'Oregánó', 'latin_name' => 'Origanum vulgare', 'type_id' => '3'],
            ['name' => 'Rózsabors', 'latin_name' => 'Schinus molle', 'type_id' => '3'],
            ['name' => 'Feketebors', 'latin_name' => 'Piper nigrum', 'type_id' => '3'],
            ['name' => 'Fehérbors', 'latin_name' => 'Piper nigrum', 'type_id' => '3'],

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

            //Virág
            ['name' => 'Orchidea', 'latin_name' => 'Orchidaceae', 'type_id' => '5'],
            ['name' => 'Tulipán', 'latin_name' => 'Tulipa', 'type_id' => '5'],
            ['name' => 'Büdöske', 'latin_name' => 'Tagetes', 'type_id' => '5'],
            ['name' => 'Muskátli', 'latin_name' => 'Pelargonium', 'type_id' => '5'],
            ['name' => 'Rózsa', 'latin_name' => 'Rosa', 'type_id' => '5'],
            ['name' => 'Pitypang', 'latin_name' => 'Taraxacum', 'type_id' => '5'],
            ['name' => 'Jázmin', 'latin_name' => 'Jasminum', 'type_id' => '5'],
            ['name' => 'Liliom', 'latin_name' => 'Lilium', 'type_id' => '5'],
            ['name' => 'Százszorszép', 'latin_name' => 'Bellis perennis', 'type_id' => '5'],
            ['name' => 'Illatos bangita', 'latin_name' => 'Viburnum carlesii', 'type_id' => '5'],
            ['name' => 'Ciklámen', 'latin_name' => 'Cyclamen', 'type_id' => '5'],
            ['name' => 'Hunyor', 'latin_name' => 'Helleborus', 'type_id' => '5'],
            ['name' => 'Jácint', 'latin_name' => 'Hyacinthus', 'type_id' => '5'],
            ['name' => 'Szellőrózsa', 'latin_name' => 'Anemone', 'type_id' => '5'],
            ['name' => 'Nárcisz', 'latin_name' => 'Narcissus', 'type_id' => '5'],
            ['name' => 'Sárga viola', 'latin_name' => 'Erysimum cheiri', 'type_id' => '5'],
            ['name' => 'Gyöngyvirág', 'latin_name' => 'Convallaria majalis', 'type_id' => '5'],
            ['name' => 'Kikerics', 'latin_name' => 'Colchicum', 'type_id' => '5'],
            ['name' => 'Orgona', 'latin_name' => 'Syringa', 'type_id' => '5'],
            ['name' => 'Berkenye', 'latin_name' => 'Sorbus', 'type_id' => '5'],
            ['name' => 'Napraforgó', 'latin_name' => 'Helianthus annuus', 'type_id' => '5'],
            ['name' => 'Kristályvirág', 'latin_name' => 'Delosperma', 'type_id' => '5'],
            ['name' => 'Sarkantyúvirág', 'latin_name' => 'Centranthus', 'type_id' => '5'],
            ['name' => 'Kerti szegfű', 'latin_name' => 'Dianthus caryophyllus', 'type_id' => '5'],
            ['name' => 'Dália', 'latin_name' => 'Dahlia', 'type_id' => '5'],
            ['name' => 'Levendula', 'latin_name' => 'Lavandula', 'type_id' => '5'],
            ['name' => 'Cipruska', 'latin_name' => 'Santolina', 'type_id' => '5'],
            ['name' => 'Kardvirág', 'latin_name' => 'Gladiolus', 'type_id' => '5'],
            ['name' => 'Hibiszkusz', 'latin_name' => 'Hibiscus', 'type_id' => '5'],
            ['name' => 'Pampafű', 'latin_name' => 'Cortaderia', 'type_id' => '5'],
            ['name' => 'Krizantém', 'latin_name' => 'Chrysanthemum', 'type_id' => '5'],

            //Gomba
            ['name' => 'Laskagomba', 'latin_name' => 'Pleurotus ostreatus', 'type_id' => '6'],
            ['name' => 'Őzlábgomba', 'latin_name' => 'Macrolepiota procera', 'type_id' => '6'],
            ['name' => 'Rókagomba', 'latin_name' => 'Cantharellus cibarius', 'type_id' => '6'],
            ['name' => 'Tinóru gomba', 'latin_name' => 'Aureoboletus', 'type_id' => '6'],
            ['name' => 'Szarvasgomba', 'latin_name' => 'Fungus cervinus', 'type_id' => '6'],
            ['name' => 'Acélkékes galambgomba', 'latin_name' => 'Russula anatina', 'type_id' => '6'],
            ['name' => 'Acélszürke galambgomba', 'latin_name' => ' 	Russula medullata', 'type_id' => '6'],
            ['name' => 'Ágas taplógomba', 'latin_name' => 'Grifola frondosa', 'type_id' => '6'],
            ['name' => 'Ágas-bogas likacsosgomba', 'latin_name' => 'Cladomeris umbellatus', 'type_id' => '6'],
            ['name' => 'Akáccsiperke', 'latin_name' => 'Agaricus bresadolanus', 'type_id' => '6'],
            ['name' => 'Csiperke', 'latin_name' => 'Agaricus', 'type_id' => '6'],
            ['name' => 'Vargánya', 'latin_name' => 'Boletus', 'type_id' => '6'],
            ['name' => 'Császárgalóca', 'latin_name' => 'Amanita caesarea', 'type_id' => '6'],
            ['name' => 'Disznófülgomba', 'latin_name' => 'Gomphus clavatus', 'type_id' => '6'],
            ['name' => 'Rizike', 'latin_name' => 'Lactarius', 'type_id' => '6'],
            ['name' => 'Shiitake', 'latin_name' => 'Lentinula edodes', 'type_id' => '6'],

            //Egyéb
            ['name' => 'Egyéb', 'latin_name' => 'egyéb', 'type_id' => '7'],
           ]);
    }
}
