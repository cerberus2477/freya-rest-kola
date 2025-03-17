<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\PlantRequest;

class PlantController extends BaseController
{
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
        return $this->jsonResponse(200, "Plants retrieved successfully", Plant::all());
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
     *
     * @apiError {Object} message Error message if plant not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "status": 404,
     *         "message": "Plant not found",
     *         "data": []
     *     }
     */
    public function show($id)
    {
        try {
            $plant = Plant::findOrFail($id);
            return $this->jsonResponse(200, "Plant retrieved successfully", $plant);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->jsonResponse(404, "Plant not found");
        }
    }
    
//TODO: write apidoc comments
    public function store(PlantRequest $request)
    {
        $plant = Plant::create($request->validated());
        return $this->jsonResponse(201, "Plant created successfully", $plant); 
    }


    public function update(PlantRequest $request, $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($request->validated());
        return $this->jsonResponse(200, "Plant updated successfully", $plant);
    }


    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return $this->jsonResponse(204, "Plant deleted successfully");  // 204 for successful deletion with no content
    }

    //admins could do this
    //untested
    public function restore($id)
    {
       $plant = Plant::onlyTrashed()->where('id', $id)->firstOrFail();

       // Restore the user
       $plant->restore();

       return $this->jsonResponse(200, 'Plant restored succesfully');
    }
}