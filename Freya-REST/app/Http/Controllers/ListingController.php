<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Listing;
use App\Http\Requests\ListingRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class ListingController extends BaseController
{
    protected function baseQuery()
    {
        return Listing::with([
            'userPlant.user',
            'userPlant.plant.type',
            'userPlant.stage'
        ]);
    }

    /**
     * Format listings with the required structure.
     */
    protected function formatListings($listings)
    {
        return $listings->map(function ($listing) {
            return [
                'listing_id' => $listing->id,
                'title' => $listing->title,
                'description' => $listing->description,
                'media' => $listing->media ? array_map(function ($media) {
                return Storage::url($media);
                }, $listing->media) : [],
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

public function index(Request $request)
{
    if ($request->has('all')) {
        // Fetch all listings with related data
        $listings = $this->baseQuery()->get();

        // Format the listings
        $response = $this->formatListings($listings);

        return $this->jsonResponse(200, 'All listings retrieved', $response);
    }

    // Paginate the listings
    $pageSize = $request->query('pageSize', 5);
    $page = $request->query('page', 1);
    $listings = $this->baseQuery()->paginate($pageSize, ['*'], 'page', $page);

    // Format the paginated listings
    $formattedListings = $this->formatListings($listings);

    return $this->jsonResponse(200, 'Listings retrieved successfully', $formattedListings);
}

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

 
public function search(Request $request)
{
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

    // Return all matching results
    if ($request->has("all")) {
        $listings = $query->get();
        $formattedListings = $this->formatListings($listings);
        return $this->jsonResponse(200, 'Listings retrieved successfully', $formattedListings);
    }

    // Return a page of matching results
    $pageSize = $request->query('pageSize', 5);
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

    return $this->jsonResponse(200, 'Listings retrieved successfully', $paginatedListings);
}

    
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
    //TODO: example here
     */

    public function create(ListingRequest $request)//TODO test the image upload function

    {
        $manager = new ImageManager(new Driver());

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $image) {
                // Create an image instance, scale down-if needed, and comress
                $imageInstance = $manager->read($image->getRealPath());
                $imageInstance->scaleDown(1920, 1080);
                $encodedImage = $imageInstance->toWebp(80);
 
                // Generate a unique filename with proper file format, save to public/listings/
                $filename = 'listing_' . uniqid() . '.webp';
                $path = 'public/listings/' . $filename;
                Storage::disk('public')->put($path, $encodedImage);
                // Store the public URL
                $imagePaths[] = Storage::url($path);
            }
        }

        $data = array_merge($request->validated(), ['media' => $imagePaths]);
        $listing = Listing::create($data);
        return $this->jsonResponse(201, 'Listing created succesfully', $listing);
    }

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
 //TODO: implement this 
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *         "status": 404,
 *         "message": "Listing not found."
 *         "data": []
 *     }
 */

    public function update(ListingRequest $request, $id)
    {
        $manager = new ImageManager(new Driver());

    // Handle image uploads
    $listing = Listing::where('title', $id)->first();
    $imagePaths = [];
    if ($request->hasFile('media')) {
        $images = $listing->media;
            if ($images) {
                foreach ($images as $file) {
                    $filePath = storage_path('app/public/public/listings/' . $file->filename);
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file from storage
                    }
                }
            }

        foreach ($request->file('media') as $image) {
            // Create an image instance, scale down if needed, and compress
            $imageInstance = $manager->read($image->getRealPath());
            $imageInstance->scaleDown(1920, 1080);
            $encodedImage = $imageInstance->toWebp(80);

            // Generate a unique filename with proper file format, save to public/listings/
            $filename = 'listing_' . uniqid() . '.webp';
            $path = 'public/listings/' . $filename;
            Storage::disk('public')->put($path, $encodedImage);

            // Store the public URL
            $imagePaths[] = Storage::url($path);
        }
    }

    // Merge the image paths with the validated request data
    $data = array_merge($request->validated(), ['media' => $imagePaths]);

    $user = $request->user();
    if($listing){
        if($user->abilities('admin') && $user->$id == $listing->userPlant()->user()->id){
            $listing->update($data);
            return $this->jsonResponse(201, 'Listing updated succesfully', $listing);
        } else{
            return $this->jsonResponse(403, "You don't have permission to modify this listing");
        }
    }
    return $this->jsonResponse(404, 'Listing not found');
    }

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
//TODO: implemnt error
 */

    public function delete(ListingRequest $request, $id)
    {
        // Fetch the listing with the userPlant relationship
        $listing = Listing::with('userPlant')->find($id);
        $user = $request->user();

        // If the listing doesn't exist, return a 404 response
        if (!$listing) {
            return $this->jsonResponse(404, 'Listing not found');
        }

        if ($user->tokenCan('admin') || $user->$id == $listing->userPlant()->user()->id) {
            $images = $listing->media();
            if ($images) {
                foreach ($images as $file) {
                    $filePath = storage_path('app/public/public/listings/' . $file->filename);
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file from storage
                    }
                }
            }
            $listing->delete();
            return $this->jsonResponse(201, 'Listing deleted successfully');
        }

        // If the user doesn't have permission, return a 403 response
        return $this->jsonResponse(403, "You don't have permission to modify this listing");
    }
}