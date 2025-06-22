<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $npsn = request()->query('npsn');

        if (Auth::user()->role == 'operator_sekolah') {
            $guru = Guru::where('npsn', Auth::user()->npsn)->get();
        } elseif (Auth::user()->role == 'operator_yayasan') {
            if ($npsn) {
                $guru = Guru::where('npsn', $npsn)->get();
            } else {
                $guru = collect();
            }
        } else {
            $guru = collect();
        }

        return view('operator_yayasan.v_data_guru.index', compact('guru'));
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'nuptk' => 'required|string|max:20|unique:guru,nuptk',
            'ttl' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // Pisahkan tempat dan tanggal lahir
        [$tempat_lahir, $tanggal_lahir] = explode(',', $request->ttl);

        Guru::create([
            'nama' => $request->nama,
            'nuptk' => $request->nuptk,
            'tempat_lahir' => trim($tempat_lahir),
            'tanggal_lahir' => trim($tanggal_lahir),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'npsn' => Auth::user()->npsn, // ✅ ini penting
            'status' => 'Aktif', // default atau bisa dari form
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nuptk)
    {
        $guru = Guru::findOrFail($nuptk);
        return response()->json($guru);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nuptk)
    {
        
        $guru = Guru::findOrFail($nuptk);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'nullable|string|max:20', // allow nullable for inline edit
            'status' => 'required|in:Aktif,Nonaktif',
        ]);
        $guru->update($validated);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->to(url('/guru?npsn=' . $guru->npsn))->with('success', 'Data guru berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nuptk, Request $request)
    {
        $guru = Guru::findOrFail($nuptk);
        $npsn = Auth::user()->npsn;
        $guru->delete();
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        // Redirect to /guru?npsn={npsn} (not /guru/{npsn})
        return redirect()->to(url('/guru?npsn=' . $npsn))->with('success', 'Data guru berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if (Auth::user()->role == 'operator_sekolah') {
            $gurus = Guru::where('npsn', Auth::user()->npsn)
                ->where(function ($q) use ($query) {
                    $q->where('nama', 'LIKE', "%{$query}%")
                        ->orWhere('nuptk', 'LIKE', "%{$query}%");
                })
                ->get();
        } else {
            $gurus = Guru::where('nama', 'LIKE', "%{$query}%")
                ->orWhere('nuptk', 'LIKE', "%{$query}%")
                ->get();
        }

        return response()->json($gurus);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data guru berhasil Ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
