<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'operator_sekolah') {
            $data = Pengaduan::where('npsn', Auth::user()->npsn)->get();
        } else {
            $data = Pengaduan::all();
        }

        return view('operator_yayasan.v_pengaduan.index', compact('data'));
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
            'judul'     => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|string|in:Kendala Teknis,Pelayanan,Lainnya',
            'bukti'     => 'nullable|image|max:2048',
        ]);

        // 1. Buat bagian awal ID
        $prefix = 'PD' . now()->format('dmy'); // Contoh: PD240525

        // Get the last number for today
        $lastId = Pengaduan::where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('id');

        if ($lastId) {
            $lastNumber = (int)substr($lastId, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $customId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 5. Simpan file jika ada
        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti_pengaduan', 'public');
        }

        // 6. Simpan ke database
        Pengaduan::create([
            'id'          => $customId,
            'npsn'        => Auth::user()->npsn,
            'judul'       => $validated['judul'],
            'deskripsi'   => $validated['deskripsi'],
            'kategori'    => $validated['kategori'],
            'bukti_path'  => $path,
            'status'      => 'Menunggu',
            'submitted_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pengaduan berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        return view('operator_yayasan.v_pengaduan.detail', compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Search for Pengaduan data (AJAX).
     */
    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $query   = Pengaduan::query();

        if (Auth::user()->role == 'operator_sekolah') {
            $query->where('npsn', Auth::user()->npsn);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%$keyword%")
                    ->orWhere('id', 'like', "%$keyword%")
                    ->orWhere('status', 'like', "%$keyword%");
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return response()->json($data);
    }

    /**
     * Update the status of the Pengaduan (AJAX).
     */
    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'operator_yayasan') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai'
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = $request->status;
        $pengaduan->verified_by = auth()->id();
        $pengaduan->verified_at = now();
        $pengaduan->save();

        return response()->json(['success' => true]);
    }
}
