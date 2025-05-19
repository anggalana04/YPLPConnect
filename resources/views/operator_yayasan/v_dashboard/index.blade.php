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
        <div class="konten-head @if(Auth::user()->role != 'operator_yayasan') konten-head-sekolah @endif">
    @if(Auth::user()->role == 'operator_yayasan')
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>Selamat datang! {{ Auth::user()->name }}, </h2>
            <h2>Kamu adalah operator yayasan!</h2>
        </div>
    @else
        <h1>Halo...</h1>
        <div class="welcome">
            <h2>Selamat datang! {{ Auth::user()->name }}</h2>
        </div>
    @endif
</div>

<div class="konten-body @if(Auth::user()->role != 'operator_yayasan') konten-body-sekolah @endif">
    @if(Auth::user()->role == 'operator_yayasan')
        <!-- Konten untuk operator yayasan -->
        <div class="card">
            <span>Jumlah Guru</span>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                <p>xxx</p>
            </div>
        </div>

        <div class="card">
            <span>Jumlah Siswa</span>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                <p>xxx</p>
            </div>
        </div>

        <div class="card">
            <span>Notifikasi Keuangan</span>
        </div>

        <div class="card">
            <span>Notifikasi Pengaduan</span>
        </div>

        <div class="card">
            <span>Notifikasi Dokumen</span>
        </div>

        <div class="card">
            <span>Notifikasi Pengajuan</span>
        </div>
    @else
        <!-- Konten untuk operator sekolah -->
        <div class="card">
            <span>Jumlah Guru</span>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                <p>yyy</p>
            </div>
        </div>

        <div class="card">
            <span>Jumlah Siswa</span>
            <div class="detail-card">
                <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                <p>yyy</p>
            </div>
        </div>

        <div class="card">
            <span>Notifikasi Keuangan</span>
        </div>

        <div class="card">
            <span>Notifikasi Pengaduan</span>
        </div>
    @endif
</div>


        </div>
    </body>
    </html>
