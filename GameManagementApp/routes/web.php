<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerGameController;

Route::get('/', function () {
    return view('home');
});


Route::resource('players', controller: PlayerController::class);
Route::resource('games', GameController::class);
Route::resource('playergames', PlayerGameController::class);