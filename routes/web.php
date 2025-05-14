<?php

use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



// Route::get('/dashboard', function () {
//     return view('operator_yayasan.v_dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {


// });

Route::get('/', function () {
    return view('operator_yayasan.v_dashboard.index');
});

#profile
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

#pengaduan
Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');

// require __DIR__ . '/auth.php';
