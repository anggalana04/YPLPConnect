<?php
namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Keuangan;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $tahunSekarang = date('Y');
        $tahunList = [];
        for ($i = 5; $i >= 0; $i--) {
            $tahunList[] = (string)($tahunSekarang - $i);
        }

        $jumlahGuruPerTahun = [];

        if (Auth::user()->role === 'operator_sekolah') {
            $npsn = Auth::user()->npsn;
            $jumlahSiswa = Siswa::where('npsn', $npsn)->count();
            $jumlahGuru = Guru::where('npsn', $npsn)->count();
            $keuangan = Keuangan::where('npsn', $npsn)->get();
            $pengaduans = Pengaduan::where('npsn', $npsn)
                ->latest()
                ->take(5)
                ->get();

            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)
                    ->whereYear('created_at', $tahun)
                    ->count();
            }
        } else {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $keuangan = Keuangan::all();
            $pengaduans = Pengaduan::latest()
                ->take(5)
                ->get();


            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::whereYear('created_at', $tahun)->count();
            }
        }

        return view('operator_yayasan.v_dashboard.index', compact(
            'jumlahSiswa', 'jumlahGuru', 'keuangan', 'pengaduans', 'jumlahGuruPerTahun', 'tahunList'
        ));
    }
}
