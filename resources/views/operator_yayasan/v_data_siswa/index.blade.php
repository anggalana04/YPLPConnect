@extends('v_layouts.index')

@section('title', 'Data Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data_siswa/data-siswa.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
<div class="konten">
    <div class="box-konten">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Data Siswa</h1>
                <p>Lihat Dan Kelola Data Siswa Sekolah Anda</p>
            </div>
            <div class="option-button">
                <button class="upload-siswa">Upload Data Siswa</button>
                <button onclick="openPopUpForm()" class="tambah-siswa">Tambah Data Siswa</button>
            </div>
        </div>

        <div class="option-head-box">
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search Icon">
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
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa as $item)
                        <tr>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<div class="modal-pengaduan" id="PopUpForm" style="display:none;">
    <form method="POST" action="{{ route('siswa.store') }}">
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
                        <label for="npa">NIS</label>
                        <input type="text" id="nisn" name="nisn" required />
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required />
                    </div>
                    <div class="form-group">
                        <label for="ttl">Kelas</label>
                        <input type="text" id="kelas" name="kelas" placeholder="" required />
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" required />
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
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

@endsection

@push('scripts')
<script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
<script>
document.querySelectorAll('.batal').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('PopUpForm').style.display = 'none';
    });
});
</script>
@endpush
