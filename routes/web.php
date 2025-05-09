<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;




//route dengan middleware

Route::get('/', function () {
        return view('Autentikasi.login');
    });

