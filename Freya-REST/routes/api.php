<?php

use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sanctum\PersonalAccessToken;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;
use App\Http\Middleware\Abilities;

//login
Route::post('user/login', [UserController::class,'login']);

Route::resource('plants', PlantController::class);
Route::resource('userplants', UserPlantController::class);


Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/show/{title}', [ArticleController::class, 'show']);

//requires users abilities
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('/listings',[ListingController::class, 'index']);
    Route::get('/listings/{id}',[ListingController::class, 'show']);
});

//requires stats abilities
Route::middleware(['auth:sanctum', 'abilties:stats'])->group(function () {

});

//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    Route::get('/users', [UserController::class, 'index']);
});