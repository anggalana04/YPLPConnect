<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class ListSekolahController extends Controller
{
    // Menampilkan daftar sekolah untuk operator yayasan
public function index(Request $request)
{
    $sekolah = Sekolah::all();
    $from = $request->query('from'); // menangkap menu asal
    return view('operator_yayasan.v_list-sekolah.index', compact('sekolah', 'from'));
}

}
