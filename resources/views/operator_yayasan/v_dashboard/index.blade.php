<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
    <title>Dashboard</title>
</head>
<body>
    @extends('operator_yayasan.v_layouts.index')

    <div class="konten">
        <div class="konten-head">
            <h3>Halooooo</h3>
            <h4>Selamat datang !{{ Auth::user()->name }}
                @if ( Auth::user()->role  == 'operator_yayasan')
                <h2>Kamu adalah operator yayasan!</h2>

                @endif
            </h4>

        </div>

        <div class="konten-body">
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
        </div>

    </div>
</body>
</html>
