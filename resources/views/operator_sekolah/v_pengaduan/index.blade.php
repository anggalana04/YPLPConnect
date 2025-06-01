{{-- <!DOCTYPE html>
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
                    <h1>PENGADUANnnnnnnnn</h1>
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
                        @forelse ($data as $index => $row)
                            <tr class="clickable-row" data-id="{{ $row->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->judul }}</td>
                               <td>{{ $row->id }}</td>
                                <td>{{ $row->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status {{ strtolower($row->status) }}">
                                        {{ ucfirst($row->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pengaduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>

    <!-- Modal Form Pengaduan (disembunyikan awalnya) -->
<div class="modal-pengaduan" id="PopUpForm" style="display: none;">
    <form action="/pengaduan/submit" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf
        <div class="sub-head-box">
            <input 
                type="text" 
                name="judul" 
                placeholder="Judul Pengajuan Masalah" 
                required
            />
        </div>

        <div class="sub-form-box">
            <div class="isi-pengaduan">
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    cols="50"
                    placeholder="Tulis pengaduan Anda..."
                    required
                ></textarea>

                <!-- Tempat preview gambar -->
                <div id="preview"></div>
            </div>

            <div class="button-pengaduan">
                <div class="kategori">
                    <label for="kategori">Kategori Masalah</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Kendala Teknis">Kendala Teknis</option>
                        <option value="Pelayanan">Pelayanan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="all-button">
                    <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>

                    <input
                        type="file"
                        id="buktiInput"
                        name="bukti"
                        accept="image/*"
                        style="display: none"
                        onchange="previewImage(event)"
                    />
                    <button type="button" class="bukti" onclick="document.getElementById('buktiInput').click()">
                        Tambahkan Bukti
                    </button>

                    <button type="submit" class="kirim">Kirim</button>
                </div>
            </div>
        </div>
    </form>
</div>


    <script src="{{ asset('JavaScript/Pagination.js') }}"></script>
    <script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
    <script src="{{ asset('JavaScript/Preview/Preview.js') }}"></script>
    <script src="{{ asset('JavaScript/JS_Data_pengaduan/data_pengaduan.js') }}"></script>
</body>
</html> --}}
