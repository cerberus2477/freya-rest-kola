<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\UserPlant;
use Tests\TestCase;
use App\Models\Listing;
use App\Models\User;

class ListingControllerTest extends TestCase
{
    use RefreshDatabase; // Resets DB after each test

    /**
     * Test fetching all listings without pagination
     */
    public function test_can_get_all_listings()
    {
        Listing::factory()->count(3)->create(); // Create dummy listings

        $response = $this->getJson('/api/listings?all=true');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'media', 'price', 'stage', 'plant_name', 'plant_type']
                     ]
                 ]);
    }

    /**
     * Test fetching paginated listings
     */
    public function test_can_get_paginated_listings()
    {
        Listing::factory()->count(10)->create();

        $response = $this->getJson('/api/listings?pageSize=5&page=1');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [['id', 'title', 'media', 'price', 'user', 'plant', 'type', 'stage']],
                     'pagination' => ['total', 'page', 'pageSize', 'totalPages']
                 ]);
    }

    /**
     * Test filtering listings by title
     */
    public function test_can_filter_listings_by_title()
    {
        Listing::factory()->create(['title' => 'Special Plant']);

        $response = $this->getJson('/api/listings?title=Special');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Special Plant']);
    }

    /**
     * Test filtering by min and max price
     */
    public function test_can_filter_listings_by_price_range()
    {
        Listing::factory()->create(['price' => 50]);
        Listing::factory()->create(['price' => 100]);
        Listing::factory()->create(['price' => 200]);

        $response = $this->getJson('/api/listings?minPrice=50&maxPrice=150');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data'); // Expect 2 listings
    }

    /**
     * Test getting a single listing by ID
     */
    public function test_can_get_single_listing()
    {
        $listing = Listing::factory()->create();

        $response = $this->getJson("/api/listings/{$listing->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $listing->id]);
    }

    /**
     * Test getting a non-existent listing
     */
    public function test_returns_404_for_non_existent_listing()
    {
        $response = $this->getJson('/api/listings/9999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'Listing not found',
                     'data' => []
                 ]);
    }


    public function a_user_can_delete_their_own_listing()
    {
        // Create a user and authenticate
        $user = User::factory()->create();

        // Create a UserPlant associated with the user
        $userPlant = UserPlant::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create a Listing associated with the UserPlant
        $listing = Listing::factory()->create([
            'user_plants_id' => $userPlant->id,
        ]);

        // Authenticate the user
        dump($user);
        $this->actingAs($user);

        // Send DELETE request to remove the listing
        $response = $this->deleteJson(route('listings.destroy', $listing->id));

        // Assert the response is successful (200 OK)
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Listing deleted successfully',
                 ]);

        // Assert the listing is deleted from the database
        $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    }
}