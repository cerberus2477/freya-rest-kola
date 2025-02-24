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
            ['name' => 'mag'],
            ['name' => 'palánta'],
            ['name' => 'növény'],
            ['name' => 'termés'],
            ['name' => 'késztermék'],
            ['name' => 'egyéb'],
        ]);
    }
}