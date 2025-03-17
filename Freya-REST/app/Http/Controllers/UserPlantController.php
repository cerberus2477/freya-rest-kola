<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPlantRequest;
use App\Models\UserPlant;

class UserPlantController extends BaseController
{
    //TODO: make sure errors are catched (like not found) 

    /**
     * Display a listing of the resource.
     */
    public function index(UserPlantRequest $request)
    {
        return $this->jsonResponse(200, "Data retrived succesfully", UserPlant::with([
            'user'=> function ($query) {$query->select('username');},
            'plant'=> function ($query) {$query->select('id', 'name');},
            'stage'=> function ($query) {$query->select('name');}
            ])
            ->where('active', true)
            ->where('username', $request->user())
            ->get(['count']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->jsonResponse(200, 'User plant retrived succesfully',UserPlant::findOrFail($id)::with('user', 'plant, stage')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPlantRequest $request)
    {

        $userplant = UserPlant::create($request->validated());
        return $this->jsonResponse(200, 'User plant created succesfully', $userplant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPlantRequest $request, string $id)
    {
        $userplant = UserPlant::findOrFail($id);

        $userplant->update($request->validated());
        return $this->jsonResponse(200, 'User plant modified succesfully', $userplant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userplant = UserPlant::findOrFail($id);
        $userplant->delete();

        return $this->jsonResponse(204,'User plant deleted succesfully');
    }
}