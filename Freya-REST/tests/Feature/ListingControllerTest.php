<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Models\UserPlant;
use Illuminate\Support\Facades\Storage;

use Database\Seeders\RoleSeeder;
use Database\Seeders\TypeSeeder;
use Database\Seeders\PlantSeeder;
use Database\Seeders\CategorySeeder;

class ListingControllerTest extends TestCase
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

    //destroy
    public function test_user_can_delete_their_own_listing()
    {
        $user = User::factory()->create();


        // dd($user); // Debugging: Check what is returned
        $userPlant = UserPlant::factory()->create(['user_id' => $user->id]);
        $listing = Listing::factory()->create(['user_plants_id' => $userPlant->id]);
        
        // $this->actingAs($user);
        $this->actingAs(User::find($user->id));
        
        $response = $this->deleteJson(route('listings.destroy', $listing->id));
        
        $response->assertStatus(201)
                 ->assertJson(['status' => 201, 'message' => 'Listing deleted successfully']);
        
        $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    }


    // public function test_user_cannot_delete_other_users_listing()
    // {
    //     $user = User::factory()->create();
    //     $otherUser = User::factory()->create();
    //     $userPlant = UserPlant::factory()->create(['user_id' => $otherUser->id]);
    //     $listing = Listing::factory()->create(['user_plants_id' => $userPlant->id]);
        
    //     $this->actingAs($user);
        
    //     $response = $this->deleteJson(route('listings.destroy', $listing->id));
        
    //     $response->assertStatus(403)
    //              ->assertJson(['status' => 403, 'message' => "You don't have permission to modify this listing"]);
        
    //     $this->assertDatabaseHas('listings', ['id' => $listing->id]);
    // }


    // public function test_admin_can_delete_any_listing()
    // {
    //     $admin = User::factory()->create();
    //     $admin->tokens()->create(['name' => 'admin', 'abilities' => ['admin']]);
        
    //     $user = User::factory()->create();
    //     $userPlant = UserPlant::factory()->create(['user_id' => $user->id]);
    //     $listing = Listing::factory()->create(['user_plants_id' => $userPlant->id]);
        
    //     $this->actingAs($admin);
        
    //     $response = $this->deleteJson(route('listings.destroy', $listing->id));
        
    //     $response->assertStatus(201)
    //              ->assertJson(['status' => 201, 'message' => 'Listing deleted successfully']);
        
    //     $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    // }


    // public function test_destroy_returns_404_for_non_existent_listing()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
        
    //     $response = $this->deleteJson(route('listings.destroy', 9999));
        
    //     $response->assertStatus(404)
    //              ->assertJson(['status' => 404, 'message' => 'Listing not found']);
    // }


    // public function test_media_is_deleted_from_storage_when_listing_is_deleted()
    // {
    //     Storage::fake('public');
    //     $user = User::factory()->create();
    //     $userPlant = UserPlant::factory()->create(['user_id' => $user->id]);
        
    //     $listing = Listing::factory()->create([
    //         'user_plants_id' => $userPlant->id,
    //         'media' => json_encode(['test.jpg']),
    //     ]);
        
    //     Storage::put('public/listings/test.jpg', 'test content');
        
    //     $this->actingAs($user);
    //     $this->deleteJson(route('listings.destroy', $listing->id));
        
    //     Storage::assertMissing('public/listings/test.jpg');
    // }

    
}
