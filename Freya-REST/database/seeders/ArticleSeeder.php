<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Article::create([
                'title' => $faker->sentence,
                'plant_id' => $faker->optional()->numberBetween(1, 10), // Optional association with plants
                'author_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->optional()->numberBetween(1, 4),
                'description' => $faker->text(200),
                'content' => $this->generateMarkdown($faker),
                'source' => $faker->url        
            ]);
        }
    }

    private function generateMarkdown($faker)
    {
        $mdSentences = [
            "# " . $faker->sentence . "\n\n",
            "## " . $faker->sentence . "\n\n",
            "### " . $faker->sentence . "\n\n",
            "**" . $faker->sentence . "**\n\n",
            "* " . $faker->sentence . "\n",
            "* " . $faker->sentence . "\n",
            "- " . $faker->sentence . "\n"."- " . $faker->sentence . "\n"."- " . $faker->sentence . "\n",
            "1. " . $faker->sentence . "\n". "2. " . $faker->sentence . "\n". "3. " . $faker->sentence . "\n",
            "> " . $faker->sentence . "\n\n",
            "```\n" . $faker->paragraph . "\n```\n"
        ];
    
        $text = ""; // Initialize an empty string
    
        for ($i = 0; $i < 100; $i++) {
            $text .= $faker->randomElement($mdSentences);
        }
    
        return $text;
    }
}