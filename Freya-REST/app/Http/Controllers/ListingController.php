<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Listing;
use App\Http\Requests\ListingRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\Helpers\StorageHelper;

class ListingController extends BaseController
{
    //functions to be used in index, search, show, that have to do with retriveing and formatting data
    protected function baseQuery()
    {
        return Listing::with([
            'userPlant.user',
            'userPlant.plant.type',
            'userPlant.stage'
        ]);
    }

    
    //TODO: format datetime if necessary (we need both date and time)
    protected function formatListings($listings)
    {
        return collect($listings)->map(function ($listing) {
            return [
                'listing_id' => $listing->id,
                'title' => $listing->title,
                'description' => $listing->description,
                //the json array stored in db is decoded, and each filename gets turned into the full path of the image
                'media' => $listing->media 
                ? array_map(fn($file) => Storage::url("public/" . $file), json_decode($listing->media, true) ?? []) 
                : [],
                'price' => $listing->price,
                'created_at' => $listing->created_at,
                'user' => [
                    'id' => $listing->userPlant->user->id,
                    'username' => $listing->userPlant->user->username,
                ],
                'plant' => [
                    'id' => $listing->userPlant->plant->id,
                    'name' => $listing->userPlant->plant->name,
                    'type' => $listing->userPlant->plant->type->name,
                ],
                'stage' => [
                    'name' => $listing->userPlant->stage->name,
                ],
            ];
        });
    }
 
    public function search(Request $request)
    {
        $cacheKey = 'listings_search_' . md5($request->fullUrl());
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Start with the base query
        $query = $this->baseQuery();

        // Search by title, plant, and optionally in description
        $q = $request->query('q', '');
        if (!empty($q)) {
            $query->where(function ($query) use ($q, $request) {
                $query->where('title', 'LIKE', "%$q%")
                    ->orWhereHas('userPlant.plant', function ($plantQuery) use ($q) {
                        $plantQuery->where('name', 'LIKE', "%$q%");
                    });
                if ($request->has("deep")) {
                    $query->orWhere('description', 'LIKE', "%$q%");
                }
            });
        }

        // Filters
        //TODO: warning here, correct this or test if it works really
        $filters = [
            'user' => ['column' => 'users.username', 'relationship' => 'userPlant.user'],
            'plant' => ['column' => 'plants.name', 'relationship' => 'userPlant.plant'],
            'type' => ['column' => 'types.name', 'relationship' => 'userPlant.plant.type'],
            'stage' => ['column' => 'stages.name', 'relationship' => 'userPlant.stage'],
        ];

        foreach ($filters as $param => $filter) {
            if ($value = $request->query($param)) {
                $query->whereHas($filter['relationship'], function ($relationshipQuery) use ($filter, $value) {
                    $relationshipQuery->where($filter['column'], '=', $value);
                });
            }
        }

        // Price filters
        if ($minPrice = $request->query('minprice')) {
            $query->where('listings.price', '>=', $minPrice);
        }
        if ($maxPrice = $request->query('maxprice')) {
            $query->where('listings.price', '<=', $maxPrice);
        }


        $pageSize = $request->query('pageSize', 5);

        // Return all matching results
        if ($pageSize === 'all') 
        {
            $listings = $query->get();
            $formattedListings = $this->formatListings($listings);
            $response = $this->jsonResponse(200, 'Listings retrieved successfully', $formattedListings);
        } 
        else{
            //TODO: test this, refactor
            // TODO: $articles = $query->paginate($pageSize, ['*'], 'page', $page); lehet e pl, kell e az a hosszú  $paginatedListings

            // Return a page of matching results
            $pageSize = intval($pageSize); // make sure that pagesize is a number
            $page = $request->query('page', 1);
            $listings = $query->paginate($pageSize, ['*'], 'page', $page);
            // Format the paginated listings
            $formattedListings = $this->formatListings($listings->items());
            $paginatedListings = new \Illuminate\Pagination\LengthAwarePaginator(
                $formattedListings,
                $listings->total(),
                $listings->perPage(),
                $listings->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $response = $this->jsonResponse(200, 'Listings retrieved successfully', $paginatedListings);
        }

        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(10));
        return $response;
    }

    
    public function show($id)
    {
        $cacheKey = 'listings_show_' . $id;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Retrieve listing with related data using the baseQuery
        $listing = $this->baseQuery()->find($id);

        if (!$listing) {
            return $this->jsonResponse(404, "$id. listing not found");
        }

        // Format the listing
        $formattedListing = $this->formatListings(collect([$listing]))->first();

        // Cache the response
        $response = $this->jsonResponse(200, "$id. listing found", $formattedListing);
        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(10));

        return $response;
    }


   //TODO test the image upload function
    public function create(ListingRequest $request)   
    {
        //TODO: there could be error cathing here, if its realistic that the saving fails. otherwise this could be put straight in $data
        $imagePaths = StorageHelper::storeRequestImages($request, 'listings');

        // Store filenames (only filename, without path) in the DB as JSON
        $data = array_merge($request->validated(), ['media' => json_encode($imagePaths)]);
        $listing = Listing::create($data);
        return $this->jsonResponse(201, 'Listing created successfully', $listing);
    }


    public function update(ListingRequest $request, $id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            return $this->jsonResponse(404, 'Listing not found');
        }

        $user = $request->user();

        // Check permissions
        if(!$user->tokenCan('admin') && $user->$id != $listing->userPlant()->user()->id){
            return $this->jsonResponse(403, "You don't have permission to modify this listing");
        }

        // delete previous photos and save the new ones
        $previousImages = json_decode($listing->media, true);
        if ($previousImages) StorageHelper::deleteMedia($previousImages, 'listings');
        $newImagePaths = StorageHelper::storeRequestImages($request, 'listings');

        // Update listing with new data
        $data = array_merge($request->validated(), ['media' => json_encode($newImagePaths)]);
        $listing->update($data);
        return $this->jsonResponse(201, 'Listing updated successfully', $listing);
    }


    public function destroy(ListingRequest $request, $id)
    {
        // Fetch the listing with the userPlant relationship
        $listing = Listing::with('userPlant')->find($id);
        $user = $request->user();

        // If the listing doesn't exist, return a 404 response
        if (!$listing) {
            return $this->jsonResponse(404, 'Listing not found');
        }

        // If the user doesn't have permission, return a 403 response
        if(!$user->tokenCan('admin') && $user->id != $listing->userPlant()->user()->id){
            return $this->jsonResponse(403, "You don't have permission to modify this listing");
        }

        // if (!$user->tokenCan('admin') && $user->id !== $listing->userPlant->user->id) {
        //     return $this->jsonResponse(403, "You don't have permission to modify this listing");
        // }

        // delete the images from storage and finally delete the listing from db
        StorageHelper::deleteMedia($listing, 'listings');
        $listing->delete();
        return $this->jsonResponse(201, 'Listing deleted successfully');
    }
}




// APIDOC COMMENTS 
//TODO: maybie put these in a sepereate file


/**
 * @api {delete} /listing/:id Delete Listing
 * @apiName DeleteListing
 * @apiGroup Listing
 * @apiDescription Delete an existing listing.
 *
 * @apiParam {Integer} id The ID of the listing to delete.
 *
 * @apiSuccess {Integer} status HTTP status code.
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Hirdetés sikeresen törölve"
 *     }
 *
 */





/**
 * @api {patch} /listing/:id Update Listing
 * @apiName UpdateListing
 * @apiGroup Listing
 * @apiDescription Update an existing listing.
 *
 * @apiParam {Integer} id The ID of the listing to update.
 *
 * @apiBody {Integer} [user_plants_id] Optional ID of the user's plant.
 * @apiBody {String} [title] Optional title of the listing.
 * @apiBody {String} [description] Optional description of the listing.
 * @apiBody {String} [city] Optional city where the listing is located.
 * @apiBody {String} [media] Optional media file or URL.
 * @apiBody {Integer} [price] Optional price of the listing.
 *
 * @apiSuccess {Integer} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object} data The updated listing.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Hirdetés sikeresen módosítva",
 *         "data": {
 *             "id": 1,
 *             "user_plants_id": 5,
 *             "title": "Updated Plant Title",
 *             "description": "Updated description.",
 *             "city": "Budapest",
 *             "media": "plant.jpg",
 *             "price": 1200,
 *             "created_at": "2023-10-01T12:00:00.000000Z",
 *             "updated_at": "2023-10-01T12:30:00.000000Z"
 *         }
 *     }
 *
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *         "status": 404,
 *         "message": "Listing not found."
 *         "data": []
 *     }
 */



     //TODO: check apicomments below this line. 
    /**
     * @api {post} /listing Create Listing
     * @apiName CreateListing
     * @apiGroup Listing
     * @apiDescription Create a new listing.
     *
     * @apiBody {Integer} user_plants_id The ID of the user's plant.
     * @apiBody {String} title The title of the listing.
     * @apiBody {String} description The description of the listing.
     * @apiBody {String} city The city where the listing is located.
     * @apiBody {String} [media] Optional media file or URL.
     * @apiBody {Integer} price The price of the listing.
     *
     * @apiSuccess {Integer} status HTTP status code.
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Object} data The created listing.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "status": 201,
     *         "message": "Hirdetés sikeresen létrehozva",
     *         "data": {
     *             "id": 1,
     *             "user_plants_id": 5,
     *             "title": "Beautiful Plant",
     *             "description": "A very healthy plant.",
     *             "city": "Budapest",
     *             "media": "plant.jpg",
     *             "price": 1000,
     *             "created_at": "2023-10-01T12:00:00.000000Z",
     *             "updated_at": "2023-10-01T12:00:00.000000Z"
     *         }
     *     }
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     */




         /**
 * @api {get} /listings/:id Get Single Listing
 * @apiName GetListing
 * @apiGroup Listing
 * @apiDescription Retrieve details of a single listing.
 *
 * @apiParam {Integer} id The ID of the listing to retrieve.
 *
 * @apiSuccess {Integer} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object} data The listing details.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Listing found",
 *         "data": {
 *             "id": 2,
 *             "title": "Assumenda et repudiandae est laboriosam vitae nihil.",
 *             "description": "Et dolores aliquid delectus reprehenderit sunt distinctio molestias exercitationem.",
 *             "media": "http://example.com/image.jpg",
 *             "price": 6000,
 *             "created_at": "2025-03-03 19:29:45",
 *             "user": "lenna20",
 *             "plant": "Málna",
 *             "type": "gyümölcs",
 *             "stage": "növény",
 *             "email": "user@example.com"
 *         }
 *     }
 */







     // GET /api/listings/search?q=&deep&&user=&plant=&type=&stage&minprice=&maxprice=&all

    /**
 * @api {get} /listings/search Search Listings
 * @apiName SearchListings
 * @apiGroup Listing
 * @apiDescription Search listings based on filters.
 *
 * @apiParam {String} [q] Search query for title and plant name.
 * @apiParam {Boolean} [deep] If set, also searches in descriptions.
 * @apiParam {String} [user] Filter by username.
 * @apiParam {String} [plant] Filter by plant name.
 * @apiParam {String} [type] Filter by plant type.
 * @apiParam {String} [stage] Filter by plant stage.
 * @apiParam {Integer} [minprice] Filter by minimum price.
 * @apiParam {Integer} [maxprice] Filter by maximum price.
 * @apiParam {Boolean} [all] If set, retrieves all matching results without pagination.
 * @apiParam {Integer} [pageSize=5] Number of listings per page.
 * @apiParam {Integer} [page=1] Page number for pagination.
 *
 * @apiSuccess {Integer} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object[]} data Array of matching listings.
 * @apiSuccess {Object} pagination Pagination metadata.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Listings retrieved successfully",
 *         "data": [
 *             {
 *                 "id": 1,
 *                 "title": "Culpa ab a quibusdam est debitis rerum.",
 *                 "description": "Aut odio facere consequatur incidunt minus iste.",
 *                 "media": "https://example.com/image.jpg",
 *                 "price": 16200,
 *                 "created_at": "2025-03-03 19:29:45",
 *                 "user": "mable.brakus",
 *                 "plant": "Gránátalma",
 *                 "type": "gyümölcs",
 *                 "stage": "palánta"
 *             }
 *         ],
 *         "pagination": {
 *             "total": 1,
 *             "page": 1,
 *             "pageSize": 5,
 *             "totalPages": 1
 *         }
 *     }
 */






     //TODO modify apidoc for current responses

    // GET /api/articles?all
    /**
 * @api {get} /listings Get Listings
 * @apiName GetListings
 * @apiGroup Listing
 * @apiDescription Retrieve a paginated list of listings or all listings.
 *
 * @apiParam {Boolean} [all] If set, retrieves all listings without pagination.
 * @apiParam {Integer} [pageSize=5] Number of listings per page.
 * @apiParam {Integer} [page=1] Page number for pagination.
 *
 * @apiSuccess {Integer} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object[]} data Array of listings.
 * @apiSuccess {Object} pagination Pagination metadata.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Listings retrieved successfully",
 *         "data": [
 *             {
 *                 "id": 1,
 *                 "title": "Culpa ab a quibusdam est debitis rerum.",
 *                 "description": "Aut odio facere consequatur incidunt minus iste.",
 *                 "media": "https://example.com/image.jpg",
 *                 "price": 16200,
 *                 "created_at": "2025-03-03 19:29:45",
 *                 "user": "mable.brakus",
 *                 "plant": "Gránátalma",
 *                 "type": "gyümölcs",
 *                 "stage": "palánta"
 *             }
 *         ],
 *         "pagination": {
 *             "total": 15,
 *             "page": 1,
 *             "pageSize": 5,
 *             "totalPages": 3
 *         }
 *     }
 */
