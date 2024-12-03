<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Plant::all());
    }


    /**
     * Display the specified resource.
     */

    // GET /api/plants/{id}
    public function show($id)
    {
        return response()->json(Plant::findOrFail($id));
    }








    /**
     * Store a newly created resource in storage.
     */

    // POST /api/plants - Store a new plant
    public function store(Request $request)
    {
        // KÜLÖN FÁJLBA!
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latin_name' => 'required|string|max:255',
        ]);

        $plant = Plant::create($validated);
        return response()->json($plant);
    }


    /**
     * Update the specified resource in storage.
     */

    // PUT/PATCH /api/plants/{id} - Update a plant by ID
    public function update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'latin_name' => 'required|string|max:255',
        ]);

        $plant->update($validated);
        return response()->json($plant);
    }


    /**
     * Remove the specified resource from storage.
     */

    // DELETE /api/plants/{id} - Delete a plant by ID
    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();

        return response()->json(null, 204); // 204 No Content response to indicate deletion
    }
}
