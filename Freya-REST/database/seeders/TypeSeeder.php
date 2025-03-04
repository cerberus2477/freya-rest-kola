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
            ['name' => 'gyümölcs'],     //1
            ['name' => 'zöldség'],      //2
            ['name' => 'fűszernövény'], //3
            ['name' => 'szobanövény'],  //4
            ['name' => 'virág'],        //5
            ['name' => 'gomba'],        //6
            ['name' => 'egyéb'],        //7
        ]);
    }
}