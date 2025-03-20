<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Plant;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class); // Seed roles before each test
    }

    // Test for retrieving all articles
    public function test_index_all_articles()
    {
        // Create dummy articles with necessary related models
        $user = User::factory()->create(['role_id' => 3]);
        $category = Category::factory()->create();
        $plant = Plant::factory()->create();

        Article::factory()->count(3)->create([
            'author_id' => $user->id,
            'category_id' => $category->id,
            'plant_id' => $plant->id
        ]);

        $response = $this->get('/api/articles?all=true');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'message', 'data' => [
                '*' => ['id', 'title', 'name', 'description', 'updated_at', 'plant_name', 'author']
            ]
        ]);
    }

   // Test for paginated articles
   public function test_index_paginated_articles()
   {
       $roles = ['admin', 'stats', 'user'];
       $user = User::factory()->create();
       $category = Category::factory()->create();
       $plant = Plant::factory()->create();

       Article::factory()->count(10)->create([
           'author_id' => $user->id,
           'category_id' => $category->id,
           'plant_id' => $plant->id
       ]);

       $response = $this->get('/api/articles?page=1&pageSize=5');

       $response->assertStatus(200);
       $response->assertJsonStructure([
           'status', 'message', 'data' => [
               'current_page', 'data' => [
                   '*' => ['id', 'title', 'name', 'description', 'updated_at', 'plant_name', 'author']
               ]
           ]
       ]);
   }

    // Test for article search with filters
    public function test_search_articles_with_filters()
    {
        // Create related models
        $user1 = User::factory()->create(['username' => 'JohnDoe']);
        $user2 = User::factory()->create(['username' => 'JaneDoe']);
        
        $category1 = Category::factory()->create(['name' => 'Science']);
        $category2 = Category::factory()->create(['name' => 'Technology']);
        
        $plant1 = Plant::factory()->create(['name' => 'PlantA']);
        $plant2 = Plant::factory()->create(['name' => 'PlantB']);

        // Create dummy articles with different attributes
        Article::factory()->create([
            'title' => 'Test Article 1',
            'author_id' => $user1->id,
            'category_id' => $category1->id,
            'plant_id' => $plant1->id,
            'description' => 'This is a test article about plants and science.',
        ]);

        Article::factory()->create([
            'title' => 'Test Article 2',
            'author_id' => $user2->id,
            'category_id' => $category2->id,
            'plant_id' => $plant2->id,
            'description' => 'This is a test article about plants and technology.',
        ]);

        Article::factory()->create([
            'title' => 'Test Article 3',
            'author_id' => $user1->id,
            'category_id' => $category1->id,
            'plant_id' => $plant1->id,
            'description' => 'Another article about science and plants.',
        ]);

        // Perform the search with filters
        $response = $this->get('/api/articles/search?q=test&author=JohnDoe&category=Science');

        // Check the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['id', 'title', 'name', 'description', 'updated_at', 'plant_name', 'author']
            ]
        ]);

        // Assert the returned data contains the expected article(s)
        $response->assertJsonFragment([
            'title' => 'Test Article 1',
            'author' => 'JohnDoe',
            'name' => 'Science',
        ]);

        $response->assertJsonFragment([
            'title' => 'Test Article 3',
            'author' => 'JohnDoe',
            'name' => 'Science',
        ]);
        
        // Assert the returned data does not contain an article that shouldn't be included
        $response->assertJsonMissing([
            'title' => 'Test Article 2',
            'author' => 'JaneDoe',
            'name' => 'Technology',
        ]);
    }

     // Test for retrieving a specific article
     public function test_show_article_found()
     {
         $user = User::factory()->create();
         $category = Category::factory()->create();
         $plant = Plant::factory()->create();
         $article = Article::factory()->create([
             'author_id' => $user->id,
             'category_id' => $category->id,
             'plant_id' => $plant->id
         ]);
 
         $response = $this->get('/api/articles/' . $article->title);
 
         $response->assertStatus(200);
         $response->assertJsonStructure([
             'status', 'message', 'data' => ['id', 'title', 'name', 'description', 'content', 'created_at']
         ]);
     }


    // Test for article not found
    public function test_show_article_not_found()
    {
        $response = $this->get('/api/articles/Non-Existent-Article');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'message' => 'Article not found',
            'data' => []
        ]);
    }
}