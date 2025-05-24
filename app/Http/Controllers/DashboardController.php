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
        $user = Auth::user();
        $npsn = $user->npsn ?? null;
        $tahun = date('Y');

        $keuangan = Keuangan::where('npsn', $npsn)
            ->where('tahun', $tahun)
            ->select('bulan', 'jumlah_spp', 'status')
            ->get();

        $jumlahSiswa = Siswa::where('npsn', $npsn)->count();

        $pengaduan = Pengaduan::where('npsn', $npsn)
            ->latest()
            ->first();

        return view('operator_sekolah.v_dashboard.index', compact('keuangan', 'jumlahSiswa', 'pengaduan'));
    }

}
