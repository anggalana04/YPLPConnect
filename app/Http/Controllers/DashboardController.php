<?php
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Keuangan;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $tahunSekarang = date('Y');
        $tahunDipilih = request('tahun') ?? $tahunSekarang;

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tahunList = Keuangan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        $keuangan = [];
        $keuanganPerTahun = [];

        if (Auth::user()->role === 'operator_sekolah') {
            $npsn = Auth::user()->npsn;

            $jumlahSiswa = Siswa::where('npsn', $npsn)->count();
            $jumlahGuru = Guru::where('npsn', $npsn)->count();

            $keuangan = Keuangan::where('npsn', $npsn)
                ->where('tahun', $tahunDipilih)
                ->select('bulan', DB::raw('SUM(nominal) as total_nominal'), DB::raw('MAX(status) as status'))
                ->groupBy('bulan')
                ->orderByRaw("FIELD(bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
                ->get();

           $pengaduans = Pengaduan::where('npsn', $npsn)
                       ->where('status', 'menunggu')
                       ->get();


            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];

            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
            }

            return view('operator_yayasan.v_dashboard.index', compact(
                'jumlahSiswa', 'jumlahGuru', 'keuangan', 'pengaduans',
                'jumlahGuruPerTahun', 'jumlahSiswaPerTahun', 'tahunList', 'tahunDipilih', 'bulanList'
            ));
        }

        // Operator Yayasan
        else {
            $tahunList = Keuangan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
            $tahunDipilih = request('tahun') ?? now()->year;
            $totalKeuanganTahun = \App\Models\Keuangan::where('tahun', $tahunDipilih)->sum('jumlah_spp');


            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $dokumens = \App\Models\Dokumen::with('guru')->get();


            $keuanganPerBulan = [];
            $bulanList = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            foreach ($bulanList as $bulan) {
                $total = Keuangan::where('tahun', $tahunDipilih)->where('bulan', $bulan)->sum('jumlah_spp');
                $keuanganPerBulan[] = $total;
            }


            $pengaduans = Pengaduan::all();

            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];

            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::whereYear('created_at', $tahun)->count();
            }

            return view('operator_yayasan.v_dashboard.index', compact(
                'jumlahSiswa', 'jumlahGuru', 'keuanganPerBulan', 'pengaduans',
                'jumlahGuruPerTahun', 'jumlahSiswaPerTahun', 'tahunList', 'tahunDipilih', 'bulanList','dokumens', 'totalKeuanganTahun'
            ));
        }
    }

    // app/Http/Controllers/KeuanganController.php

public function getByTahun(Request $request)
{
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
}

public function getKeuanganYayasanByTahun(Request $request)
{
    $tahun = $request->get('tahun') ?? date('Y');

    $bulanList = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $keuanganPerBulan = [];

    foreach ($bulanList as $bulan) {
        $jumlahSpp = Keuangan::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->sum('jumlah_spp');

        $keuanganPerBulan[] = [
            'bulan' => $bulan,
            'jumlah_spp' => $jumlahSpp,
        ];
    }

    return response()->json($keuanganPerBulan);
}


}
