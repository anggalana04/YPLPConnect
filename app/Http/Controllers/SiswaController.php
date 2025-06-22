<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Sekolah;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function redirectToSiswa()
    {
        if (Auth::user()->role === 'operator_yayasan') {
            // Tampilkan list sekolah
            $sekolah = Sekolah::all();
            return view('operator_yayasan.v_list-sekolah.index', compact('sekolah'));
        } else {
            // Tampilkan data siswa sesuai npsn operator sekolah
            $siswa = Siswa::where('npsn', Auth::user()->npsn)->get();
            return view('operator_yayasan.v_data_siswa.index', compact('siswa'));
        }
    }


    public function index($npsn = null)
    {
        if (Auth::user()->role == 'operator_sekolah') {
            $siswa = Siswa::where('npsn', Auth::user()->npsn)->get();
        } elseif (Auth::user()->role == 'operator_yayasan') {
            // Tambahan logika yayasan: hanya tampilkan data jika npsn dikirim
            if ($npsn) {
                $siswa = Siswa::where('npsn', $npsn)->get();
            } else {
                $siswa = collect(); // kosongkan jika belum pilih sekolah
            }
        } else {
            $siswa = collect(); // kosongkan untuk role lain
        }

        return view('operator_yayasan.v_data_siswa.index', compact('siswa'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input lain
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'nisn'           => 'required|string|max:20|unique:siswa,nisn',
            'kelas'          => 'required|string|max:50',
            'alamat'         => 'nullable|string|max:255',
            'jenis_kelamin'  => 'required|in:L,P',
            'ttl'            => 'required|string', // validasi gabungan
        ]);

        // Pisahkan tempat dan tanggal lahir
        // Format: "Tempat, YYYY-MM-DD"
        $ttl = explode(',', $request->ttl, 2);
        $tempat = trim($ttl[0]);
        $tanggal = isset($ttl[1]) ? trim($ttl[1]) : null;

        // Validasi tanggal
        if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return back()->withErrors(['ttl' => 'Format tempat, tanggal lahir tidak valid. Contoh: Jakarta, 2001-01-01'])->withInput();
        }

        // Simpan ke database
        $siswaBaru = Siswa::create([
            'nama'           => $validated['nama'],
            'nisn'           => $validated['nisn'],
            'kelas'          => $validated['kelas'],
            'alamat'         => $validated['alamat'],
            'jenis_kelamin'  => $validated['jenis_kelamin'],
            'tempat_lahir'   => $tempat,
            'tanggal_lahir'  => $tanggal,
            'npsn'           => Auth::user()->npsn ?? null, // atau sesuaikan sumber npsn
        ]);

        // --- Tambahan: Update jumlah_spp di keuangan ---
        $npsn = Auth::user()->npsn ?? null;
        if ($npsn) {
            $jumlahSiswa = \App\Models\Siswa::where('npsn', $npsn)->count();
            $tahun = date('Y');
            $bulanSekarang = (int)date('n');
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
            for ($i = $bulanSekarang - 1; $i < 12; $i++) { // mulai dari bulan sekarang (0-based)
                $bulan = $bulanList[$i];
                \App\Models\Keuangan::where('npsn', $npsn)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->update(['jumlah_spp' => 2000 * $jumlahSiswa]);
            }
        }
        // --- END Tambahan ---

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil disimpan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nisn)
    {
        $siswa = Siswa::findOrFail($nisn);
        return response()->json($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nisn)
    {
        $siswa = Siswa::findOrFail($nisn);
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'kelas'          => 'required|string|max:50',
            'alamat'         => 'nullable|string|max:255',
            'jenis_kelamin'  => 'required|in:L,P',
            'ttl'            => 'required|string',
        ]);
        $ttl = explode(',', $request->ttl, 2);
        $tempat = trim($ttl[0]);
        $tanggal = isset($ttl[1]) ? trim($ttl[1]) : null;
        if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return back()->withErrors(['ttl' => 'Format tempat, tanggal lahir tidak valid. Contoh: Jakarta, 2001-01-01'])->withInput();
        }
        $siswa->update([
            'nama'           => $validated['nama'],
            'kelas'          => $validated['kelas'],
            'alamat'         => $validated['alamat'],
            'jenis_kelamin'  => $validated['jenis_kelamin'],
            'tempat_lahir'   => $tempat,
            'tanggal_lahir'  => $tanggal,
        ]);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nisn, Request $request)
    {
        $siswa = Siswa::findOrFail($nisn);
        $npsn = $request->input('npsn', $siswa->npsn); // fallback to siswa's npsn if not provided
        $siswa->delete();
        return redirect()->route('siswa.by-sekolah', $npsn)->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        try {
            $import = new \App\Imports\SiswaImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $npsn = Auth::user()->npsn;

        $siswa = Siswa::where('npsn', $npsn)
            ->where(function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('nisn', 'like', '%' . $keyword . '%')
                    ->orWhere('kelas', 'like', '%' . $keyword . '%');
            })
            ->get();

        return response()->json([
            'data' => $siswa
        ]);
    }
}
