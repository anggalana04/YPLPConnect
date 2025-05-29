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
        //
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
