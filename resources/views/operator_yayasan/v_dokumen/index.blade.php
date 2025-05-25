<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/Dokumen/dokumen.css') }}" />
    <title>Dokumen</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="box-konten">

            <!-- Header Konten -->
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Dokumen SK</h1>
                    <p>Mengajukan dan melihat file Surat Keputusan</p>
                </div>
                <button onclick="openPopUpForm()">Mengajukan SK</button>
            </div>

            <!-- Search & Filter -->
            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{ asset('image/search/search.svg') }}" alt="" />
                    </div>
                    <input type="text" placeholder="Cari Siswa" class="search-input" />
                </div>

                <div class="kategori">
                    <select id="kategori" name="kategori">
                        <option value="">Kategori SK</option>
                        <option value="kelas1">SK Kepala Sekolah</option>
                        <option value="kelas2">SK Guru</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-box">
                <table class="table-konten">
                    <thead id="table-header">
                        <tr>
                            <th>ID Pengajuan</th>
                            <th>NPA PGRI</th>
                            <th>Nama</th>
                            <th>Jenis SK</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ambil semua data dokumen dari database
                            $data = \App\Models\Dokumen::all();
                        @endphp

                        @foreach ($data as $row)
                            <tr class="clickable-row" data-id="{{ $row->id }}">
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->nuptk }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->jenis_sk }}</td>
                                <td>{{ $row->status }}</td>
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

<div class="modal-pengaduan" id="PopUpForm" style="display:none;">
    <form method="POST" action="{{ route('dokumen.store') }}">
        @csrf
        <div class="form-box">
            <div class="sub-head-box">
                <h1>Form Pengajuan Surat Keputusan</h1>
            </div>

            <div class="sub-form-box">
                <div class="border-form">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" required />
                    </div>
                    <div class="form-group">
                        <label for="npa">NPA PGRI</label>
                        <input type="text" id="npa" name="npa" required />
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required />
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Unit Kerja</label>
                        <input type="text" id="alamat" name="alamat" required />
                    </div>
                    <div class="form-group">
                        <label for="kategori">Jenis SK</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">-- Pilih Jenis SK --</option>
                            <option value="SK Pengangkatan">SK Pengangkatan</option>
                            <option value="SK Pensiun">SK Pensiun</option>
                            <option value="SK Mutasi">SK Mutasi</option>
                        </select>

                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="all-button">
                    <button type="button" class="batal">Batal</button>
                    <button type="submit" class="kirim">Kirim</button>
                </div>
            </div>
        </div>
    </form>
</div>


    <!-- Script -->
    <script src="{{ asset('JavaScript/Pagination.js') }}"></script>
    <script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>

    <script>
        // Tombol batal untuk menutup popup
        const batalButton = document.querySelector('.batal');
        const popup = document.querySelector('.modal-pengaduan');

        batalButton.addEventListener('click', function () {
            popup.style.display = 'none';
        });

        // Membuat setiap baris tabel bisa diklik untuk menuju detail
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                window.location.href = `/dokumen/detail/${id}`;
            });
        });
    </script>
</body>
</html>
