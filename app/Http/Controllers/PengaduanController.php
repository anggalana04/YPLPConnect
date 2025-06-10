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
public function index(Request $request)
{
    $query = Pengaduan::query();

    if (Auth::user()->role == 'operator_sekolah') {
        $query->where('npsn', Auth::user()->npsn);
    } elseif (Auth::user()->role == 'operator_yayasan') {
        $npsn = $request->npsn;

        if ($npsn) {
            $query->where('npsn', $npsn);
        }
    } else {
        abort(403, 'Unauthorized access'); // Handle other roles
    }

    // Tambahan filter jika diperlukan
    if ($request->filled('q')) {
        $query->where('judul', 'like', '%' . $request->q . '%');
    }

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    $data = $query->latest()->get();

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
            'judul'     => 'required|string|min:15|max:100', // Added min:15 for minimum character validation
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

    public function updateStatus($id, $status)
    {
        $allowedStatuses = ['diterima', 'diproses', 'selesai'];

        if (!in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $pengaduan = Pengaduan::findOrFail($id);
        $currentStatus = strtolower($pengaduan->status);

        // Ensure logical status transition
        $statusOrder = ['menunggu', 'terkirim', 'diterima', 'diproses', 'selesai'];
        $currentIndex = array_search($currentStatus, $statusOrder);
        $newIndex = array_search($status, $statusOrder);

        if ($newIndex <= $currentIndex) {
            return redirect()->back()->with('error', 'Status tidak bisa mundur atau sama.');
        }

        $pengaduan->status = $status;
        $pengaduan->save();

        return redirect()->back()->with('success', ucfirst($status) . ' sukses');
    }


}
