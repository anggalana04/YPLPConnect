<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/pengaduan/pengaduan.css') }}" />
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

                <button onclick="openPopUpForm()">Ajukan Pengaduan</button>
            </div>

            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="" />
                </div>
                <input type="text" placeholder="Cari Pengaduan..." class="search-input" />
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
                        @php
                            $data = [
                                ['id' => 1, 'judul' => 'Keluhan tentang pelayanan customer service', 'kode' => 'PID-2023-001', 'tanggal' => '10/05/2023', 'status' => 'Diproses'],
                                ['id' => 2, 'judul' => 'Laporan masalah teknis aplikasi', 'kode' => 'PID-2023-002', 'tanggal' => '11/05/2023', 'status' => 'Selesai'],
                                ['id' => 3, 'judul' => 'Permintaan fitur baru pada aplikasi', 'kode' => 'PID-2023-003', 'tanggal' => '12/05/2023', 'status' => 'Diproses'],
                                ['id' => 4, 'judul' => 'Keluhan akses login', 'kode' => 'PID-2023-004', 'tanggal' => '13/05/2023', 'status' => 'Selesai'],
                                ['id' => 5, 'judul' => 'Laporan bug halaman dashboard', 'kode' => 'PID-2023-005', 'tanggal' => '14/05/2023', 'status' => 'Diproses'],
                                ['id' => 6, 'judul' => 'Permintaan update tampilan UI', 'kode' => 'PID-2023-006', 'tanggal' => '15/05/2023', 'status' => 'Selesai'],
                                ['id' => 7, 'judul' => 'Masalah notifikasi email', 'kode' => 'PID-2023-007', 'tanggal' => '16/05/2023', 'status' => 'Diproses'],
                                ['id' => 8, 'judul' => 'Keluhan performa aplikasi', 'kode' => 'PID-2023-008', 'tanggal' => '17/05/2023', 'status' => 'Selesai'],
                                ['id' => 9, 'judul' => 'Permintaan backup data rutin', 'kode' => 'PID-2023-009', 'tanggal' => '18/05/2023', 'status' => 'Diproses'],
                                ['id' => 10, 'judul' => 'Laporan error pada modul pembayaran', 'kode' => 'PID-2023-010', 'tanggal' => '19/05/2023', 'status' => 'Selesai'],
                            ];
                        @endphp

                        @foreach($data as $row)
                            <tr class="clickable-row" data-id="{{ $row['id'] }}">
                                <td>{{ $row['id'] }}</td>
                                <td>{{ $row['judul'] }}</td>
                                <td>{{ $row['kode'] }}</td>
                                <td>{{ $row['tanggal'] }}</td>
                                <td>
                                    <span class="status {{ strtolower($row['status']) }}">
                                        {{ $row['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>

    <!-- Modal Form Pengaduan (Disembunyikan awalnya) -->
    <div class="modal-pengaduan" id="PopUpForm" style="display: none;">
        <div class="form-box">
            <div class="sub-head-box">
                <input type="text" placeholder="Judul Pengajuan Masalah" />
            </div>

            <div class="sub-form-box">
                <div class="isi-pengaduan">
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        cols="50"
                        placeholder="Tulis pengaduan Anda..."
                    ></textarea>

                    <!-- Tempat preview gambar -->
                    <div id="preview"></div>
                </div>

                <div class="button-pengaduan">
                    <div class="kategori">
                        <label for="kategori">Kategori Masalah</label>
                        <select id="kategori" name="kategori">
                            <option value="">Pilih Kategori</option>
                            <option value="kendala">Kendala Teknis</option>
                            <option value="pelayanan">Pelayanan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="all-button">
                        <button class="batal" onclick="closePopUpForm()">Batal</button>

                        <input
                            type="file"
                            id="buktiInput"
                            accept="image/*"
                            style="display: none"
                            onchange="previewImage(event)"
                        />
                        <button class="bukti" onclick="document.getElementById('buktiInput').click()">
                            Tambahkan Bukti
                        </button>

                        <button class="kirim">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('JavaScript/Pagination.js') }}"></script>
    <script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
    <script src="{{ asset('JavaScript/Preview/Preview.js') }}"></script>
    <script src="{{ asset('JavaScript/JS_Data_pengaduan/data_pengaduan.js') }}"></script>
</body>
</html>
