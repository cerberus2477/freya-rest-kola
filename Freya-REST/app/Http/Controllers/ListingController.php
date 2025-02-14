<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    // GET /api/listings
    public function index(Request $request)
    {
        // Pagination and page size
        $pageSize = $request->query('pageSize', 5); // Default page size is 24
        $page = $request->query('page', 1); // Default page is 1
    
        // Query the listings with joins and pagination
        $listings = DB::table('listings')
            ->join('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
            ->join('users', 'user_plants.user_id', '=', 'users.id')
            ->join('plants', 'user_plants.plant_id', '=', 'plants.id')
            ->select(
                'listings.id',
                'listings.title',
                'listings.media',
                'listings.sell',
                'users.username as user_name',
                'plants.name as plant_name'
            )
            ->paginate($pageSize, ['*'], 'page', $page);
    
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
        if (Listing::find($id)) {
            return response()->json([
                'data'=>'',
                'message' => 'Listing not found'],
                404);
        }

        $listing = DB::table('listings')
            ->join('user_plants', 'listings.user_plants_id', '=', 'user_plants.id')
            ->join('users', 'user_plants.user_id', '=', 'users.id')
            ->join('plants', 'user_plants.plant_id', '=', 'plants.id')
            ->select(
                'listings.id',
                'listings.title',
                'listings.description',
                'listings.media',
                'listings.sell',
                'users.username as user_name',
                'users.email as email',
                'users.te',
                'plants.name as plant_name'
            )
            ->where('listings.id=',$id);
    
        return response()->json([
            'data' => $listing->items(),
            ]);
    }
}
