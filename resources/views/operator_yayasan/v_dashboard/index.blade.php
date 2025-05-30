@extends('v_layouts.index')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/Operator Yayasan/dashboard_yayasan.css') }}">
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
<div class="konten">
    <div class="konten-head @if(auth()->user()->role != 'operator_yayasan') konten-head-sekolah @endif">
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>
                Selamat datang! {{ auth()->user()->name }}
                @if(auth()->user()->role == 'operator_yayasan')
                    , Kamu adalah operator yayasan!
                @endif
            </h2>
        </div>
    </div>

    <div class="konten-body @if(auth()->user()->role == 'operator_yayasan') konten-body-yayasan @else konten-body-sekolah @endif">

        @if(auth()->user()->role == 'operator_yayasan')
            {{-- Konten untuk operator yayasan --}}
            <div class="card card-1">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p>{{ $jumlahGuru }}</p>
                </div>
            </div>

            <div class="card card-2">
                <h1 class="head-siswa">Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                    <p>{{ $jumlahSiswa }}</p>
                </div>
            </div>

            <div class="card card-3">
                <h1>Pengaduan</h1>
            </div>

            <div class="card card-4">
                <h1>Dokumen</h1>
            </div>

            <div class="card card-keuangan">
                <h1>Keuangan</h1>
            </div>

        @else
            {{-- Konten untuk operator sekolah --}}
            <div class="card card-sekolah">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahGuruPerTahun) }}</p>
                </div>
                <!-- Canvas untuk Chart.js -->
                <canvas id="chartGuru" style="height: 300px;"></canvas>
            </div>

            <div class="card card-sekolah">
                <h1 class="head-guru">Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahSiswaPerTahun) }}</p>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chartSiswa" style="height: 300px"></canvas>
                </div>
            </div>

            <div class="card card-sekolah">
                <h1>Keuangan</h1>
                <div class="keuangan-bar">
                    @php
                        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @foreach ($bulanList as $bulan)
                        @php
                            $item = $keuangan->firstWhere('bulan', $bulan);
                            $color = ($item && $item->status == 'Sudah Bayar') ? 'green' : 'red';
                        @endphp
                        <div class="bar-item" title="{{ $bulan }}" style="background-color: {{ $color }}"></div>
                    @endforeach
                </div>
            </div>

            <div class="card card-sekolah">
    <h1>Pengaduan</h1>

    @if ($pengaduans->isEmpty())
        <p class="No-data-pengaduan">Tidak ada pengaduan ditemukan.</p>
    @else
        <div class="pengaduan-scroll">
            @foreach ($pengaduans as $pengaduan)
                <div class="status-head">
                    <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                        <div class="judul-pengaduan">
                            Judul Pengaduan: {{ $pengaduan->judul }}
                        </div>
                        <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                    </span>
                    <div class="detail-status">
                        <span class="head-detail">Status Pengaduan</span>
                        @php
                            $statusSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                            $currentIndex = array_search($pengaduan->status, $statusSteps);
                        @endphp
                        <div class="box-status-step">
                            @foreach ($statusSteps as $index => $step)
                                <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}">
                                    <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                </div>
                                @if ($index < count($statusSteps) - 1)
                                    <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('JavaScript/JS_Data_pengaduan/data_pengaduan.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script src="{{ asset('JavaScript/Show_Data-Guru/Data_Guru_/Tahun.js') }}"></script>

{{-- SCRIPT UNTUK SISWA & GURU --}}
<script>
    window.guruData = @json($jumlahGuruPerTahun);
    window.siswaData = @json($jumlahSiswaPerTahun);
    window.tahunList = @json($tahunList);
</script>
@endpush
