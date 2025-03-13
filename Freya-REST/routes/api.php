<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPlantController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

//No need for bearer token
//login
Route::post('/login', [UserController::class,'login'])->name('login');
//register
Route::post('/register', [UserController::class, 'register'])->name('register');
//password reset
Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::post('/reset-password', [UserController::class, 'passwordReset'])->name('password-reset');

Route::resource('plants', PlantController::class);
Route::resource('userplants', UserPlantController::class);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/{title}', [ArticleController::class, 'show']);



Route::resource('types', TypeController::class);
Route::resource('categories', CategoryController::class);

Route::get('/listings',[ListingController::class, 'index']);
Route::get('/listings/search', [LIstingController::class, 'search']);
Route::get('/listings/{id}',[ListingController::class, 'show']);
//requires users abilities 
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {

    Route::get('/profile', [UserController::class, 'showMyPlants']);
    Route::patch('/profile', [UserController::class, 'update'])->name('update');
    Route::get('/users/{username}', [UserController::class, 'show']);
    Route::post('/listing', [ListingController::class, 'create']);
    Route::patch('/listings/{id}', [ListingController::class, 'update']);
    Route::delete('/listings/{id}', [ListingController::class, 'delete']);
});

//requires stats abilities
Route::middleware(['auth:sanctum', 'abilities:stats'])->group(function () {
    Route::post('/article', [ArticleController::class, 'create']);//TODO not ttested
    Route::patch('/article/{title}', [ArticleController::class, 'update']);//TODO not tested
    Route::delete('/article/{title}', [ArticleController::class, 'delete']);//TODO not tested
});

//requires admin abilities
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    Route::patch('/listings/{id}/admin', [ListingController::class, 'update']);//TODO image modyfiing, can i just add images?
    Route::delete('/listings/{id}/admin', [ListingController::class, 'delete']);//TODO what happens when deleting from database with the files
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{username}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{username}', [UserController::class, 'destroy']);//TODO to be tested
    Route::patch('/users/{username}/restore', [UserController::class, 'restore']);//TODO to be tested
    Route::patch('/users/{username}/role', [UserController::class, 'roleUpdate'])->name('role-update');
});