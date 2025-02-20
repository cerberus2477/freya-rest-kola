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

            return response()->json(['data' => $listings]);
        }
        // Pagination and search parameters
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $plantType = $request->query('plantType');
        $stage = $request->query('stage');

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
                'users.username as author',
                'plants.name as plant_name',
                'types.name as plant_type',
                'stages.name as stage'
            );

        if (!empty($sell)) {
            $query->where('listings.sell', '=', $sell);
        }
            
        // Apply search filter
        if (!empty($search)) {
            $query->where('listings.title', 'LIKE', "%$search%");
        }

        // Apply plant type filter
        if (!empty($plantType)) {
            $query->where('types.name', '=', $plantType);
        }

        // Apply state filter
        if (!empty($stage)) {
            $query->where('stages.name', '=', $stage);
        }

        // Paginate results
        $listings = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json([
            'data' => $listings->items(),
            'pagination' => [
                'total' => $listings->total(),
                'page' => $listings->currentPage(),
                'pageSize' => $listings->perPage(),
                'totalPages' => $listings->lastPage(),
            ],
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
            ->select(
                'listings.id',
                'listings.title',
                'listings.description',
                'listings.media',
                'listings.sell',
                'listings.price',
                'users.username as user_name',
                'users.email',
                'stages.name as stage',
                'plants.name as plant_name',
                'types.name as plant_type'
            )
            ->where('listings.id', '=', $id)
            ->first();

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        return response()->json(['data' => $listing]);
    }
}