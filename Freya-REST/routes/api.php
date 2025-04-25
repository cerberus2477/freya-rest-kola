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
//get fixed file paths from storage
Route::get('/documentation', [HardCodedController::class, 'getDocumentation']);
Route::get('/images/{folder}', [HardCodedController::class, 'getPlaceholders'])->where('folder', 'placeholders|profilePictures|notFoundImage');

//login
Route::post('/login', [UserController::class,'login'])->name('login');

//register
Route::post('/register', [UserController::class, 'register'])->name('register');

//password reset
Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::post('/reset-password', [UserController::class, 'passwordReset'])->name('password-reset');

//articles
Route::get('/articles', [ArticleController::class, 'search']);
Route::get('/articles/{title}', [ArticleController::class, 'show']);

//listings
Route::get('/listings', [LIstingController::class, 'search']);
Route::get('/listings/{id}',[ListingController::class, 'show']);

//filter dropdowns WEB
Route::get('/types', [TypeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/stages', [StageController::class, 'index']);
Route::get('/plants', [PlantController::class, 'index']);


//requires users abilities 
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {

    //other users
    Route::get('/users/{username}', [UserController::class, 'show']);

    //profile
    Route::get('/profile', [UserController::class, 'showMyPlants']);
    Route::delete('/profile', [UserController::class, 'destroy']);
    Route::patch('/profile', [UserController::class, 'update'])->name('update');

    //userplants
    Route::post('/profile/plants', [UserPlantController::class, 'store']);
    //Own resource
    Route::middleware(['ownerOrAdmin:user-plant'])->group(function(){
        Route::patch('/profile/plants/{id}', [UserPlantController::class, 'update']);
        Route::delete('/profile/plants/{id}', [UserPlantController::class, 'destroy']);
    });

    //listings
    Route::post('/listing', [ListingController::class, 'create']);
    Route::patch('/listings/{id}', [ListingController::class, 'update']);
    Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
    //Own resource
    Route::middleware(['ownerOrAdmin:listing'])->group(function(){
        Route::patch('/listings/{id}', [ListingController::class, 'update']);
        Route::delete('/listings/{id}', [ListingController::class, 'destroy'])->name('listings.destroy');
    });
});

//requires stats abilities
Route::middleware(['auth:sanctum', 'abilities:stats'])->group(function () {
    //article
    Route::post('/article', [ArticleController::class, 'create']);
    Route::post('/articles/upload-image', [ArticleController::class, 'uploadArticleImage'])->name('articles.upload-image');

    //Own resource
    Route::middleware(['ownerOrAdmin:article'])->group(function(){
        Route::patch('/article/{title}', [ArticleController::class, 'update']);//should do thing with te pictures no longer used but it will not hapen
        Route::delete('/article/{title}', [ArticleController::class, 'destroy']);//should do thing with te pictures no longer used but it will not hapen
    });

    //dictionay tables index/show
    Route::get('/stages/{id}', [StageController::class, 'show']);
    Route::get('/types/{id}', [TypeController::class, 'show']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/plants/{id}', [PlantController::class, 'show']);
});

//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    //users
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{username}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{username}', [UserController::class, 'destroy']);
    Route::patch('/users/{username}/restore', [UserController::class, 'restore']);
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