<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/data_sekolah/data_sekolah.css') }}">
    <title>Data Sekolah</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="head-konten">
            <h1>DATA SEKOLAH</h1>
            <P>Lihat Dan kelola Data Siswa & Guru Sekolah Anda</P>
        </div>

        <div class="data-box">
            <div class="subdata-box">
                <a href="#" >
                    <div class="head-data-box">
                        <h1>Data Siswa</h1>
                    </div>
                </a>
            </div>

            <div class="subdata-box">
                <a href="#">
                    <div class="head-data-box">
                        <h1>Data Guru</h1>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>