<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        Article::factory()->count(44)->create();
        Article::factory()->withoutPlant()->count(33)->create();
    }
}