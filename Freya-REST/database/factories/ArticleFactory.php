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
        $imagePaths = StorageHelper::generateImagesToFolder('articles', 1, 5); // Generate 1 to 5 images for the article

        return [
            'title' => $this->faker->sentence,
            'plant_id' => Plant::inRandomOrder()->first()->id,
            //creates user with stats role if needed
            'author_id' => User::inRandomOrder()->whereIn('role_id', [1, 2])->first()->id ?? User::factory()->create(['role_id' => 2])->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->text(200),
            'content' => $this->generateMarkdown($imagePaths),
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


    private function generateMarkdown($imagepaths, $length)
    {
        //TODO: put the images in random places of the md text. format it as a md link
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
        $mdtext = "";
        // Insert random sentences into markdown
        for ($i = 0; $i < $length; $i++;) {
            $mdtext .= $faker->randomElement($mdSentences);
        }

                        // Pick a random image path
                        $imagePath = $faker->randomElement($imagePaths);  
                
                        // Construct the full URL for the image
                        $imageUrl = $baseUrl . $imagePath;
                        
                        // Using the article title (or part of it) and a number for alt text
                        $altText = $faker->sentence . ' ' . rand(1, 100);  // Example: "Article Title 1"
                        
                        // Append the image markdown to the content
                        $text .= "\n![$altText]($imageUrl)\n\n";  // Insert full image link


                // Merge sentences and images into one array
                $merged = array_merge($sentences, $images);

                // Shuffle the final array to mix the content
                shuffle($merged);
                    
                return $text;
            
}
}