<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->jsonResponse(200, "Categories retrieved successfully", Category::all());
    }

      //TODO: copy from plantcontroller when it's is done
}

// GET /resource (index)
// POST /resource (store)
// GET /resource/{id} (show)
// PATCH /resource/{id} (update)
// DELETE /resource/{id} (destroy)