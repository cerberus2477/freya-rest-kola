<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;

//demonstrate how to be able to set diffrent autorizatoin levels
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/example', function (Request $request) {
        // Only users with 'admin' ability can access this route
    })->middleware('ability:admin');

    Route::get('/stats/example', function (Request $request) {
        // Only users with 'stats' ability can access this route
    })->middleware('ability:stats,user');

    Route::get('/user/example', function (Request $request) {
        // Only users with 'viewer' ability can access this route
    })->middleware('ability:user');

    //más format, lehet jogosultság alapján groupolni
    Route::middleware('ability:user')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::resource('plants', PlantController::class);
    });

});


Route::post('user/login', [UserController::class,'login']);
Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('plants', PlantController::class);
//Route::resource('users', UserController::class);
Route::resource('userplants', UserPlantController::class);