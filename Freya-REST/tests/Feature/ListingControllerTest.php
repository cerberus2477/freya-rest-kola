<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
                     'data' => [['id', 'title', 'media', 'sell', 'price', 'user', 'plant', 'type', 'stage']],
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
}