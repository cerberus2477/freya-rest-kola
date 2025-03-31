<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
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
    // public function test_user_can_delete_their_own_listing()
    // {
    //     //creating a listing to test with
    //     $listing = Listing::factory()->create();

    //     //"loggin in" as the user who owns the listing
    //     $this->actingAs($listing->userPlant->user);
        
    //     //deleting the listing as the user
    //     $response = $this->deleteJson(route('listings.destroy', $listing->id));
        
    //     //checking if the api response is as expected
    //     $response//->assertStatus(201)
    //              ->assertJson(['status' => 201, 'message' => 'Listing deleted successfully']);
        
    //     //checking if the listing has been truly deleted
    //     $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    // }


    // // public function test_user_cannot_delete_other_users_listing()
    // // {
    // //     $user = User::factory()->create();
    // //     $otherUser = User::factory()->create();
    // //     $userPlant = UserPlant::factory()->create(['user_id' => $otherUser->id]);
    // //     $listing = Listing::factory()->create(['user_plants_id' => $userPlant->id]);
 
    // //     $this->actingAs($user);
 
    // //     $response = $this->deleteJson(route('listings.destroy', $listing->id));
 
    // //     $response->assertStatus(403)
    // //              ->assertJson(['status' => 403, 'message' => "You don't have permission to modify this listing"]);
 
    // //     $this->assertDatabaseHas('listings', ['id' => $listing->id]);
    // // }


    // public function test_admin_can_delete_any_listing()
    // {
    //     $admin = User::factory()->withRole(1)->create();
    //     // $admin->tokens()->create(['name' => 'admin', 'abilities' => ['admin']]);
        
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
    //     $user = User::factory()->withRole(1)->create();
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

    //Filters & Search
    public function test_can_get_all_listings_matching_filters()
    {

        //users
        $user1 = User::factory()->create([
            'username' => 'aaa',
        ]);
        $user2 = User::factory()->create([
            'username' => 'bbb',
        ]);
        $user3 = User::factory()->create([
            'username' => 'ccc',
        ]);

        //userplants
        $userplant1 = UserPlant::factory()->create([
            'user_id' => $user1->id,
            'plant_id' => 1, //alma
            'stage_id' => 4 //termés
        ]);
        $userplant2 = UserPlant::factory()->create([
            'user_id' => $user2->id,
            'plant_id' => 2, //körte
            'stage_id' => 3 //növény
        ]);
        $userplant3 = UserPlant::factory()->create([
            'user_id' => $user3->id,
            'plant_id' => 3, //banán
            'stage_id' => 2 //palánta
        ]);
        $userplant4 = UserPlant::factory()->create([
            'user_id' => $user1->id,
            'plant_id' => 50, //zöldborsó
            'stage_id' => 1 //mag
        ]);


        $listing1 = Listing::factory()->create([
            'user_plants_id' => $userplant1->id,
        ]);
        $listing2 = Listing::factory()->create([
            'user_plants_id' => $userplant2->id,
        ]);
        $listing3 = Listing::factory()->create([
            'user_plants_id' => $userplant3->id,
        ]);
        $listing4 = Listing::factory()->create([
            'user_plants_id' => $userplant4->id,
        ]);


        //plant
        $response = $this->get('/api/listings?plant=Alma');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['listing_id', 'title', 'description', 'media', 'price', 'created_at', 'user', 'plant', 'stage']
            ]
        ]);
        $response->assertJsonPath('data.0.plant.name', 'Alma');


        //user
        $response = $this->get('/api/listings?user=bbb');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['listing_id', 'title', 'description', 'media', 'price', 'created_at', 'user', 'plant', 'stage']
            ]
        ]);
        $responseData = $response->decodeResponseJson()->json();
        foreach ($responseData['data'] as $item) {
            $this->assertEquals('bbb', $item['user']['username']);
        }

        //noUser
        $response = $this->get('/api/listings?user=fff');
        $response->assertJson([
            'status' => 200,
            'message' => 'Listings retrieved successfully', //TODO: "No listings found" kene ide no?
        ]);

        //type
        $response = $this->get('/api/listings?type=gyümölcs');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'listing_id',
                    'title',
                    'description',
                    'media',
                    'price',
                    'created_at',
                    'user',
                    'plant',
                    'stage']
            ]
        ]);
        $responseData = $response->decodeResponseJson()->json();
        $n = 0;
        foreach ($responseData['data'] as $item) {
            $this->assertEquals('gyümölcs', $item['plant']['type']);
            $n = $n+1;
        }
        $this->assertEquals(3, $n);

        //stage
        $response = $this->get('/api/listings?stage=mag');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'listing_id',
                    'title',
                    'description',
                    'media',
                    'price',
                    'created_at',
                    'user',
                    'plant',
                    'stage']
            ]
        ]);
        $response->assertJsonPath('data.0.stage.name', 'mag');
    }
    
}
