<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //feel free to add ideas
        DB::table('categories')->insert([
            ['name' => 'recept'],
            ['name' => 'hasznos tippek'],
            ['name' => 'alkalmazás használata'],
            ['name' => 'növények gondozása'],
        ]);
    }
}