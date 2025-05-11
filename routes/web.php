<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;




//route dengan middleware

Route::get('/', function () {
    return view('Landing_Page.index');
});

Route::get('/login', function () {
    return view('Autentikasi.login')->name('login');
});
