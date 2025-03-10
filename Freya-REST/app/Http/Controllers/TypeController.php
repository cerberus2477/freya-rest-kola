<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->jsonResponse(200, "Types retrieved successfully", Type::all());
    }


   //TODO: copy from plantcontroller when it's is done
}