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
            $npsn = Auth::user()->npsn;
            $jumlahSiswa = Siswa::where('npsn', $npsn)->count();
            $jumlahGuru = Guru::where('npsn', $npsn)->count();
            $keuangan = \App\Models\Keuangan::where('npsn', $npsn)->get();
            $pengaduans = \App\Models\Pengaduan::where('npsn', $npsn)->get();

            $tahunSekarang = date('Y');
            $tahunList = [];
            for ($i = 5; $i >= 0; $i--) {
                $tahunList[] = (string)($tahunSekarang - $i);
            }
            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];
            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
            }
        } else {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $keuangan = \App\Models\Keuangan::all();
            $pengaduans = \App\Models\Pengaduan::all();

            $tahunSekarang = date('Y');
            $tahunList = [];
            for ($i = 5; $i >= 0; $i--) {
                $tahunList[] = (string)($tahunSekarang - $i);
            }
            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];
            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::whereYear('created_at', $tahun)->count();
            }
        }

        return view('operator_yayasan.v_dashboard.index', compact(
            'jumlahSiswa', 'jumlahGuru', 'keuangan', 'pengaduans',
            'jumlahGuruPerTahun', 'jumlahSiswaPerTahun', 'tahunList'
        ));
    })->name('dashboard');

    
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
    Route::get('/dokumen/detail/{id_pengajuan}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::post('/dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');

    #data sekolah


    #data siswa
    Route::get('/siswa/sekolah', [SiswaController::class, 'ListSekolah'])->name('siswa.sekolah');
    Route::get('/siswa', [SiswaController::class, 'redirectToSiswa'])->name('siswa.index');
    Route::get('/siswa/{npsn}', [SiswaController::class, 'index'])->name('siswa.by-sekolah');

    #data guru
    Route::get('guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/search-guru', [GuruController::class, 'search'])->name('search.guru');



    // Route Users
    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
});







require __DIR__ . '/auth.php';
