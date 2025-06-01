<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Keuangan;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

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

        // Ambil tahun distinct dari tabel keuangan (5 tahun terakhir atau semua tahun ada di database)
        $tahunList = Keuangan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

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

            $pengaduans = Pengaduan::where('npsn', $npsn)->get();

            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];

            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::where('npsn', $npsn)->whereYear('created_at', $tahun)->count();
            }

        } else {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();

            $keuanganPerTahun = [];
            foreach ($tahunList as $tahun) {
                $total = Keuangan::where('tahun', $tahun)->sum('jumlah_spp');
                $keuanganPerTahun[] = $total;
            }


            $pengaduans = Pengaduan::all();

            $jumlahGuruPerTahun = [];
            $jumlahSiswaPerTahun = [];

            foreach ($tahunList as $tahun) {
                $jumlahGuruPerTahun[] = Guru::whereYear('created_at', $tahun)->count();
                $jumlahSiswaPerTahun[] = Siswa::whereYear('created_at', $tahun)->count();
            }
        }

        return view('operator_yayasan.v_dashboard.index', compact(
            'jumlahSiswa', 'jumlahGuru', 'keuangan', 'pengaduans',
            'jumlahGuruPerTahun', 'jumlahSiswaPerTahun', 'tahunList', 'tahunDipilih', 'bulanList', 'keuanganPerTahun'
        ));
    }
}
