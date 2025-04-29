<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\PlantRequest;
use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;

class PlantController extends BaseController
{
    protected function format($plants){
        return collect($plants)->map(function ($plant) {
            return [
                'id' => $plant->id,
                'name' => $plant->name,
                'latin_name' => $plant->latin_name,
                'type'=>[
                    'id' => $plant->type->id,
                    'name' => $plant->type->name,
                ]];
        });
    }
    
    public function index(Request $request)
    {
        $cacheKey = 'plants_all_' . md5($request->fullUrl());
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $plants = Plant::with('type')->get();
        $response = $this->jsonResponse(200, "Plants retrieved successfully",  $this->format($plants));
        Cache::put($cacheKey, $response);
        return $response;
    }


    public function show($id)
    {
        $plant = Plant::findOrFail($id);
        $formattedPlant = $this->format($plant);

        return $this->jsonResponse(200, "Plant retrieved successfully", $formattedPlant);
    }
    
    public function store(PlantRequest $request)
    {
        $plant = Plant::create($request->validated());
        $formattedPlant = $this->format($plant);
        Cache::forget('plants_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(201, "Plant created successfully", $formattedPlant); 
    }

    public function update(PlantRequest $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($request->validated());
        $formattedPlant = $this->format($plant);
        Cache::forget('plants_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(200, "Plant updated successfully", $formattedPlant);
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        Cache::forget('plants_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(200, "Plant deleted successfully");
    }

    public function restore($id)
    {
       $plant = Plant::onlyTrashed()->where('id', $id)->firstOrFail();
       $plant->restore();
       Cache::forget('plants_all_' . md5(request()->fullUrl()));
       return $this->jsonResponse(200, 'Plant restored successfully');
    }
}

//apidoc


 /**
 * @api {get} /api/plants Get Plants
 * @apiName GetPlants
 * @apiGroup Plant
 * @apiDescription Retrieve a list of all plants.
 * The response is cached for improved performance. Cache is invalidated when a plant is created, updated, deleted, or restored.
 * 
 * @apiSuccess {Integer} id The ID of the plant.
 * @apiSuccess {String} name The name of the plant.
 * @apiSuccess {String} latin_name The Latin name of the plant.
 * @apiSuccess {Object} type The type of the plant.
 * @apiSuccess {Integer} type.id The ID of the plant type.
 * @apiSuccess {String} type.name The name of the plant type.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Plants retrieved successfully",
 *         "data": [
 *             {
 *                 "id": 1,
 *                 "name": "Alma",
 *                 "latin_name": "Malus",
 *                 "type": {
 *                     "id": 1,
 *                     "name": "Fruit"
 *                 }
 *             }
 *         ]
 *     }
 */

/**
 * @api {get} /api/plants/:id Get Plant by ID
 * @apiName GetPlantById
 * @apiGroup Plant
 * @apiDescription Retrieve a plant by its ID.
 * 
 * @apiParam {Integer} id The ID of the plant to retrieve.
 * 
 * @apiSuccess {Integer} id The ID of the plant.
 * @apiSuccess {String} name The name of the plant.
 * @apiSuccess {String} latin_name The Latin name of the plant.
 * @apiSuccess {Object} type The type of the plant.
 * @apiSuccess {Integer} type.id The ID of the plant type.
 * @apiSuccess {String} type.name The name of the plant type.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Plant retrieved successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Alma",
 *             "latin_name": "Malus",
 *             "type": {
 *                 "id": 1,
 *                 "name": "Fruit"
 *             }
 *         }
 *     }
 */

/**
 * @api {post} /api/plants Create Plant
 * @apiName CreatePlant
 * @apiGroup Plant
 * @apiDescription Create a new plant.
 * This operation invalidates the cached list of plants.
 * 
 * @apiParam {String} name The name of the plant.
 * @apiParam {String} latin_name The Latin name of the plant.
 * @apiParam {Integer} type_id The type ID of the plant.
 * 
 * @apiSuccess {Integer} id The ID of the created plant.
 * @apiSuccess {String} name The name of the created plant.
 * @apiSuccess {String} latin_name The Latin name of the created plant.
 * @apiSuccess {Object} type The type of the created plant.
 * @apiSuccess {Integer} type.id The ID of the plant type.
 * @apiSuccess {String} type.name The name of the plant type.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 201 Created
 *     {
 *         "status": 201,
 *         "message": "Plant created successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Alma",
 *             "latin_name": "Malus",
 *             "type": {
 *                 "id": 1,
 *                 "name": "Fruit"
 *             }
 *         }
 *     }
 */

/**
 * @api {put} /api/plants/:id Update Plant
 * @apiName UpdatePlant
 * @apiGroup Plant
 * @apiDescription Update an existing plant.
 * This operation invalidates the cached list of plants.
 * 
 * @apiParam {Integer} id The ID of the plant to update.
 * @apiParam {String} name The name of the plant.
 * @apiParam {String} latin_name The Latin name of the plant.
 * @apiParam {Integer} type_id The type ID of the plant.
 * 
 * @apiSuccess {Integer} id The ID of the updated plant.
 * @apiSuccess {String} name The name of the updated plant.
 * @apiSuccess {String} latin_name The Latin name of the updated plant.
 * @apiSuccess {Object} type The type of the updated plant.
 * @apiSuccess {Integer} type.id The ID of the plant type.
 * @apiSuccess {String} type.name The name of the plant type.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Plant updated successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Alma",
 *             "latin_name": "Malus",
 *             "type": {
 *                 "id": 1,
 *                 "name": "Fruit"
 *             }
 *         }
 *     }
 */

/**
 * @api {delete} /api/plants/:id Delete Plant
 * @apiName DeletePlant
 * @apiGroup Plant
 * @apiDescription Delete a plant by its ID.
 * This operation invalidates the cached list of plants.
 * 
 * @apiParam {Integer} id The ID of the plant to delete.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Plant deleted successfully"
 *     }
 */

/**
 * @api {post} /api/plants/:id/restore Restore Plant
 * @apiName RestorePlant
 * @apiGroup Plant
 * @apiDescription Restore a deleted plant by its ID.
 * This operation invalidates the cached list of plants.
 * 
 * @apiParam {Integer} id The ID of the plant to restore.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Plant restored successfully"
 *     }
 */