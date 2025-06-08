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
                'ditolak'  => 'selesai', // treat 'ditolak' as 'selesai' for the indicator
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

        {{-- Tombol aksi hanya untuk operator yayasan --}}
        @if(auth()->user()->role === 'operator_yayasan')
        <div class="download action-buttons">
            @php
                $status = strtolower($dokumen->status);
            @endphp

            {{-- Tombol Diproses --}}
            <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'Diproses']) }}" method="POST" class="form-action">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-diproses" {{ $status !== 'menunggu' ? 'disabled' : '' }}>Diproses</button>
            </form>

            {{-- Tombol Diterima dan Ditolak --}}
            <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'Selesai']) }}" method="POST" class="form-action">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-diterima" {{ $status !== 'diproses' ? 'disabled' : '' }}>Diterima</button>
            </form>
            <form action="{{ route('dokumen.updateStatus', [$dokumen->id, 'Ditolak']) }}" method="POST" class="form-action">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-ditolak" {{ $status !== 'diproses' ? 'disabled' : '' }}>Ditolak</button>
            </form>
        </div>
        @endif

        {{-- Show status result --}}
        @if($dokumen->status === 'Selesai')
            <span class="status-label status-success">Disetujui</span>
        @elseif($dokumen->status === 'Ditolak')
            <span class="status-label status-reject">Ditolak</span>
        @endif

        {{-- Tombol download untuk operator sekolah & yayasan --}}
        @if($dokumen->status === 'Selesai' && $dokumen->file_path && (auth()->user()->role === 'operator_sekolah' || auth()->user()->role === 'operator_yayasan'))
            <a href="{{ route('dokumen.download', $dokumen->id) }}" class="btn-download" target="_blank">Download SK PDF</a>
        @endif
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
