<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Http\Requests\StageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StageController extends BaseController
{
    public function index(Request $request)
    {
        $cacheKey = 'stages_all_' . md5($request->fullUrl());
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
        $response = Stage::all();
        Cache::put($cacheKey, $response);
        return $this->jsonResponse(200, "Stages retrieved successfully", $response);
    }

    public function show($id)
    {
        $stage = Stage::findOrFail($id);
        return $this->jsonResponse(200, "Stage retrieved successfully", $stage);
    }

    public function store(StageRequest $request)
    {
        $stage = Stage::create($request->validated());
        Cache::forget('stages_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(201, "Stage created successfully", $stage);
    }

    public function update(StageRequest $request, $id)
    {
        $stage = Stage::findOrFail($id);
        $stage->update($request->validated());
        Cache::forget('stages_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(200, "Stage updated successfully", $stage);
    }

    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $stage->delete();
        Cache::forget('stages_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(200, "Stage deleted successfully");
    }

    public function restore($id)
    {
        $stage = Stage::onlyTrashed()->where('id', $id)->firstOrFail();
        $stage->restore();
        Cache::forget('stages_all_' . md5(request()->fullUrl()));
        return $this->jsonResponse(200, 'Stage restored successfully');
    }
}

//apidoc

/**
 * @api {get} /api/stages Get Stages
 * @apiName GetStages
 * @apiGroup Stage
 * @apiDescription Retrieve a list of all stages.
 * This response is cached for improved performance. Cache is invalidated when a stage is created, updated, deleted, or restored.
 * 
 * @apiSuccess {Integer} id The ID of the stage.
 * @apiSuccess {String} name The name of the stage.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Stages retrieved successfully",
 *         "data": [
 *             {
 *                 "id": 1,
 *                 "name": "Seedling"
 *             },
 *             {
 *                 "id": 2,
 *                 "name": "Vegetative"
 *             }
 *         ]
 *     }
 */

/**
 * @api {get} /api/stages/:id Get Stage by ID
 * @apiName GetStageById
 * @apiGroup Stage
 * @apiDescription Retrieve a stage by its ID.
 * 
 * @apiParam {Integer} id The ID of the stage to retrieve.
 * 
 * @apiSuccess {Integer} id The ID of the stage.
 * @apiSuccess {String} name The name of the stage.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Stage retrieved successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Seedling"
 *         }
 *     }
 */

/**
 * @api {post} /api/stages Create Stage
 * @apiName CreateStage
 * @apiGroup Stage
 * @apiDescription Create a new stage.
 * This operation invalidates the cache for stages.
 * 
 * @apiParam {String} name The name of the stage.
 * 
 * @apiSuccess {Integer} id The ID of the created stage.
 * @apiSuccess {String} name The name of the created stage.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 201 Created
 *     {
 *         "status": 201,
 *         "message": "Stage created successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Seedling"
 *         }
 *     }
 */

/**
 * @api {put} /api/stages/:id Update Stage
 * @apiName UpdateStage
 * @apiGroup Stage
 * @apiDescription Update an existing stage.
 * This operation invalidates the cache for stages.
 * 
 * @apiParam {Integer} id The ID of the stage to update.
 * @apiParam {String} name The name of the stage.
 * 
 * @apiSuccess {Integer} id The ID of the updated stage.
 * @apiSuccess {String} name The name of the updated stage.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Stage updated successfully",
 *         "data": {
 *             "id": 1,
 *             "name": "Seedling"
 *         }
 *     }
 */

/**
 * @api {delete} /api/stages/:id Delete Stage
 * @apiName DeleteStage
 * @apiGroup Stage
 * @apiDescription Delete a stage by its ID.
 * This operation invalidates the cache for stages.
 * 
 * @apiParam {Integer} id The ID of the stage to delete.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Stage deleted successfully"
 *     }
 */

/**
 * @api {post} /api/stages/:id/restore Restore Stage
 * @apiName RestoreStage
 * @apiGroup Stage
 * @apiDescription Restore a deleted stage by its ID.
 * This operation invalidates the cache for stages.
 * 
 * @apiParam {Integer} id The ID of the stage to restore.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "status": 200,
 *         "message": "Stage restored successfully"
 *     }
 */