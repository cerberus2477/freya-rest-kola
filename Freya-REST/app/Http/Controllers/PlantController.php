<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\PlantRequest;

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

    //TODO apidoc comments update(formatttin function is new)
    /**
     * @api {get} /api/plants Get Plants
     * @apiName GetPlants
     * @apiGroup Plant
     * @apiDescription Retrieve a list of all plants.
     * 
     * @apiSuccess {Integer} id The ID of the plant.
     * @apiSuccess {String} name The name of the plant.
     * @apiSuccess {String} latin_name The Latin name of the plant.
     * @apiSuccess {Integer} type_id The type ID of the plant.
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
     *                 "type_id": 1
     *             },
     *             {
     *                 "id": 2,
     *                 "name": "Körte",
     *                 "latin_name": "Pyrus",
     *                 "type_id": 1
     *             },
     *             {
     *                 "id": 3,
     *                 "name": "Banán",
     *                 "latin_name": "Musa",
     *                 "type_id": 1
     *             },
     *             ...
     *         ]
     *     }
     */
    
    public function index()
    {
        $plants = Plant::with('type')->get();
    
        $formattedPlants = $this->format($plants);

        return $this->jsonResponse(200, "Plants retrieved successfully", $formattedPlants);
    }

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
     * @apiSuccess {Integer} type_id The type ID of the plant.
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
     *                 "type_id": 1
     *             }
     *         ]
     *     }
     */
    public function show($id)
    {
        $plant = Plant::findOrFail($id);
        $formattedPlant = $this->format($plant);

        return $this->jsonResponse(200, "Plant retrieved successfully", $formattedPlant);
    }
    
    /**
     * @api {post} /api/plants Create Plant
     * @apiName CreatePlant
     * @apiGroup Plant
     * @apiDescription Create a new plant.
     * 
     * @apiParam {String} name The name of the plant.
     * @apiParam {String} latin_name The Latin name of the plant.
     * @apiParam {Integer} type_id The type ID of the plant.
     * 
     * @apiSuccess {Integer} id The ID of the created plant.
     * @apiSuccess {String} name The name of the created plant.
     * @apiSuccess {String} latin_name The Latin name of the created plant.
     * @apiSuccess {Integer} type_id The type ID of the created plant.
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
     *             "type_id": 1
     *         }
     *     }
     */
    public function store(PlantRequest $request)
    {
        $plant = Plant::create($request->validated());
        $formattedPlant = $this->format($plant);

        return $this->jsonResponse(201, "Plant created successfully", $formattedPlant); 
    }

    /**
     * @api {put} /api/plants/:id Update Plant
     * @apiName UpdatePlant
     * @apiGroup Plant
     * @apiDescription Update an existing plant.
     * 
     * @apiParam {Integer} id The ID of the plant to update.
     * @apiParam {String} name The name of the plant.
     * @apiParam {String} latin_name The Latin name of the plant.
     * @apiParam {Integer} type_id The type ID of the plant.
     * 
     * @apiSuccess {Integer} id The ID of the updated plant.
     * @apiSuccess {String} name The name of the updated plant.
     * @apiSuccess {String} latin_name The Latin name of the updated plant.
     * @apiSuccess {Integer} type_id The type ID of the updated plant.
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
     *             "type_id": 1
     *         }
     *     }
     */
    public function update(PlantRequest $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($request->validated());
        $formattedPlant = $this->format($plant);

        return $this->jsonResponse(200, "Plant updated successfully", $formattedPlant);
    }

    /**
     * @api {delete} /api/plants/:id Delete Plant
     * @apiName DeletePlant
     * @apiGroup Plant
     * @apiDescription Delete a plant by its ID.
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
    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return $this->jsonResponse(200, "Plant deleted successfully");
    }

    /**
     * @api {post} /api/plants/:id/restore Restore Plant
     * @apiName RestorePlant
     * @apiGroup Plant
     * @apiDescription Restore a deleted plant by its ID.
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
    public function restore($id)
    {
       $plant = Plant::onlyTrashed()->where('id', $id)->firstOrFail();
       $plant->restore();

       return $this->jsonResponse(200, 'Plant restored successfully');
    }
}