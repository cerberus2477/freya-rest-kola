<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sanctum\PersonalAccessToken;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;



//login
Route::post('user/login', [UserController::class,'login']);
//example requests
Route::resource('plants', PlantController::class);
Route::resource('userplants', UserPlantController::class);

//returns all uers
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});