<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends BaseController
{
    private function baseQuery()
    {
        return DB::table('listings')
            ->join('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
            ->join('users', 'user_plants.user_id', '=', 'users.id')
            ->join('plants', 'user_plants.plant_id', '=', 'plants.id')
            ->join('types', 'plants.type_id', '=', 'types.id')
            ->join('stages', 'user_plants.stage_id', '=', 'stages.id')
            ->select(
                'listings.id',
                'listings.title',
                'listings.description',
                'listings.media',
                'listings.sell',
                'listings.price',
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
}