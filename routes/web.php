<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PengaduanController;


// Route::get('/dashboard', function () {
//     return view('operator_yayasan.v_dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('operator_yayasan.v_dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('operator_yayasan.v_dashboard.index');
    })->name('dashboard');

    #profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #pengaduan
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('pengaduan/1', [PengaduanController::class, 'show'])->name('pengaduan.detail');


    #keuangan
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');

    #dokumen
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/detail_dokumen/{id}', [DokumenController::class, 'show'])->name('detail_dokumen.show');


    #data sekolah

    #data siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');


    #data guru
    Route::get('guru', [GuruController::class, 'index'])->name('guru.index');
});







require __DIR__ . '/auth.php';
