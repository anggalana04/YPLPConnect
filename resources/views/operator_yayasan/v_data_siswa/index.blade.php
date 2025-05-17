<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/data_siswa/data-siswa.css') }}">
    <title>Data Siswa</title>
</head>
<body>
    @extends('operator_yayasan.v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Data Siswa</h1>
                    <p>Lihat Dan Kelola Data Siswa Sekolah Anda</p>
                </div>

                <button>Upload Data Siswa</button>
            </div>

            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{asset('image/search/search.svg') }}" alt="">
                    </div>
                    <input type="text" placeholder="Cari Siswa" class="search-input">
                </div>

                <div class="kategori">
                        <select id="kategori" name="kategori">
                            <option value="">Kategori Kelas</option>
                            <option value="kelas1">Kelas 1</option>
                            <option value="kelas2">Kelas 2</option>
                            <option value="kelas3">Kelas 3</option>
                        </select>
                </div>
            </div>
            
            <div class="table-box">
                <table class="table-konten">
                    <thead id="table-header">
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                            <td>123456</td>
                            <td>Ahmad Ramadhan</td>
                            <td>Laki-laki</td>
                            <td>Bandung, 12 Mei 2008</td>
                            <td>Jl. Merdeka No. 45, Bandung</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>234567</td>
                            <td>Siti Aminah</td>
                            <td>Perempuan</td>
                            <td>Jakarta, 5 Juli 2009</td>
                            <td>Jl. Sudirman No. 10, Jakarta</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>345678</td>
                            <td>Rizky Hidayat</td>
                            <td>Laki-laki</td>
                            <td>Surabaya, 23 Januari 2007</td>
                            <td>Jl. Diponegoro No. 8, Surabaya</td>
                            <td>Nonaktif</td>
                        </tr>
                        <tr>
                            <td>456789</td>
                            <td>Nur Aini</td>
                            <td>Perempuan</td>
                            <td>Yogyakarta, 30 Maret 2008</td>
                            <td>Jl. Kaliurang Km 7, Yogyakarta</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>567890</td>
                            <td>Bagas Pratama</td>
                            <td>Laki-laki</td>
                            <td>Semarang, 18 Oktober 2009</td>
                            <td>Jl. Pemuda No. 99, Semarang</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>567890</td>
                            <td>Bagas Pratama</td>
                            <td>Laki-laki</td>
                            <td>Semarang, 18 Oktober 2009</td>
                            <td>Jl. Pemuda No. 99, Semarang</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>567890</td>
                            <td>Bagas Pratama</td>
                            <td>Laki-laki</td>
                            <td>Semarang, 18 Oktober 2009</td>
                            <td>Jl. Pemuda No. 99, Semarang</td>
                            <td>Aktif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination">
                    <!-- Pagination buttons akan dibuat otomatis lewat JS -->
                </ul>
            </nav>

        </div>
    </div>
</body>
<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
</html>