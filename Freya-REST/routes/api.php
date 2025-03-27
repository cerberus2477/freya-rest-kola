<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\HardCodedController;

//No need for token
//fixed file paths
Route::get('/documentation', [HardCodedController::class, 'getDocumentation']);
Route::get('/placeholders', [HardCodedController::class, 'getPlaceholders']);

//login
Route::post('/login', [UserController::class,'login'])->name('login');

//register
Route::post('/register', [UserController::class, 'register'])->name('register');

//password reset
Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::post('/reset-password', [UserController::class, 'passwordReset'])->name('password-reset');

Route::resource('userplants', UserPlantController::class);

//articles
Route::get('/articles', [ArticleController::class, 'search']);
Route::get('/articles/{title}', [ArticleController::class, 'show']);

//listings
Route::get('/listings', [LIstingController::class, 'search']);
Route::get('/listings/{id}',[ListingController::class, 'show']);

//requires users abilities 
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {

    //users/profile
    Route::get('/profile', [UserController::class, 'showMyPlants']);
    Route::patch('/profile', [UserController::class, 'update'])->name('update');
    Route::get('/users/{username}', [UserController::class, 'show']);
    Route::get('/profile/plants', [UserController::class, 'showMyPlants']);
    Route::post('/profile/plants', [UserPlantController::class, 'create']);//TODO implement
    Route::patch('/profile/plants/{id}', [UserPlantController::class, 'update']);//TODO implement
    Route::delete('/profile/plants/{id}', [UserPlantController::class, 'destroy']);//TODO implement

    //listings
    Route::post('/listing', [ListingController::class, 'create']);
    Route::patch('/listings/{id}', [ListingController::class, 'update']);
    Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
});

//requires stats abilities
Route::middleware(['auth:sanctum', 'abilities:stats'])->group(function () {
    Route::get('/stats', [UserPlantController::class, 'get-stats']);//TODO to be written

    //article
    Route::post('/article', [ArticleController::class, 'create']);//TODO not ttested
    Route::patch('/article/{title}', [ArticleController::class, 'update']);//TODO not tested
    Route::delete('/article/{title}', [ArticleController::class, 'destroy']);//TODO not tested
    Route::post('/articles/upload-image', [ArticleController::class, 'uploadArticleImage'])->name('articles.upload-image');


    //dictionay tables index/show
    Route::get('/stages', [StageController::class, 'index']);
    Route::get('/stages/{id}', [StageController::class, 'show']);
    Route::get('/types', [TypeController::class, 'index']);
    Route::get('/types/{id}', [TypeController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/plants', [PlantController::class, 'index']);
    Route::get('/plants/{id}', [PlantController::class, 'show']);
});

//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    //users
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{username}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{username}', [UserController::class, 'destroy']);//TODO to be tested
    Route::patch('/users/{username}/restore', [UserController::class, 'restore']);//TODO to be tested
    Route::patch('/users/{username}/role', [UserController::class, 'roleUpdate'])->name('role-update');
    

    //categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    Route::patch('/categories/{id}/restore', [CategoryController::class, 'restore']);

    //plants
    Route::post('/plants', [PlantController::class, 'store']);
    Route::patch('/plants/{id}', [PlantController::class, 'update']);
    Route::delete('/plants/{id}', [PlantController::class, 'destroy']);
    Route::patch('/plant/{id}/restore', [PlantController::class, 'restore']);

    //types
    Route::post('/types', [TypeController::class, 'store']);
    Route::patch('/types/{id}', [TypeController::class, 'update']);
    Route::delete('/types/{id}', [TypeController::class, 'destroy']);
    Route::patch('/types/{id}/restore', [TypeController::class, 'restore']);

    //stages
    Route::post('/stages', [StageController::class, 'store']);
    Route::patch('/stages/{id}', [StageController::class, 'update']);
    Route::delete('/stages/{id}', [StageController::class, 'destroy']);
    Route::patch('/stages/{id}/restore', [StageController::class, 'restore']);
});