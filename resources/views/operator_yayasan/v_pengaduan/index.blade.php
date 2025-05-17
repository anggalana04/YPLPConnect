<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengaduan</title>

    <link rel="stylesheet" href="{{ asset('css/pengaduan/pengaduan.css') }}">
</head>
<body>
    @extends('operator_yayasan.v_layouts.index')

    <div class="konten">
        <div class="body-konten">

            <!-- Header -->
            <div class="head-body-konten">
                <div class="teks-body">
                    <h1>PENGADUAN</h1>
                    <p>Ajukan pengaduan jika sekolah anda mengalami masalah</p>
                </div>
                <button onclick="openPopUpForm()">Ajukan Pengaduan</button>
            </div>

            <!-- Search -->
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search Icon">
                </div>
                <input type="text" placeholder="Cari Pengaduan..." class="search-input">
            </div>

            <!-- Tabel Pengaduan -->
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
                        <tr onclick="window.location='{{ route('pengaduan.index') }}'" style="cursor:pointer;">
                            <td>1</td>
                            <td>Keluhan tentang pelayanan customer service</td>
                            <td>PID-2023-001</td>
                            <td>10/05/2023</td>
                            <td><span class="status diproses">Diproses</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Laporan masalah teknis aplikasi</td>
                            <td>PID-2023-002</td>
                            <td>11/05/2023</td>
                            <td><span class="status selesai">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Form Pengaduan (Disembunyikan awalnya) -->
    <div class="modal-pengaduan" id="PopUpForm">
        <div class="form-box">

            <!-- Judul Pengajuan -->
            <div class="sub-head-box">
                <input type="text" id="judul" name="judul" placeholder="Judul Pengajuan Masalah">
            </div>

            <div class="sub-form-box">
                <!-- Form Isi Pengaduan -->
                <div class="isi-pengaduan">
                    <div class="form-group">
                        <label for="judul">Judul Pengajuan</label>
                        <input type="text" id="judul" name="judul" placeholder="Masukkan judul">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Tulis pengaduan Anda..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori Masalah</label>
                        <select id="kategori" name="kategori">
                            <option value="">Pilih Kategori</option>
                            <option value="kendala">Kendala Teknis</option>
                            <option value="pelayanan">Pelayanan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="button-pengaduan">
                    <!-- Input Bukti (Hidden) -->
                    <input type="file" id="buktiInput" accept="image/*" style="display: none;" onchange="previewImage(event)">

                    <div class="all-button">
                        <button class="batal" onclick="closePopUpForm()">Batal</button>
                        <button class="bukti" onclick="document.getElementById('buktiInput').click()">Tambahkan Bukti</button>
                        <button class="kirim">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
    <script src="{{ asset('JavaScript/Preview/Preview.js') }}"></script>
</body>
</html>
