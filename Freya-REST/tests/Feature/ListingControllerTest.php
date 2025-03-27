<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserPlant;
use App\Models\Listing;

use Database\Seeders\TypeSeeder;
use Database\Seeders\StageSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PlantSeeder;


class ListingControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TypeSeeder::class); 
        $this->seed(StageSeeder::class); 
        $this->seed(RoleSeeder::class); 
        $this->seed(CategorySeeder::class); 
        $this->seed(PlantSeeder::class); 
    }

    //tests for destroy
    public function test_user_can_delete_their_own_listing()
    {
        //creating a listing to test with
        $listing = Listing::factory()->create();

        //"loggin in" as the user who owns the listing
        $this->actingAs($listing->userPlant->user);
        
        //deleting the listing as the user
        $response = $this->deleteJson(route('listings.destroy', $listing->id));
        
        //checking if the api response is as expected
        $response->assertStatus(201)
                 ->assertJson(['status' => 201, 'message' => 'Listing deleted successfully']);
        
        //checking if the listing has been truly deleted
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
