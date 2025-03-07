<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Listing;
use App\Http\Requests\ListingRequest;

class ListingController extends BaseController
{
    private function baseQuery()
    {
        return DB::table('listings')
            ->leftJoin('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
            ->leftJoin('users', 'user_plants.user_id', '=', 'users.id')
            ->leftJoin('plants', 'user_plants.plant_id', '=', 'plants.id')
            ->leftJoin('types', 'plants.type_id', '=', 'types.id')
            ->leftJoin('stages', 'user_plants.stage_id', '=', 'stages.id')
            ->select(
                'listings.id',
                'listings.title',
                'listings.description',
                'listings.media',
                'listings.sell',
                'listings.price',
                'listings.created_at',
                'users.username as user',
                'plants.name as plant',
                'types.name as type',
                'stages.name as stage'
            );
    }

    // GET /api/articles?all
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $listings = $this->baseQuery()->get();
            return $this->jsonResponse(200, 'All listings retrieved', $listings);
        }

        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $listings = $this->baseQuery()->paginate($pageSize, ['*'], 'page', $page);

        return $this->jsonResponse(200, 'Listings retrieved successfully', $listings);
    }

    // GET /api/listings/search?q=&deep&sell=&user=&plant=&type=&stage&minprice=&maxprice=&all
    public function search(Request $request)
    {   
        $query = $this->baseQuery();

        //search by title, plant, optionally in description
        $q = $request->query('q', '');
        if (!empty($q)) {
            $query->where(function ($query) use ($q, $request) {
                $query->where('listings.title', 'LIKE', "%$q%")
                  ->orWhere('plants.name', 'LIKE', "%$q%");
                if ($request->has("deep")) {
                    $query->orWhere('listings.description', 'LIKE', "%$q%");
                }
            });
        }

        //filters
        $filters = [
            'sell' => 'listings.sell',
            'user' => 'users.username',
            'plant' => 'plants.name',
            'type' => 'types.name',
            'stage' => 'stages.name',
        ];

        foreach ($filters as $param => $column) {
            if ($value = $request->query($param)) {
                $query->where($column, '=', $value);
            }
        }

        if ($minPrice = $request->query('minprice')) {
            $query->where('listings.price', '>=', $minPrice);
        }
        if ($maxPrice = $request->query('maxprice')) {
            $query->where('listings.price', '<=', $maxPrice);
        }

        //return all matching results
        if ($request->has("all")) {
            return $this->jsonResponse(200, 'Listings retrieved successfully', $query->get());
        }

        //return a page of matching results
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $listings = $query->paginate($pageSize, ['*'], 'page', $page);
        return $this->jsonResponse(200, 'Listings retrieved successfully', $listings);
    }

    public function show($id)
    {
        $listing = $this->baseQuery()
            ->addSelect('users.email')
            ->where('listings.id', '=', $id)
            ->first();

        if (!$listing) {
            return $this->jsonResponse(404, 'Listing not found', []);
        }

        return $this->jsonResponse(200, 'Listing found', $listing);
    }

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
     * @apiBody {Boolean} sell Whether the listing is for sale.
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
     *             "sell": true,
     *             "price": 1000,
     *             "created_at": "2023-10-01T12:00:00.000000Z",
     *             "updated_at": "2023-10-01T12:00:00.000000Z"
     *         }
     *     }
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *         "message": "The given data was invalid.",
     *         "errors": {
     *             "title": ["A cím megadása kötelező."]
     *         }
     *     }
     */
    public function create(ListingRequest $request)
    {
        $listing = Listing::create($request->validated());
        return $this->jsonResponse(201, 'Hirdetés sikeresen létrehozva', $listing);
    }

    /**
     * @api {patch} /listing/{id} Update Listing
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
     * @apiBody {Boolean} [sell] Optional whether the listing is for sale.
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
     *             "sell": true,
     *             "price": 1200,
     *             "created_at": "2023-10-01T12:00:00.000000Z",
     *             "updated_at": "2023-10-01T12:30:00.000000Z"
     *         }
     *     }
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "message": "Listing not found."
     *     }
     */
    public function update(ListingRequest $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update($request->validated());
        return $this->jsonResponse(200, 'Hirdetés sikeresen módosítva', $listing);
    }

/**
     * @api {delete} /listing/{id} Delete Listing
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
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "message": "Listing not found."
     *     }
     */

    public function delete($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();
        return $this->jsonResponse(200, 'Hirdetés sikeresen törölve');
    }

}