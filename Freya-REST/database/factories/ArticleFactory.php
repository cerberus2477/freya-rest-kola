<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Plant;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\StorageHelper;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        $imagePaths = StorageHelper::generateImagesToFolder('articles', 1, 5); // Generate 1 to 5 images for the articles content

        return [
            'title' => $this->faker->sentence,
            'plant_id' => Plant::inRandomOrder()->first()->id,
            //creates user with stats role if needed
            'author_id' => User::inRandomOrder()->whereIn('role_id', [1, 2])->first()->id ?? User::factory()->create(['role_id' => 2])->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->text(200),
            'content' => $this->generateMarkdown($imagePaths, rand(50, 100)),
            'source' => $this->faker->url,
        ];
    }


    public function withoutPlant(): static
    {
        //if admin, have shovel as profile pic
        return $this->state(fn (array $attributes) => [
            'plant_id' => null,
        ]);
    }


    private function generateMarkdown(array $imagePaths, int $length): string
    {
        $faker = $this->faker;
        $baseUrl = getenv('BASE_URL') . '/storage/app/public/';

        $mdElements = [
            "## " . $faker->sentence . "\n\n", 
            "### " . $faker->sentence . "\n\n", 
            "#### " . $faker->sentence . "\n\n", 
            "**" . $faker->sentence . "**\n\n",
            "* " . $faker->sentence . "\n",
            "- " . $faker->sentence . "\n",
            "1. " . $faker->sentence . "\n",
            "> " . $faker->sentence . "\n\n",
            "```\n" . $faker->paragraph . "\n```\n"
        ];
        
        $text = "";
        $usedHeadings = [];
        
        for ($i = 0; $i < $length; $i++) {
            do {
                $element = $faker->randomElement($mdElements);
            } while (!$this->isValidHeadingOrder($element, $usedHeadings));
            
            if (preg_match('/^(##+)/', $element, $matches)) {
                $usedHeadings[] = $matches[1];
            }
            
            $text .= $element;
        }
        
        // Randomly insert images into the text
        foreach ($imagePaths as $imagePath) {
            // $imageUrl = $baseUrl . ltrim($imagePath, '/');
            $imageUrl = $baseUrl . $imagePath;
            
            $imageMarkdown = "\n![$faker->sentence]($imageUrl)\n\n";
            
            // Split text into segments and insert images randomly
            $segments = preg_split('/(\n\n)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
            $insertPosition = rand(0, count($segments) - 1);
            array_splice($segments, $insertPosition, 0, $imageMarkdown);
            $text = implode('', $segments);
        }
        
        return trim($text);
    }
    
    private function isValidHeadingOrder(string $element, array &$usedHeadings): bool
    {
        if (preg_match('/^(##+)/', $element, $matches)) {
            $currentLevel = strlen($matches[1]);
            
            if ($currentLevel > 2 && !in_array(str_repeat('#', $currentLevel - 1), $usedHeadings)) {
                return false;
            }
        }
        return true;
    }

}