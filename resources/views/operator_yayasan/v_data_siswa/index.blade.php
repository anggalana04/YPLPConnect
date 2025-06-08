@extends('v_layouts.index')

@section('title', 'Data Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data_siswa/data-siswa.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">

<style>
.fixed-top-center {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    max-width: 400px;
    width: auto;
    text-align: center;
    padding: 12px 24px;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 9999;
    font-weight: 600;
    color: #fff;
}

.alert-success.fixed-top-center {
    background-color: #28a745;
}

.alert-danger.fixed-top-center {
    background-color: #dc3545;
}
</style>
@endpush

@if (session('success')) 
    <div class="alert alert-success fixed-top-center" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fixed-top-center" role="alert">
        {{ session('error') }}
    </div>
@endif


@section('content')
<div class="konten">
    <div class="box-konten {{ auth()->user()->role === 'operator_sekolah' ? 'sekolah' : (auth()->user()->role === 'operator_yayasan' ? 'yayasan' : '') }}">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Data Siswa</h1>
                <p>Lihat Dan Kelola Data Siswa Sekolah Anda</p>
            </div>
            <div class="option-button">
                @if(auth()->user()->role === 'operator_sekolah')
                    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" id="uploadForm" style="display:inline-block;">
                        @csrf
                        <button type="button" class="upload-siswa" id="uploadDataButton">Upload Data Siswa</button>
                    </form>

                    <button onclick="openPopUpForm()" class="tambah-siswa">Tambah Data Siswa</button>
                @endif
            </div>
        </div>

        <div class="option-head-box">
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search Icon">
                </div>
                <input type="text" placeholder="Cari Siswa" class="search-input" id="searchInputAjax">
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

// Auto hide alert setelah 3 detik
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 3000);
</script>

{{-- JS UPLOAD DATA SISWA BUTTON --}}
<script>
document.getElementById('uploadDataButton').addEventListener('click', function () {
    // Buat input file secara dinamis
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'file';
    input.accept = '.csv, .xlsx, .xls';
    input.style.display = 'none';

    // Tambahkan input ke form
    const form = document.getElementById('uploadForm');
    form.appendChild(input);

    // Saat file dipilih, submit form
    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            form.submit();
        }
    });

    // Trigger file picker
    input.click();
});
</script>
{{-- JS UPLOAD DATA SISWA BUTTON --}}

{{-- AJAX UNTUK SEARCH --}}
<script>
document.getElementById('searchInputAjax').addEventListener('keyup', function () {
    let keyword = this.value;

    fetch(`/siswa/search?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector(".table-konten tbody");
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada hasil ditemukan.</td></tr>`;
                return;
            }

            data.data.forEach(item => {
                let jenis_kelamin = item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                let tanggal_lahir = new Date(item.tanggal_lahir);
                let tanggalFormatted = tanggal_lahir.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });

                tbody.innerHTML += `
                    <tr>
                        <td>${item.nisn}</td>
                        <td>${item.nama}</td>
                        <td>${jenis_kelamin}</td>
                        <td>${item.tempat_lahir}, ${tanggalFormatted}</td>
                        <td>${item.alamat ?? '-'}</td>
                        <td>${item.status ?? '-'}</td>
                    </tr>
                `;
            });
        });
});
</script>
{{-- AJAX UNTUK SEARCH --}}

{{-- JS UNTUK CLOSE NOTIF --}}
<script>
    window.onload = function() {
        const alertBox = document.querySelector('.alert');
        if (alertBox) {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert.fixed-top-center');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);
        }
    };
</script>

{{-- JS UNTUK CLOSE NOTIF --}}

@endpush
