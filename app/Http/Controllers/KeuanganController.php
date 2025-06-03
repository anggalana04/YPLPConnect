<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sekolah;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tahunDipilih = $request->input('tahun', date('Y'));
        $npsnDipilih = $request->input('npsn');
        $jumlahSiswa = 0;

        $query = Keuangan::query();

        if ($user->role === 'operator_sekolah') {
            $query->where('npsn', $user->npsn);
            $jumlahSiswa = \App\Models\Siswa::where('npsn', $user->npsn)->count();
        } elseif ($user->role === 'operator_yayasan' && $npsnDipilih) {
            $query->where('npsn', $npsnDipilih);
            $jumlahSiswa = \App\Models\Siswa::where('npsn', $npsnDipilih)->count();
        }


        $keuangan = $query->where('tahun', $tahunDipilih)->get();
        $tahunList = Keuangan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $sekolahList = [];
        if ($user->role === 'operator_yayasan') {
            $sekolahList = Sekolah::pluck('nama', 'npsn');
        }

        return view('operator_yayasan.v_keuangan.index', [
        'keuangan' => $keuangan,
        'tahunList' => $tahunList,
        'tahunDipilih' => $tahunDipilih,
        'sekolahList' => $sekolahList,
        'npsnDipilih' => $npsnDipilih,
         'jumlahSiswa' => $jumlahSiswa,
        ]);
    }

    /**
     * Handle upload bukti SPP (update or create).
     */
    public function upload(Request $request, $id = null)
    {
        $user = Auth::user();
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

        // Validasi file
        $request->validate([
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Jika id ada, update data keuangan yang sudah ada
        if ($id) {
            $keuangan = Keuangan::findOrFail($id);
        } else {
            // Jika tidak ada id, cari data keuangan berdasarkan npsn, tahun, bulan
            $keuangan = Keuangan::where('npsn', $user->npsn)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            // Jika belum ada, buat baru (minimal field)
            if (!$keuangan) {
                $keuangan = Keuangan::create([
                    'npsn' => $user->npsn,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'jumlah_spp' => 0,
                    'status' => 'Menunggu',
                ]);
            }
        }

        // Simpan file bukti
        $file = $request->file('bukti');
        $path = $file->store('bukti_spp', 'public');
        $keuangan->bukti_path = $path;
        $keuangan->status = 'Menunggu';
        $keuangan->save();

        return back()->with('success', 'Bukti berhasil diupload.');
    }


    // Tambahkan method lain jika diperlukan (show, edit, update, destroy)
}