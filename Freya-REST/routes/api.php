<?php

use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sanctum\PersonalAccessToken;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;
use App\Http\Middleware\Abilities;
use App\Http\Controllers\ArticleController;

//login
Route::post('/login', [UserController::class,'login']);

//register
Route::post('/register', [UserController::class, 'register']);


Route::resource('plants', PlantController::class);
Route::resource('userplants', UserPlantController::class);


Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/{title}', [ArticleController::class, 'show']);



//requires users abilities 
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('/listings',[ListingController::class, 'index']);
    Route::get('/listings/{id}',[ListingController::class, 'show']);
});

//Tesztelés miatt vannak kikkommentelve, hogy ne kelljen hozzá token ideiglenesen
//requires stats abilities
Route::middleware(['auth:sanctum', 'abilities:stats'])->group(function () {
    
});

//Tesztelés miatt vannak kikkommentelve, hogy ne kelljen hozzá token ideiglenesen
//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    Route::get('/users', [UserController::class, 'index']);
});