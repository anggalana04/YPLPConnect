@extends('v_layouts.index')

@section('title', 'Detail Pengaduan')

@push('styles')
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
<link rel="stylesheet" href="{{ asset('css/pengaduan/detail-pengaduan.css') }}" />
@endpush

@if(session('success'))
    <div style="
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #d4edda;
        color: #155724;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    ">
        {{ session('success') }}
    </div>
@endif



@section('content')
<div class="konten">
    <h1>DETAIL PENGADUAN</h1>

    <label for="">Status</label>

    @php
        // Semua step
        $allSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];

        // Mapping status database ke step yang sesuai
        $statusMapping = [
            'menunggu' => 'terkirim',
            'terkirim' => 'terkirim',
            'diterima' => 'diterima',
            'diproses' => 'diproses',
            'selesai' => 'selesai',
        ];

        $statusDb = strtolower($pengaduan->status);
        $mappedStatus = $statusMapping[$statusDb] ?? 'terkirim';

        $user = auth()->user();

        // Tentukan step yang tampil
        if ($user->role === 'operator_yayasan') {
            $statusSteps = array_slice($allSteps, 1); // mulai dari 'diterima'
        } else {
            $statusSteps = $allSteps;
        }

        // Tentukan indeks step saat ini (sesuaikan dengan slice jika yayasan)
        $currentIndex = array_search($mappedStatus, $allSteps);
        if ($user->role === 'operator_yayasan' && $currentIndex < 1) {
            $currentIndex = 1; // minimal 'diterima'
        }
        // Sesuaikan currentIndex untuk array slice di yayasan
        $currentIndexForDisplay = $user->role === 'operator_yayasan' ? $currentIndex - 1 : $currentIndex;
    @endphp

    <div class="status-container {{ $user->role === 'operator_yayasan' ? 'yayasan' : '' }}">
        <div class="box-status-step">
            @foreach ($statusSteps as $index => $step)
                <div class="status-step {{ $index <= $currentIndexForDisplay ? 'active' : '' }}">
                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                    <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                </div>

                @if ($index < count($statusSteps) - 1)
                    <div class="status-line {{ $index < $currentIndexForDisplay ? 'active' : '' }}"></div>
                @endif
            @endforeach
        </div>

        <div class="ket-status">
            <p><strong>ID Pengaduan : </strong>{{ $pengaduan->id }}</p>
            <p><strong>Tanggal Pengaduan : </strong>{{ \Carbon\Carbon::parse($pengaduan->created_at)->format('d-m-Y') }}</p>
        </div>
    </div>

    <div class="table-box">
        <table class="table-detail">
            <thead>
                <tr>
                    <th>Deskripsi Pengaduan</th>
                    <th>Bukti Pengaduan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $pengaduan->deskripsi }}</td>
                    <td>
                        @if ($pengaduan->bukti_path)
                            <img src="{{ asset('storage/' . $pengaduan->bukti_path) }}" alt="Bukti Pengaduan" width="150" />
                        @else
                            *Tidak ada bukti
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tombol aksi untuk operator yayasan --}}
    @if($user->role === 'operator_yayasan')
    <div class="action-buttons">
        @php
            $status = $mappedStatus; // status sudah mapped ke step
        @endphp

        {{-- Tombol Diterima --}}
        <form action="{{ route('pengaduan.updateStatus', [$pengaduan->id, 'diterima']) }}" method="POST" class="form-action">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-diterima" {{ $status !== 'terkirim' ? 'disabled' : '' }}>Diterima</button>
        </form>

        {{-- Tombol Diproses --}}
        <form action="{{ route('pengaduan.updateStatus', [$pengaduan->id, 'diproses']) }}" method="POST" class="form-action">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-diproses" {{ $status !== 'diterima' ? 'disabled' : '' }}>Diproses</button>
        </form>

        {{-- Tombol Selesai --}}
        <form action="{{ route('pengaduan.updateStatus', [$pengaduan->id, 'selesai']) }}" method="POST" class="form-action">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-selesai" {{ $status !== 'diproses' ? 'disabled' : '' }}>Selesai</button>
        </form>
    </div>
    @endif
</div>

    <script>
        setTimeout(() => {
            const notif = document.getElementById('notif-success');
            if (notif) {
                notif.style.opacity = '0'; // Mulai animasi hilang
                setTimeout(() => {
                    notif.remove(); // Hapus dari DOM setelah animasi
                }, 500); // sama dengan durasi transition
            }
        }, 3000); // 3000 ms = 3 detik
    </script>
@endsection
