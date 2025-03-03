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
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeController;
//No need for bearer token
//login
Route::post('/login', [UserController::class,'login'])->name('login');
//register
Route::post('/register', [UserController::class, 'register'])->name('register');
//password reset
//TODO

Route::resource('plants', PlantController::class);
Route::resource('userplants', UserPlantController::class);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/{title}', [ArticleController::class, 'show']);

Route::get('/listings',[ListingController::class, 'index']);
Route::get('/listings/search', [LIstingController::class, 'search']);
Route::get('/listings/{id}',[ListingController::class, 'show']);

Route::resource('types', TypeController::class);
Route::resource('categories', CategoryController::class);


//requires users abilities 
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('/profile', [UserController::class, 'showMyPlants']);
    Route::patch('/profile', [UserController::class, 'update']);
    Route::get('/users/{username}', [UserController::class, 'show']);
});

//requires stats abilities
Route::middleware(['auth:sanctum', 'abilities:stats'])->group(function () {
    
});

//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{username}', [UserController::class, 'update']); //TODO finish
    Route::patch('/users/{username}/role', [UserController::class, 'role'])->name('role-update');//TODO finish
});