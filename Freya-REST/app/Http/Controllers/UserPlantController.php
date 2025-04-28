<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPlantRequest;
use App\Models\UserPlant;

class UserPlantController extends BaseController
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->jsonResponse(200, 'Userplant retrived succesfully',UserPlant::findOrFail($id)::with('user', 'plant', 'stage')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPlantRequest $request)
{
    $validated = $request->validated();
    $validated['user_id'] = $request->user()->id; // Set the authenticated user's ID
    
    $userplant = UserPlant::create($validated);
    
    return $this->jsonResponse(200, 'Userplant created successfully', $userplant);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPlantRequest $request, string $id)
    {
        $userplant = UserPlant::findOrFail($id);

        $userplant->update($request->validated());
        return $this->jsonResponse(200, 'Userplant modified succesfully', $userplant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userplant = UserPlant::findOrFail($id);
        $userplant->delete();

        return $this->jsonResponse(204,'Userplant deleted succesfully');
    }
}

//apidoc

/**
 * @api {get} /profile/plants/:id Get UserPlant by ID
 * @apiName GetUserPlantById
 * @apiGroup UserPlant
 * @apiDescription Retrieve a specific UserPlant by its ID.
 * 
 * @apiParam {Integer} id The ID of the UserPlant to retrieve.
 * 
 * @apiSuccess {Integer} id The ID of the UserPlant.
 * @apiSuccess {Integer} user_id The ID of the user.
 * @apiSuccess {Integer} plant_id The ID of the plant.
 * @apiSuccess {Integer} stage_id The ID of the stage.
 * @apiSuccess {Integer} count The count of the UserPlant.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Userplant retrieved successfully",
 *         "data": {
 *             "id": 1,
 *             "user_id": 1,
 *             "plant_id": 2,
 *             "stage_id": 3,
 *             "count": 5
 *         }
 *     }
 * 
 * @api {post} /profile/plants Create UserPlant
 * @apiName CreateUserPlant
 * @apiGroup UserPlant
 * @apiDescription Create a new UserPlant.
 * 
 * @apiParam {Integer} plant_id The ID of the plant.
 * @apiParam {Integer} stage_id The ID of the stage.
 * @apiParam {Integer} [count] The count of the UserPlant (optional).
 * 
 * @apiSuccess {Integer} id The ID of the created UserPlant.
 * @apiSuccess {Integer} user_id The ID of the user.
 * @apiSuccess {Integer} plant_id The ID of the plant.
 * @apiSuccess {Integer} stage_id The ID of the stage.
 * @apiSuccess {Integer} count The count of the UserPlant.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Userplant created successfully",
 *         "data": {
 *             "id": 1,
 *             "user_id": 1,
 *             "plant_id": 2,
 *             "stage_id": 3,
 *             "count": 5
 *         }
 *     }
 * 
 * @api {patch} /profile/plants/:id Update UserPlant
 * @apiName UpdateUserPlant
 * @apiGroup UserPlant
 * @apiDescription Update an existing UserPlant.
 * 
 * @apiParam {Integer} id The ID of the UserPlant to update.
 * @apiParam {Integer} [plant_id] The ID of the plant (optional).
 * @apiParam {Integer} [stage_id] The ID of the stage (optional).
 * @apiParam {Integer} [count] The count of the UserPlant (optional).
 * 
 * @apiSuccess {Integer} id The ID of the updated UserPlant.
 * @apiSuccess {Integer} user_id The ID of the user.
 * @apiSuccess {Integer} plant_id The ID of the plant.
 * @apiSuccess {Integer} stage_id The ID of the stage.
 * @apiSuccess {Integer} count The count of the UserPlant.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Userplant modified successfully",
 *         "data": {
 *             "id": 1,
 *             "user_id": 1,
 *             "plant_id": 2,
 *             "stage_id": 3,
 *             "count": 10
 *         }
 *     }
 * 
 * @api {delete} /profile/plants/:id Delete UserPlant
 * @apiName DeleteUserPlant
 * @apiGroup UserPlant
 * @apiDescription Delete a UserPlant by its ID.
 * 
 * @apiParam {Integer} id The ID of the UserPlant to delete.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 204 No Content
 *     {
 *         "status": 204,
 *         "message": "Userplant deleted successfully"
 *     }
 */