<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    // GET /api/listings
    public function index(Request $request)
    {
        // Check if 'all' is set to true
        //GET /api/listings?all=true
        if ($request->query('all') === 'true') {
            $listings = DB::table('listings')
                ->join('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
                ->join('users', 'user_plants.user_id', '=', 'users.id')
                ->join('plants', 'user_plants.plant_id', '=', 'plants.id')
                ->join('types', 'plants.type_id', '=', 'types.id')
                ->join('stages', 'user_plants.stage_id', '=', 'stages.id')
                ->select(
                    'listings.id',
                    'listings.title',
                    'listings.media',
                    'listings.sell as price',
                    'stages.name as stage',
                    'plants.name as plant_name',
                    'types.name as plant_type'
                )
                ->get(); // Get all results without pagination

            return response()->json(['data' => $listings], 200);
        }

        // Query builder with necessary joins
        $query = DB::table('listings')
            ->join('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
            ->join('users', 'user_plants.user_id', '=', 'users.id')
            ->join('plants', 'user_plants.plant_id', '=', 'plants.id')
            ->join('types', 'plants.type_id', '=', 'types.id')
            ->join('stages', 'user_plants.stage_id', '=', 'stages.id')
            ->select(
                'listings.id',
                'listings.title',
                'listings.media',
                'listings.sell',
                'listings.price',
                'users.username as user',
                'plants.name as plant',
                'types.name as type',
                'stages.name as stage'
            );

        // Pagination and search parameters
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);

        //approximate search
        $search = $request->query('search', '');
        //whether to search in description too
        $indesc = $request->query('indesc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search, $indesc) {
                $q->where('listings.title', 'LIKE', "%$search%")
                  ->orWhere('plants.name', 'LIKE', "%$search%");
                
                if ($indesc === 'true') {
                    $q->orWhere('listings.description', 'LIKE', "%$search%");
                }
            });
        }



        //filters
        $sell = $request->query('sell');

        $user = $request->query('user');
        $type = $request->query('type');
        $stage = $request->query('stage');
        $plant = $request->query('plant');
        
        // $price
        $minprice = $request->query('minprice');
        $maxprice = $request->query('maxprice');
        
        
        if (!empty($sell)) {
            $query->where('listings.sell', '=', $sell);
        }


        if (!empty($user)) {
            $query->where('users.username', '=', $user);
        }

        if (!empty($plant)) {
            $query->where('plants.name', '=', $plant);
        }

        if (!empty($type)) {
            $query->where('types.name', '=', $type);
        }

        if (!empty($stage)) {
            $query->where('stages.name', '=', $stage);
        }


        // Price range filter
        if (!empty($minprice)) {
            $query->where('listings.price', '>=', $minprice);
        }
        
        if (!empty($maxprice)) {
            $query->where('listings.price', '<=', $maxprice);
        }


        // Paginate results
        $listings = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json([
            'status' => 200,
            'data' => $listings->items(),
            'pagination' => [
                'total' => $listings->total(),
                'page' => $listings->currentPage(),
                'pageSize' => $listings->perPage(),
                'totalPages' => $listings->lastPage(),
            ],200
        ]);
    }

    // GET /api/listings/{id}
    public function show($id)
    {
        $listing = DB::table('listings')
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
                'users.email',
                'stages.name as stage',
                'plants.name as plant',
                'types.name as type'
            )
            ->where('listings.id', '=', $id)
            ->first();
    
        if (!$listing) {
            return response()->json([
                'status' => 404,
                'message' => 'Listing not found',
                'data' => []
            ], 404);
        }
    
        return response()->json([
            'status' => 200,
            'message' => 'Listing found',
            'data' => $listing
        ], 200);
    }
    
}