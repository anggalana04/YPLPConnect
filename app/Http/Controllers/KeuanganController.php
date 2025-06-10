<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $jumlahSiswa = Siswa::where('npsn', $user->npsn)->count();
        } elseif ($user->role === 'operator_yayasan' && $npsnDipilih) {
            $query->where('npsn', $npsnDipilih);
            $jumlahSiswa = Siswa::where('npsn', $npsnDipilih)->count();
        } else {
            abort(403, 'Unauthorized access'); // Handle other roles
        }

        $keuangan = $query->where('tahun', $tahunDipilih)->get();
        $tahunList = Keuangan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $sekolahList = $user->role === 'operator_yayasan' ? Sekolah::pluck('nama', 'npsn') : [];

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
        $keuangan = $id ? Keuangan::findOrFail($id) : Keuangan::where('npsn', $user->npsn)->where('tahun', $tahun)->where('bulan', $bulan)->first();

        if (!$keuangan) {
            $keuangan = new Keuangan();
            $keuangan->npsn = $user->npsn;
            $keuangan->tahun = $tahun;
            $keuangan->bulan = $bulan;
        }

        // Simpan file bukti
        $file = $request->file('bukti');
        $path = $file->store('bukti_spp', 'public');
        $keuangan->bukti_path = $path;
        $keuangan->status = 'Menunggu';
        $keuangan->save();

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload dan sudah terkirim');
    }

    /**
     * Validasi status keuangan.
     */
    public function validasi(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'operator_yayasan') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $keuangan = Keuangan::findOrFail($id);
        $keuangan->status = $request->status;
        $keuangan->verified_by = $user->id;
        $keuangan->verified_at = now();
        $keuangan->save();

        return response()->json(['success' => true, 'status' => $keuangan->status]);
    }

    /**
     * Get URL Bukti SPP.
     */
    public function getBukti($id)
    {
        $keuangan = \App\Models\Keuangan::find($id);
        if (!$keuangan || !$keuangan->bukti_path) {
            return response()->json(['success' => false]);
        }
        $url = asset('storage/' . $keuangan->bukti_path);
        return response()->json(['success' => true, 'url' => $url]);
    }



    public function previewBukti($id)
    {
        $data = Keuangan::findOrFail($id);

        if (!$data->bukti_path) {
            abort(404, 'Bukti tidak ditemukan.');
        }

        // Data tambahan (misalnya dari relasi)
        $jumlahSiswa = Siswa::where('npsn', $data->npsn)->count();
        $biayaPerSiswa = 2000;

        $viewData = [
            'bulan' => $data->bulan,
            'tahun' => $data->tahun, // Tambahkan ini
            'jumlahSiswa' => $jumlahSiswa,
            'biayaPerSiswa' => $biayaPerSiswa,
            'total' => $jumlahSiswa * $biayaPerSiswa,
            'catatan' => $data->catatan,
            'buktiPath' => $data->bukti_path,
        ];

        $pdf = Pdf::loadView('operator_yayasan.v_keuangan.Bukti_bulanan', $viewData)->setPaper('a4', 'portrait');

        return $pdf->stream("bukti_{$data->bulan}.pdf"); // Untuk preview, bukan download
    }

    public function downloadRecap(Request $request)
    {
        $tahun = $request->tahun;
        $user = Auth::user();

        if ($user->role !== 'operator_sekolah') {
            abort(403);
        }

        $npsn = $user->npsn;

        // Ambil data keuangan untuk tahun dan NPSN terkait
        $keuangan = Keuangan::where('npsn', $npsn)
            ->where('tahun', $tahun)
            ->get();

        // Cek apakah semua bulan dari Januari–Desember sudah lunas (status == Disetujui)
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

        foreach ($bulanList as $bulan) {
            $data = $keuangan->firstWhere('bulan', $bulan);
            if (!$data || $data->status !== 'Disetujui') {
                return back()->with('error', 'Lunasi pembayaran untuk mendownload recap.');
            }
        }

        // Jika semua bulan sudah lunas, lanjut generate PDF
        $sekolah = Sekolah::where('npsn', $npsn)->first();
        $namaSekolah = $sekolah ? $sekolah->nama : 'Nama Sekolah Tidak Ditemukan';

        $rekap = [];
        foreach ($bulanList as $bulan) {
            $rekap[] = [
                'bulan' => $bulan,
                'status' => 'Lunas',
            ];
        }

        $pdf = PDF::loadView('operator_yayasan.v_keuangan.all_recap', [
            'rekap' => $rekap,
            'tahun' => $tahun,
            'namaSekolah' => $namaSekolah,
        ]);

        return $pdf->stream("Rekap_Pembayaran_{$tahun}.pdf");
    }

    // AJAX: Keuangan Yayasan per tahun (for dashboard)
    public function ajaxYayasanKeuanganByTahun(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
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
        $data = [];
        foreach ($bulanList as $bulan) {
            $jumlah = \App\Models\Keuangan::where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('status', 'Disetujui')
                ->sum('jumlah_spp');
            $data[] = [
                'bulan' => $bulan,
                'jumlah_spp' => (float) $jumlah,
            ];
        }
        return response()->json($data);
    }

    // Approve keuangan (Setujui)
    public function setujui(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'operator_yayasan') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->status = 'Disetujui';
        $keuangan->verified_by = $user->id;
        $keuangan->verified_at = now();
        $keuangan->save();
        return response()->json(['success' => true, 'status' => $keuangan->status]);
    }

    // Reject keuangan (Tolak)
    public function tolak(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'operator_yayasan') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->status = 'Ditolak';
        $keuangan->verified_by = $user->id;
        $keuangan->verified_at = now();
        $keuangan->save();
        return response()->json(['success' => true, 'status' => $keuangan->status]);
    }
}
