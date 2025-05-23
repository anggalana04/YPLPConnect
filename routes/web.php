<?php

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\Auth\RegisteredUserController;


// Route::get('/dashboard', function () {
//     return view('operator_yayasan.v_dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('operator_yayasan.v_dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', function () {
        return view('Landing_Page.index');
    })->name('landing-page');
    
Route::middleware('auth')->group(function () {
    
    
    
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'operator_sekolah') {
            $jumlahSiswa = Siswa::where('npsn', Auth::user()->npsn)->count();
            $jumlahGuru = Guru::where('npsn', Auth::user()->npsn)->count(); // Only count guru for this sekolah
            return view('operator_sekolah.v_dashboard.index', compact('jumlahSiswa', 'jumlahGuru'));
        } else {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count(); // All guru
            return view('operator_yayasan.v_dashboard.index', compact('jumlahSiswa', 'jumlahGuru'));
        }
    })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

    #profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #pengaduan
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/detail/{id}', [PengaduanController::class, 'show'])->name('pengaduan.detail');
    Route::post('/pengaduan/submit', [PengaduanController::class, 'store'])->name('pengaduan.store');

    #keuangan
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('keuangan/upload/{id?}', [KeuanganController::class, 'upload'])->name('keuangan.upload');
    

    #dokumen
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/detail/{id}', function ($id) {
        return view('operator_yayasan.v_dokumen.detail_dokumen', compact('id'));
    });
    Route::get('/detail_dokumen/{id}', [DokumenController::class, 'show']);


    #data sekolah

    #data siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');


    #data guru
    Route::get('guru', [GuruController::class, 'index'])->name('guru.index');


    // Route Users
    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
});







require __DIR__ . '/auth.php';
