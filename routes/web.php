<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupsController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/confirm',[SignupsController::class,'confirm']);