<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dokumen::query();

        if (Auth::user()->role === 'operator_sekolah') {
            $npsn = Auth::user()->npsn;

            // Filter dokumen berdasarkan NPSN guru
            $query->whereHas('guru', function ($q) use ($npsn) {
                $q->where('npsn', $npsn);
            });
        } elseif (Auth::user()->role === 'operator_yayasan') {
            $npsn = $request->npsn;

            // Jika yayasan pilih NPSN, filter sesuai itu
            if ($npsn) {
                $query->whereHas('guru', function ($q) use ($npsn) {
                    $q->where('npsn', $npsn);
                });
            } else {
                // Jika tidak memilih sekolah, tampilkan data kosong
                $query->whereRaw('0 = 1');
            }
        }

        // Tambahan filter jika diperlukan
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('jenis_sk', $request->kategori);
        }

        $dokumen = $query->latest()->get();

        // Ambil guru dari sekolah tertentu (misal dari session, request, atau semua guru)
        if (isset($npsn) && $npsn) {
            $guruList = \App\Models\Guru::where('npsn', $npsn)->get();
        } else {
            $guruList = collect();
        }

        return view('operator_yayasan.v_dokumen.index', compact('dokumen', 'guruList'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $npsn = $request->input('npsn'); // or get from route param
        $guruList = \App\Models\Guru::where('npsn', $npsn)->get();
        return view('operator_yayasan.v_dokumen.create', compact('guruList', 'npsn'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nuptk'   => 'required|string|max:16',
            'alamat'  => 'required|string|max:255',
            'kategori' => 'required|in:SK Pengangkatan,SK Pensiun,SK Mutasi',
        ]);

        $guru = Guru::where('nuptk', $validated['nuptk'])->first();
        if (!$guru) {
            return back()->withErrors(['nuptk' => 'Guru tidak ditemukan'])->withInput();
        }

        $dokumen = new Dokumen();
        $dokumen->nuptk             = $guru->nuptk;
        $dokumen->nama              = $guru->nama_gelar ?? $guru->nama;
        $dokumen->tempat_lahir      = $guru->tempat_lahir;
        $dokumen->tanggal_lahir     = $guru->tanggal_lahir;
        $dokumen->alamat_unit_kerja = $validated['alamat'];
        $dokumen->jenis_sk          = $validated['kategori'];
        $dokumen->status            = 'Menunggu';
        $dokumen->submitted_by      = Auth::id();
        $dokumen->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disimpan!');
    }

    /**
     * Generate custom ID dokumen (format: PJddmmyyyyXXXX)
     */
    private function generateDokumenId(): string
    {
        $tanggal = now()->format('dmy'); // contoh: 250524
        $prefix  = 'PJ' . $tanggal;

        $last = DB::table('dokumen')
            ->where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last && isset($last->id)) {
            $lastNumber = (int)substr($last->id, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $guru = Guru::where('nuptk', $dokumen->nuptk)->first(); // cari data guru yang sesuai

        $sekolah = $guru?->sekolah; // pakai relasi

        return view('operator_yayasan.v_dokumen.detail_dokumen', compact('dokumen', 'guru', 'sekolah'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumen $dokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        //
    }

    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (!$dokumen->file_path || !Storage::exists('public/' . $dokumen->file_path)) {
            return back()->with('error', 'File SK belum tersedia.');
        }

        return Storage::download('public/' . $dokumen->file_path, 'SK_' . $dokumen->id . '.pdf');
    }

    public function ajaxSearch(Request $request)
    {
        $query = Dokumen::query();

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('jenis_sk', $request->kategori);
        }

        $dokumen = $query->latest()->get();

        $html = '';
        foreach ($dokumen as $row) {
            $html .= '
        <tr class="clickable-row" data-id="' . $row->id . '">
            <td>' . $row->id . '</td>
            <td>' . $row->nuptk . '</td>
            <td>' . $row->nama . '</td>
            <td>' . $row->jenis_sk . '</td>
            <td>' . $row->status . '</td>
        </tr>';
        }

        return response()->json(['html' => $html]);
    }

    public function updateStatus(Request $request, $id, $status)
    {
        $allowed = ['Selesai', 'Ditolak'];
        if (!in_array($status, $allowed)) {
            return back()->with('error', 'Status tidak valid');
        }
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->status = $status;

        // Jika status disetujui (Selesai), generate PDF dan simpan path-nya
        if ($status === 'Selesai') {
            $guru = $dokumen->guru;
            $sekolah = $guru?->sekolah;
            $pdf = Pdf::loadView('operator_yayasan.v_dokumen.Dokumen_PDF_Layouts', compact('dokumen', 'guru', 'sekolah'));
            $pdfPath = 'dokumen/sk_' . $dokumen->id . '.pdf';
            Storage::put('public/' . $pdfPath, $pdf->output());
            $dokumen->file_path = $pdfPath;
        }

        $dokumen->save();

        return back()->with('success', 'Status dokumen berhasil diubah!');
    }
}
