<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Plant;
use App\Models\Category;
use Database\Seeders\PlantSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TypeSeeder;

use function PHPSTORM_META\type;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class); // Seed roles before each test
        $this->seed(TypeSeeder::class); // Seed categories before each test
        $this->seed(CategorySeeder::class); // Seed plants before each test
        $this->seed(PlantSeeder::class); // Seed plants before each test
    }

     // Test for retrieving all articles
     public function test_index_all_articles()
     {
         // Create dummy articles with necessary related models
         $user = User::factory()->create(['role_id' => 3]);
         $category = Category::inRandomOrder()->first();
         $plant = Plant::inRandomOrder()->first();
         Article::factory()->count(3)->create([
             'author_id' => $user->id,
             'category_id' => $category->id,
             'plant_id' => $plant->id
         ]);
         $response = $this->get('/api/articles?all=true');
         $response->assertStatus(200);
         $response->assertJsonStructure([
             'status', 'message', 'data' => [
                 '*' => ['id', 'title', 'category', 'description', 'updated_at', 'plant_name', 'type', 'author']
             ]
         ]);
    }
    // Test for paginated articles
    public function test_index_paginated_articles()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $category = Category::inRandomOrder()->first();
        $plant = Plant::inRandomOrder()->first();
        Article::factory()->count(10)->create([
            'author_id' => $user->id,
            'category_id' => $category->id,
            'plant_id' => $plant->id
        ]);
        $response = $this->get('/api/articles?pageSize=5&page=1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
                'status', 'message', 'data' => [
                 '*' => ['id', 'title', 'category', 'description', 'updated_at', 'plant_name', 'type', 'author']
             ],
             'pagination' => [
                 'total', 'page', 'pageSize', 'totalPages'
             ]
        ]);
    }
     // Test for article search with filters
     public function test_search_articles_with_filters()
     {
         // Create related models
         $user1 = User::factory()->create(['username' => 'JohnDoe']);
         $user2 = User::factory()->create(['username' => 'JaneDoe']); 
         $category1 = Category::inRandomOrder()->first();  
         $category2 = Category::inRandomOrder()->first();  
         $plant1 = Plant::inRandomOrder()->first();   
         $plant2 = Plant::inRandomOrder()->first();
         // Create dummy articles with different attributes
         $article1 = Article::factory()->create([
             'title' => 'Test Article 1',
             'author_id' => $user1->id,
             'category_id' => $category1->id,
             'plant_id' => $plant1->id,
             'description' => 'This is a test article about plants and science.',
         ]);
         $article2 = Article::factory()->create([
             'title' => 'Test Article 2',
             'author_id' => $user2->id,
             'category_id' => $category2->id,
             'plant_id' => $plant2->id,
             'description' => 'This is a test article about plants and technology.',
         ]);
         $article3 = Article::factory()->create([
            'title' => 'Test Article 3',
            'author_id' => $user1->id,
            'category_id' => $category1->id,
            'plant_id' => $plant2->id,
            'description' => 'This is a test article about plants and technology.',
        ]);
         // Perform the search with filters
         $response = $this->get('/api/articles?q=test&author=JohnDoe&category='.$category1->id);
         // Check the response status and structure
         $response->assertStatus(200);
         $response->assertJsonStructure([
             'status',
             'message',
             'data' => [
                 '*' => ['id', 'title', 'category', 'description', 'updated_at', 'plant_name', 'author']
             ]
             ]);
         // Assert the returned data contains the expected article(s)
         $response->assertJsonFragment([
            'title' => 'Test Article 1',
            // 'author' => 'JohnDoe',
            'category' => $category1->name
         ]);
         $response->assertJsonFragment([
            'data' => [
                'title' => 'Test Article 1',
                'author' => 'JohnDoe',
                'category' => $category1->name,
            ]
         ]);      
         // Assert the returned data does not contain an article that shouldn't be included
         $response->assertJsonMissing([
            'data' => [
                'title' => 'Test Article 2',
                'author' => 'JaneDoe',
                'category' => $category2->name,
            ]
         ]);
        }
      // Test for retrieving a specific article
      public function test_show_article_found()
      {
          $user = User::factory()->create();
          $category = Category::inRandomOrder()->first();
          $plant = Plant::inRandomOrder()->first();
          $article = Article::factory()->create([
              'author_id' => $user->id,
              'category_id' => $category->id,
              'plant_id' => $plant->id
          ]);
          $response = $this->get('/api/articles/' . $article->title);
          $response->assertStatus(200);
          $response->assertJsonStructure([
              'status', 'message', 'data' => ['id', 'title', 'category', 'description', 'content', 'created_at']
          ]);
        }
     // Test for article not found
     public function test_show_article_not_found()
     {
         $response = $this->get('/api/articles/'.urlencode('Non-Existent-Article'));
         $response->assertStatus(404);
         $response->assertJson([
             'status' => 404,
             'message' => 'Article not found',
             'data' => []
         ]);
     }
}