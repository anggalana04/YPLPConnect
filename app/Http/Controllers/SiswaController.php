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

    public function listSekolah() {
        $sekolah = Sekolah::all(); // Ambil semua data sekolah dari DB
        return view('operator_yayasan.v_list-sekolah.index', compact('sekolah'));
    }

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
        //
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
