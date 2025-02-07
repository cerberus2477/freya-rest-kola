<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Types')->insert([
            ['Type_name' => 'gyümölcs'],
            ['Type_name' => 'zöldség'],
            ['Type_name' => 'fűszernövény'],
            ['Type_name' => 'szobanövény'],
            ['Type_name' => 'virág'],
            ['Type_name' => 'gomba'],
            ['Type_name' => 'egyéb'],
        ]);
    }
}
