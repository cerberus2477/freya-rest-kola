<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerGameController;

Route::get('/', function () {
    return view('home');
})->name('home');