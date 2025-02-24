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
            ['name' => 'gyümölcs'],
            ['name' => 'zöldség'],
            ['name' => 'fűszernövény'],
            ['name' => 'szobanövény'],
            ['name' => 'virág'],
            ['name' => 'gomba'],
            ['name' => 'egyéb'],
        ]);
    }
}