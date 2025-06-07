@extends('v_layouts.index')

@section('title', 'Detail Dokumen')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Dokumen/detail_dokumen.css') }}" />
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
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
    <div class="box-konten">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Dokumen SK</h1>
            </div>
        </div>

        <label for="">Status</label>

        @php
            $allSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];

            $statusMapping = [
                'menunggu' => 'terkirim',
                'terkirim' => 'terkirim',
                'diterima' => 'diterima',
                'diproses' => 'diproses',
                'selesai'  => 'selesai',
            ];

            $dokumenStatus = strtolower($dokumen->status);
            $mappedStatus = $statusMapping[$dokumenStatus] ?? 'terkirim';

            $statusSteps = auth()->user()->role === 'operator_yayasan'
                ? array_slice($allSteps, 1)
                : $allSteps;

            $currentIndex = array_search($mappedStatus, $statusSteps);
        @endphp



        <div class="status-container {{ auth()->user()->role === 'operator_yayasan' ? 'yayasan' : '' }}">
            <div class="box-status-step">
                @foreach ($statusSteps as $index => $step)
                    <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                        <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                        <span>
                            {{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}
                        </span>
                    </div>

                    @if ($index < count($statusSteps) - 1)
                        <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="ket-detail">
            <p><strong>ID Pengajuan : </strong>{{ $dokumen->id }}</p>
            <p><strong>NPA PGRI : </strong>{{ $guru->nuptk }}</p>
            <p><strong>Asal Sekolah : </strong>{{ $sekolah->nama }}</p>
            <p><strong>Nama : </strong>{{ $dokumen->nama }}</p>
            <p><strong>Jenis SK : </strong>{{ $dokumen->jenis_sk }}</p>
            <p><strong>Alamat Kerja : </strong>{{ $dokumen->alamat_unit_kerja }}</p>
        </div>

        <div class="download action-buttons">
            @if(auth()->user()->role === 'operator_yayasan')
                @php
                    $status = strtolower($dokumen->status);
                    $statusOrder = ['terkirim', 'diterima', 'diproses', 'selesai'];
                    $currentIndex = array_search($status, $statusOrder);
                @endphp

                {{-- Tombol: Diterima --}}
                <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'diterima']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-download btn-diterima" {{ $status !== 'menunggu' ? 'disabled' : '' }}>
                        Diterima
                    </button>
                </form>

                {{-- Tombol: Diproses --}}
                <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'diproses']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-download btn-diproses" {{ $status !== 'diterima' ? 'disabled' : '' }}>
                        Diproses
                    </button>
                </form>

                {{-- Tombol: Selesai --}}
                <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'selesai']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-download btn-selesai" {{ $status !== 'diproses' ? 'disabled' : '' }}>
                        Selesai
                    </button>
                </form>
            @else
                @if(auth()->user()->role === 'operator_sekolah')
                    @if (strtolower($dokumen->status) === 'selesai')
                        <a href="{{ route('dokumen.download', $dokumen->id) }}" class="btn-download">Download</a>
                    @else
                        <button class="btn-download" disabled>Download</button>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Menambahkan nomor urut pada <p id="no"> - meskipun cuma 1 elemen di sini
    document.querySelectorAll('.ket-detail #no').forEach((p, i) => {
        p.innerHTML = '<strong>No :</strong> ' + (i + 1);
    });
</script>

<script>
    setTimeout(function() {
        const alert = document.querySelector('[style*="position: fixed"]');
        if (alert) alert.style.display = 'none';
    }, 3000); // 3 detik
</script>
@endpush
