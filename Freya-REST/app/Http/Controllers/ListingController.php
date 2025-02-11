<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;

class ListingController extends Controller
{
    // GET /api/listings
    public function index(Request $request)
    {
        // Pagination and page size
        $pageSize = $request->query('pageSize', 24); // Default page size is 24
        $page = $request->query('page', 1); // Default page is 1

        // Query the listings with pagination
        $listings = Listing::select('user_id', 'id', 'title', 'description', 'plant_name', 'media', 'sell')
            ->with('UserPlant')
            ->
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
        // Find the listing by ID
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        // Return all attributes of the listing
        return response()->json([
            'data' => $listing,
        ]);
    }
}
