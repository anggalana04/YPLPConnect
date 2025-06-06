<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListSekolahController extends Controller
{
    // Menampilkan daftar sekolah untuk operator yayasan
public function index(Request $request)
{
    // Jika bukan operator yayasan, redirect ke halaman sesuai menu
    if (Auth::user()->role !== 'operator_yayasan') {
        // Redirect ke halaman sesuai menu yang diminta (from)
        $from = $request->from;
        switch ($from) {
            case 'keuangan':
                return redirect()->route('keuangan.index');
            case 'dokumen':
                return redirect()->route('dokumen.index');
            case 'pengaduan':
                return redirect()->route('pengaduan.index');
            case 'guru':
                return redirect()->route('guru.index');
            case 'siswa':
                return redirect()->route('siswa.index');
            default:
                return redirect()->route('dashboard');
        }
    }

    // Hanya operator yayasan yang bisa melihat list sekolah
    $query = Sekolah::query();

    if ($request->filled('q')) {
        $query->where('nama', 'like', '%' . $request->q . '%');
    }
    if ($request->filled('kategori')) {
        $query->where('jenjang', $request->kategori);
    }

    $sekolah = $query->get();
    $from = $request->from;

    if ($request->ajax()) {
        $html = view('operator_yayasan.v_list-sekolah.index', compact('sekolah', 'from'))->render();
        return response()->json(['html' => $html]);
    }

    return view('operator_yayasan.v_list-sekolah.index', compact('sekolah', 'from'));
}

}
