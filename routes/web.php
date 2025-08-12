<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupsController;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailExemplo;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/confirm',[SignupsController::class,'confirm']);

Route::post('/confirm-code',[SignupsController::class,'confirmCode']);

Route::post('/confirm-code-error',[SignupsController::class,'confirmCodeError']);
