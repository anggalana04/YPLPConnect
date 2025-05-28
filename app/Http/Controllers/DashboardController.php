<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Keuangan;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    if (Auth::user()->role === 'operator_sekolah') {
        $jumlahSiswa = \App\Models\Siswa::where('npsn', Auth::user()->npsn)->count();
        $jumlahGuru = \App\Models\Guru::where('npsn', Auth::user()->npsn)->count();
        return view('operator_sekolah.v_dashboard.index', compact('jumlahSiswa', 'jumlahGuru'));
    } else {
        $jumlahSiswa = \App\Models\Siswa::count();
        $jumlahGuru = \App\Models\Guru::count();
        return view('operator_yayasan.v_dashboard.index', compact('jumlahSiswa', 'jumlahGuru'));
    }
}

}
