<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;




//route dengan middleware
Route::middleware('custom.auth')->group(function () {
    Route::get('/', function () {
        return view('landingPage');
    });
});
