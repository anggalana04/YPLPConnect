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
        // Ambil semua data pengaduan dari database
        $data = Pengaduan::orderBy('created_at', 'asc')->get();

        if (Auth::user()->role === 'operator_yayasan') {
            return view('operator_yayasan.v_pengaduan.index', compact('data'));
        } elseif (Auth::user()->role === 'operator_sekolah') {
            return view('operator_sekolah.v_pengaduan.index', compact('data'));
        } else {
            return redirect('/'); // Jika role tidak sesuai, arahkan ke halaman utama
        }
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
                    'judul' => 'required|string|max:100',
                    'deskripsi' => 'required|string',
                    'kategori' => 'required|string|in:Kendala Teknis,Pelayanan,Lainnya',
                    'bukti' => 'nullable|image|max:2048',
                ]);

                // 1. Buat bagian awal ID
                $prefix = 'PD' . now()->format('dmy'); // Contoh: PD240525

                // 2. Ambil jumlah pengaduan hari ini
                $countToday = Pengaduan::whereDate('created_at', now())
                    ->where('id', 'like', $prefix . '%')
                    ->count() + 1;

                // 3. Buat nomor 4 digit
                $number = str_pad($countToday, 4, '0', STR_PAD_LEFT); // 0001

                // 4. Gabungkan jadi ID lengkap
                $customId = $prefix . $number; // contoh: PD2405250001

                // 5. Simpan file jika ada
                $path = null;
                if ($request->hasFile('bukti')) {
                    $path = $request->file('bukti')->store('bukti_pengaduan', 'public');
                }

                // 6. Simpan ke database
                Pengaduan::create([
                    'id' => $customId,
                    'npsn' => Auth::user()->npsn,
                    'judul' => $validated['judul'],
                    'deskripsi' => $validated['deskripsi'],
                    'kategori' => $validated['kategori'],
                    'bukti_path' => $path,
                    'status' => 'Menunggu',
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
}
