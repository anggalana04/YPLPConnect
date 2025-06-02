<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        } elseif ($npsn) {
            $siswa = Siswa::where('npsn', $npsn)->get();
        } else {
            $siswa = Siswa::all();
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
    Siswa::create([
        'nama'           => $validated['nama'],
        'nisn'           => $validated['nisn'],
        'kelas'          => $validated['kelas'],
        'alamat'         => $validated['alamat'],
        'jenis_kelamin'  => $validated['jenis_kelamin'],
        'tempat_lahir'   => $tempat,
        'tanggal_lahir'  => $tanggal,
        'npsn'           => Auth::user()->npsn ?? null, // atau sesuaikan sumber npsn
    ]);

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
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        //
    }

    
}
