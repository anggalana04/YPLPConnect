    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('css/Operator Yayasan/dashboard_yayasan.css') }}">
        <title>Dashboard</title>
    </head>
    <body>
        @extends('v_layouts.index')

        <div class="konten">
        <div class="konten-head @if(auth()->user()->role != 'operator_yayasan') konten-head-sekolah @endif">
    @if(auth()->user()->role == 'operator_yayasan')
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>Selamat datang! {{ auth()->user()->name }}, </h2>
            <h2>Kamu adalah operator yayasan!</h2>
        </div>
    @else
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>Selamat datang! {{ auth()->user()->name }}</h2>
        </div>
    @endif
</div>

<div class="konten-body @if(auth()->user()->role != 'operator_yayasan') konten-body-sekolah @endif">
    @if(auth()->user()->role == 'operator_yayasan')
        <!-- Konten untuk operator yayasan -->
        <div class="card">
            <h1>Jumlah Guru</h1>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                <p>{{$jumlahGuru}}</p>
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
        </div>

        <div class="card">
            <h1>Pengaduan</h1>
        </div>

        <div class="card">
            <h1>Dokumen</h1>
        </div>

        <div class="card">
            <h1>Pengajuan</h1>
        </div>
    @else
        <!-- Konten untuk operator sekolah -->
        <div class="card">
            <h1>Jumlah Guru</h1>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                <p>yyy</p>
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
                        $bulanList = [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
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

                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan ditemukan.</p>
                @else
                    @foreach ($pengaduans as $pengaduan)
                        <div class="status-head">
                            <span
                                class="head toggle-status"
                                style="cursor: default; display: flex; align-items: center; width: 100%;"
                            >
                                <div class="judul-pengaduan">
                                    Judul Pengaduan: {{ $pengaduan->judul }}
                                </div>
                                <img
                                    src="{{ asset('image/icon-row/row.svg') }}"
                                    class="arrow-icon"
                                    alt="Toggle Arrow"
                                    style="cursor: pointer;"
                                />
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
                                            <img
                                                src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}"
                                                alt="{{ $step }}"
                                            />
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
                        </div>
                    @endforeach
                @endif
            </div>
    @endif
</div>


        </div>
    </body>
    </html>
