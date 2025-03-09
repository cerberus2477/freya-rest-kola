<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPlantRequest;
use App\Models\UserPlant;

class UserPlantController extends BaseController
{
    //TODO: use jsonresponse instead of response(), make sure errors are catched (like not found) 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(UserPlant::with('user', 'plant')->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(UserPlant::findOrFail($id)::with('user', 'plant')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPlantRequest $request)
    {

        $userplant = UserPlant::create($request->validated());
        return response()->json($userplant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPlantRequest $request, string $id)
    {
        $userplant = UserPlant::findOrFail($id);

        $userplant->update($request->validated());
        return response()->json($userplant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userplant = UserPlant::findOrFail($id);
        $userplant->delete();

        return response()->json(null, 204); // 204 No Content response to indicate deletion
    }
}