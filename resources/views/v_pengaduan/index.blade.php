<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/pengaduan/pengaduan.css') }}">
    <title>Pengaduan</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="body-konten">
            <div class="head-body-konten">
                <div class="teks-body">
                    <h1>PENGADUAN</h1>
                    <p>Ajukan pengaduan jika sekolah anda mengalami masalah</p>
                </div>

                <button>Ajukan Pengaduan</button>
            </div>

            <div class="search-container">
                <div class="search-icon">
                    <img src="{{asset('image/search/search.svg') }}" alt="">
                </div>
                    <input type="text" placeholder="Cari Pengaduan..." class="search-input">
            </div>

            <div class="table-box">
                <table class="table-konten">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Deskripsi Pengaduan</th>
                            <th>ID Pengaduan</th>
                            <th>Tanggal Pengaduan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be added here dynamically -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>