<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Stages')->insert([
            ['Stage_name' => 'mag'],
            ['Stage_name' => 'palánta'],
            ['Stage_name' => 'növény'],
            ['Stage_name' => 'termés'],
            ['Stage_name' => 'késztermék'],
            ['Stage_name' => 'egyéb'],
        ]);
    }
}
