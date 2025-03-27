<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends BaseController
{
    /**
     * @api {get} /api/categories Get Categories
     * @apiName GetCategories
     * @apiGroup Category
     * @apiDescription Retrieve a list of all categories.
     * 
     * @apiSuccess {Integer} id The ID of the category.
     * @apiSuccess {String} name The name of the category.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Categories retrieved successfully",
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
        return $this->jsonResponse(200, "Categories retrieved successfully", Category::all());
    }

    /**
     * @api {get} /api/categories/:id Get Category by ID
     * @apiName GetCategoryById
     * @apiGroup Category
     * @apiDescription Retrieve a category by its ID.
     * 
     * @apiParam {Integer} id The ID of the category to retrieve.
     * 
     * @apiSuccess {Integer} id The ID of the category.
     * @apiSuccess {String} name The name of the category.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Category retrieved successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */

     
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $this->jsonResponse(200, "Category retrieved successfully", $category);
    }

    /**
     * @api {post} /api/categories Create Category
     * @apiName CreateCategory
     * @apiGroup Category
     * @apiDescription Create a new category.
     * 
     * @apiParam {String} name The name of the category.
     * 
     * @apiSuccess {Integer} id The ID of the created category.
     * @apiSuccess {String} name The name of the created category.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "status": 201,
     *         "message": "Category created successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->jsonResponse(201, "Category created successfully", $category);
    }

    /**
     * @api {put} /api/categories/:id Update Category
     * @apiName UpdateCategory
     * @apiGroup Category
     * @apiDescription Update an existing category.
     * 
     * @apiParam {Integer} id The ID of the category to update.
     * @apiParam {String} name The name of the category.
     * 
     * @apiSuccess {Integer} id The ID of the updated category.
     * @apiSuccess {String} name The name of the updated category.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Category updated successfully",
     *         "data": {
     *             "id": 1,
     *             "name": "Fruit"
     *         }
     *     }
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return $this->jsonResponse(200, "Category updated successfully", $category);
    }

    /**
     * @api {delete} /api/categories/:id Delete Category
     * @apiName DeleteCategory
     * @apiGroup Category
     * @apiDescription Delete a category by its ID.
     * 
     * @apiParam {Integer} id The ID of the category to delete.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Category deleted successfully"
     *     }
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->jsonResponse(200, "Category deleted successfully");
    }

    /**
     * @api {post} /api/categories/:id/restore Restore Category
     * @apiName RestoreCategory
     * @apiGroup Category
     * @apiDescription Restore a deleted category by its ID.
     * 
     * @apiParam {Integer} id The ID of the category to restore.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": 200,
     *         "message": "Category restored successfully"
     *     }
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();
        $category->restore();
        return $this->jsonResponse(200, 'Category restored successfully');
    }
}