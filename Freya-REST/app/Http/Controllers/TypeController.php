<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Http\Requests\TypeRequest;

class TypeController extends BaseController
{
    /**
     * @api {get} /api/types Get Types
     * @apiName GetTypes
     * @apiGroup Type
     * @apiDescription Retrieve a list of all types.
     * 
     * @apiSuccess {Integer} id The ID of the type.
     * @apiSuccess {String} name The name of the type.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Types retrieved successfully",
     *         "data": [
     *             {
     *                 "id": 1,
     *                 "name": "Fruit"
     *             },
     *             {
     *                 "id": 2,
     *                 "name": "Vegetable"
     *             },
     *             ...
     *         ]
     *     }
     */
    public function index()
    {
        return $this->jsonResponse(200, "Types retrieved successfully", Type::all());
    }

    /**
     * @api {get} /api/types/:id Get Type by ID
     * @apiName GetTypeById
     * @apiGroup Type
     * @apiDescription Retrieve a type by its ID.
     * 
     * @apiParam {Integer} id The ID of the type to retrieve.
     * 
     * @apiSuccess {Integer} id The ID of the type.
     * @apiSuccess {String} name The name of the type.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Type retrieved successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */
    public function show($id)
    {
        $type = Type::findOrFail($id);
        return $this->jsonResponse(200, "Type retrieved successfully", $type);
    }

    /**
     * @api {post} /api/types Create Type
     * @apiName CreateType
     * @apiGroup Type
     * @apiDescription Create a new type.
     * 
     * @apiParam {String} name The name of the type.
     * 
     * @apiSuccess {Integer} id The ID of the created type.
     * @apiSuccess {String} name The name of the created type.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "status": 201,
     *         "message": "Type created successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */
    public function store(TypeRequest $request)
    {
        $type = Type::create($request->validated());
        return $this->jsonResponse(201, "Type created successfully", $type);
    }

    /**
     * @api {put} /api/types/:id Update Type
     * @apiName UpdateType
     * @apiGroup Type
     * @apiDescription Update an existing type.
     * 
     * @apiParam {Integer} id The ID of the type to update.
     * @apiParam {String} name The name of the type.
     * 
     * @apiSuccess {Integer} id The ID of the updated type.
     * @apiSuccess {String} name The name of the updated type.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Type updated successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */
    public function update(TypeRequest $request, $id)
    {
        $type = Type::findOrFail($id);
        $type->update($request->validated());
        return $this->jsonResponse(200, "Type updated successfully", $type);
    }

    /**
     * @api {delete} /api/types/:id Delete Type
     * @apiName DeleteType
     * @apiGroup Type
     * @apiDescription Delete a type by its ID.
     * 
     * @apiParam {Integer} id The ID of the type to delete.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Type deleted successfully"
     *     }
     */
    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return $this->jsonResponse(200, "Type deleted successfully");
    }

    /**
     * @api {post} /api/types/:id/restore Restore Type
     * @apiName RestoreType
     * @apiGroup Type
     * @apiDescription Restore a deleted type by its ID.
     * 
     * @apiParam {Integer} id The ID of the type to restore.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Type restored successfully"
     *     }
     */
    public function restore($id)
    {
        $type = Type::onlyTrashed()->where('id', $id)->firstOrFail();
        $type->restore();
        return $this->jsonResponse(200, 'Type restored successfully');
    }
}