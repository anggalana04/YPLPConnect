<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if (Auth::user()->role == 'operator_sekolah') {
            $guru = Guru::where('npsn', Auth::user()->npsn)->get();
        } else {
            $guru = Guru::all();
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
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        //
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

}
