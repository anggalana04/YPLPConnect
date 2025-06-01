<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            $query->whereHas('guru', function ($q) use ($npsn) {
                $q->where('npsn', $npsn);
            });
        }

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('jenis_sk', $request->kategori);
        }

        $dokumen = $query->latest()->get();

        return view('operator_yayasan.v_dokumen.index', compact('dokumen'));
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
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'npa'      => 'required|string|max:16',
            'ttl'      => 'required|string|max:255', // Format: "Tempat, YYYY-MM-DD"
            'alamat'   => 'required|string|max:255',
            'kategori' => 'required|in:SK Pengangkatan,SK Pensiun,SK Mutasi',
        ]);

        // Pisahkan TTL
        $ttlParts = explode(',', $validated['ttl']);
        $tempat_lahir  = trim($ttlParts[0]);
        $tanggal_lahir = isset($ttlParts[1]) ? trim($ttlParts[1]) : null;

        // Buat record dokumen
        $dokumen = new Dokumen();
        $dokumen->id                = $this->generateDokumenId();
        $dokumen->nuptk             = $validated['npa'];
        $dokumen->nama              = $validated['nama'];
        $dokumen->tempat_lahir      = $tempat_lahir;
        $dokumen->tanggal_lahir     = $tanggal_lahir;
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

    return view('operator_yayasan.v_dokumen.detail_dokumen', compact('dokumen', 'guru'));
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
    $guru = Guru::where('nuptk', $dokumen->nuptk)->first();

    $pdf = Pdf::loadView('operator_yayasan.v_dokumen.Dokumen_PDF_Layouts', compact('dokumen', 'guru'));
    // Tampilkan langsung di browser (inline)
    return $pdf->stream('dokumen.pdf');
    // return $pdf->download('Dokumen_SK_' . $dokumen->id . '.pdf');
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

   
    
}
