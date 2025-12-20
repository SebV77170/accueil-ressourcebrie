<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/sites', [SiteController::class, 'index']);