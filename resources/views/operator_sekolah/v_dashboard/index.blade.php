 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
    <title>Dashboard</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
       <div class="konten-head">
            <h1>Hallo...</h1>
            <h2>Selamat datang! {{ Auth::user()->name }}</h2>

            @if (Auth::user()->role == 'operator_yayasan')
                <h2>Kamu adalah operator yayasan!</h2>
            @endif
        </div>
        <div class="konten-body">
            <div class="card">
                <h1>Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p>xxx</p>
                </div>
            </div>
            <div class="card">
                <h1>Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                    <p>{{$jumlahSiswa}}</p>
                </div>
            </div>
            <div class="card">
                <h1>Keuangan</h1>
                <div class="keuangan-bar">
                    @php
                        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp

                    @foreach ($bulanList as $bulan)
                        @php
                            $item = $keuangan->firstWhere('bulan', $bulan);
                            $color = $item && $item->status == 'Sudah Bayar' ? 'green' : 'red';
                        @endphp
                        <div class="bar-item" title="{{ $bulan }}" style="background-color: {{ $color }}"></div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <h1>Pengaduan</h1>
                <div class="status-head">
                    <span>Judul Pengaduan:</span>
                    <div class="detail-status">
                        <span>Status Pengaduan</span>
                        <div class="box-status-step">
                            <div class="status-step">
                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-email-status.svg') }}" alt="">
                                <span>Terkirim</span>
                            </div>
                            <div class="status-step">
                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-diterima.svg') }}" alt="">
                                <span>Diterima & Dilihat</span>
                            </div>
                            <div class="status-step">
                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-proses.svg') }}" alt="">
                                <span>Diproses</span>
                            </div>
                            <div class="status-step">
                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-selesai.svg') }}" alt="">
                                <span>selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</body>
</html> 
