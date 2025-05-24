<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $npsn = $user->npsn ?? null;
        $tahun = date('Y');

        // Ambil data keuangan untuk tahun berjalan
        $keuangan = Keuangan::where('npsn', $npsn)
            ->where('tahun', $tahun)
            ->select('bulan', 'jumlah_spp', 'status')
            ->get();

        // Contoh: jumlah siswa dari model Siswa (opsional, sesuaikan dengan model kamu)
        $jumlahSiswa = Siswa::where('npsn', $npsn)->count();

        return view('operator_sekolah.v_dashboard.index', compact('keuangan', 'jumlahSiswa'));
    }
}
