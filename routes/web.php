<?php

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Keuangan;
use Illuminate\Http\Request;
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

// Route landing page
Route::get('/', function () {
    return view('Landing_Page.index');
})->name('landing-page');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $tahunDipilih = $request->get('tahun') ?? date('Y');

        if (Auth::user()->role === 'operator_sekolah') {
            $npsn = Auth::user()->npsn;
            $jumlahSiswa = Siswa::where('npsn', $npsn)->count();
            $jumlahGuru = Guru::where('npsn', $npsn)->count();
            $keuangan = \App\Models\Keuangan::where('npsn', $npsn)
                ->where('tahun', $tahunDipilih)
                ->get();
            $pengaduans = \App\Models\Pengaduan::where('npsn', $npsn)->get();

            $tahunSekarang = date('Y');
            $tahunList = Keuangan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');


            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];
            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
            }
        } else {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $keuangan = \App\Models\Keuangan::where('tahun', $tahunDipilih)->get();
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

        if ($request->ajax()) {
            return response()->json([
                'keuangan' => $keuangan,
            ]);
        }

        return view('operator_yayasan.v_dashboard.index', compact(
            'jumlahSiswa', 'jumlahGuru', 'keuangan', 'pengaduans',
            'jumlahGuruPerTahun', 'jumlahSiswaPerTahun', 'tahunList', 'tahunDipilih'
        ));
    })->name('dashboard');

    // Route AJAX untuk data keuangan berdasarkan tahun
    Route::get('/keuangan/by-tahun', function (Request $request) {
        $tahun = $request->get('tahun') ?? date('Y');

        if (Auth::user()->role === 'operator_sekolah') {
            $npsn = Auth::user()->npsn;
            $data = \App\Models\Keuangan::where('npsn', $npsn)
                ->where('tahun', $tahun)
                ->get();
        } else {
            $data = \App\Models\Keuangan::where('tahun', $tahun)->get();
        }

        return response()->json($data);
    })->name('keuangan.byTahun');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengaduan routes
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/detail/{id}', [PengaduanController::class, 'show'])->name('pengaduan.detail');
    Route::get('/pengaduan/search', [PengaduanController::class, 'search'])->name('pengaduan.search');
    Route::post('/pengaduan/submit', [PengaduanController::class, 'store'])->name('pengaduan.store');

    // Keuangan routes
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('keuangan/upload/{id?}', [KeuanganController::class, 'upload'])->name('keuangan.upload');

    // Dokumen routes
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/detail/{id_pengajuan}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::post('/dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');

    // Data siswa routes
    Route::get('/siswa/sekolah', [SiswaController::class, 'ListSekolah'])->name('siswa.sekolah');
    Route::get('/siswa', [SiswaController::class, 'redirectToSiswa'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/by-sekolah/{npsn}', [SiswaController::class, 'index'])->name('siswa.by-sekolah');

    // Data guru routes
    Route::get('guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/search-guru', [GuruController::class, 'search'])->name('search.guru');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');

    // Users route
    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
});

require __DIR__ . '/auth.php';
