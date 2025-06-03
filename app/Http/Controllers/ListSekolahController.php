<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class ListSekolahController extends Controller
{
    // Menampilkan daftar sekolah untuk operator yayasan
public function index(Request $request)
{
    $query = Sekolah::query();

    if ($request->filled('q')) {
        $query->where('nama', 'like', '%' . $request->q . '%');
    }
    if ($request->filled('kategori')) {
        $query->where('jenjang', $request->kategori);
    }

    $sekolah = $query->get();
    $from = $request->from; // <-- tambahkan baris ini

    if ($request->ajax()) {
        // Render isi tbody saja dari view index, tapi dengan flag khusus
        $html = view('operator_yayasan.v_list-sekolah.index', compact('sekolah', 'from'))
                    ->render();

        // Karena kita tidak ingin full page HTML, maka ambil bagian tbody saja dari $html.
        // Alternatif: Render partial (tapi kamu mau tanpa partial, jadi kita parse manual)

        // Cara sederhana: kirimkan full HTML, nanti di JS ambil bagian tbody-nya.
        return response()->json(['html' => $html]);
    }

    return view('operator_yayasan.v_list-sekolah.index', compact('sekolah','from'));
}


}
