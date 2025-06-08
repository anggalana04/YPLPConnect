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
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ListSekolahController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Route landing page
Route::get('/', function () {
    return view('Landing_Page.index');
})->name('landing-page');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $tahunDipilih = $request->get('tahun') ?? date('Y');
        $bulanList = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $keuanganPerTahun = [];
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
            $dokumens = \App\Models\Dokumen::with('guru')->get();


            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];
            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
            }

            // FIX: Define $keuanganPerTahun for operator_sekolah
            $keuanganPerTahun = [];
            foreach ($tahunList as $tahun) {
                $keuanganPerTahun[] = Keuangan::where('npsn', $npsn)->where('tahun', $tahun)->sum('jumlah_spp');
            }
        } else {
            $dokumens = \App\Models\Dokumen::with('guru')->get();

            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $keuangan = collect();

            $tahunSekarang = date('Y');
            $tahunList = [];
            for ($i = 5; $i >= 0; $i--) {
                $tahunList[] = (string)($tahunSekarang - $i);
            }

            $keuanganPerTahun = [];
            foreach ($tahunList as $tahun) {
                $keuanganPerTahun[] = Keuangan::where('tahun', $tahun)->sum('jumlah_spp');
            }

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
            'jumlahSiswa',
            'jumlahGuru',
            'keuangan',
            'pengaduans',
            'jumlahGuruPerTahun',
            'jumlahSiswaPerTahun',
            'tahunList',
            'tahunDipilih',
            'bulanList',
            'keuanganPerTahun',
            'dokumens'
        ));
    })->name('dashboard');

    // Route AJAX untuk data keuangan berdasarkan tahun
    Route::get('/keuangan/by-tahun', [KeuanganController::class, 'getByTahun'])->name('keuangan.byTahun');
    // Route::get('/keuangan/by-tahun', function (Request $request) {
    //     $tahun = $request->get('tahun') ?? date('Y');

    //     if (Auth::user()->role === 'operator_sekolah') {
    //         $npsn = Auth::user()->npsn;
    //         $data = \App\Models\Keuangan::where('npsn', $npsn)
    //             ->where('tahun', $tahun)
    //             ->get();
    //     } else {
    //         $data = \App\Models\Keuangan::where('tahun', $tahun)->get();
    //     }

    //     return response()->json($data);
    // })->name('keuangan.byTahun');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengaduan routes
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/yayasan', [PengaduanController::class, 'yayasan'])->name('pengaduan.yayasan');
    Route::get('/pengaduan/detail/{id}', [PengaduanController::class, 'show'])->name('pengaduan.detail');
    Route::get('/pengaduan/search', [PengaduanController::class, 'search'])->name('pengaduan.search');
    Route::post('/pengaduan/submit', [PengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
    // Update status pakai PUT, status disisipkan di URL
    Route::put('/pengaduan/{id}/status/{status}', [PengaduanController::class, 'updateStatus'])
        ->name('pengaduan.updateStatus')
        ->middleware('auth');


    // Keuangan routes
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('keuangan/upload/{id?}', [KeuanganController::class, 'upload'])->name('keuangan.upload');
    Route::get('/keuangan/yayasan', [KeuanganController::class, 'yayasan'])->name('keuangan.yayasan');
    Route::post('/keuangan/validasi/{id}', [KeuanganController::class, 'validasi'])->name('keuangan.validasi');
    Route::get('/keuangan/bukti-preview/{id}', [KeuanganController::class, 'previewBukti'])->name('keuangan.bukti.preview');
    Route::get('/keuangan/download-recap', [KeuanganController::class, 'downloadRecap'])->name('keuangan.download.recap');


    // Dokumen routes
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/detail/{id_pengajuan}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::post('/dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen/yayasan', [DokumenController::class, 'yayasan'])->name('dokumen.yayasan');
    Route::get('/dokumen/ajax/search', [DokumenController::class, 'ajaxSearch'])->name('dokumen.ajax.search');
    Route::get('/dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::put('/dokumen/{id}/status/{status}', [DokumenController::class, 'updateStatus'])->name('dokumen.updateStatus');



    // Data siswa routes
    Route::get('/siswa', [SiswaController::class, 'redirectToSiswa'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/search', [SiswaController::class, 'search'])->name('siswa.search');
    Route::get('/siswa/by-sekolah/{npsn}', [SiswaController::class, 'index'])->name('siswa.by-sekolah');

    // Data guru routes
    Route::get('guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/search-guru', [GuruController::class, 'search'])->name('search.guru');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');


    // Users route
    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
    Route::post('/user-manage/update-inline/{id}', [RegisteredUserController::class, 'updateInline'])->name('user.update-inline');
    Route::patch('/users/{id}', [RegisteredUserController::class, 'update'])->name('user.update');

    // Route Sekolah
    Route::post('/sekolah', [SekolahController::class, 'store'])->name('sekolah.store');

    // Route untuk menampilkan daftar sekolah khusus operator yayasan
    Route::get('/sekolah', [ListSekolahController::class, 'index'])->name('sekolah.index');
});

require __DIR__ . '/auth.php';
