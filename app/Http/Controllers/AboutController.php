<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\about;
use App\Models\Siswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jumlahSekolah = Sekolah::count();
        $jumlahSiswa = Siswa::where('status', 'aktif')->count();
        $jumlahGuru = Guru::where('status', 'aktif')->count();

        return view('Landing_Page.index', compact('jumlahSekolah', 'jumlahSiswa', 'jumlahGuru'));
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
    public function show(about $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(about $about)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, about $about)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(about $about)
    {
        //
    }
}
