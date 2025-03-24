<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Plant;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'plant_id' =>Plant::inRandomOrder()->first()->id??Plant::factory()->create()->id,
            'author_id' => User::inRandomOrder()->whereIn('role_id', [1, 2])->first()->id ?? User::factory()->create(['role_id' => 2])->id,
            'category_id' => Category::inRandomOrder()->first()->id??Category::factory()->create()->id,
            'description' => $this->faker->text(200),
            'content' => $this->generateMarkdown(),
            'image' => $this->faker->imageUrl(),
            'source' => $this->faker->url,
        ];
    }

    private function generateMarkdown()
    {
        $faker = $this->faker;
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