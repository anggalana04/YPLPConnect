@extends('v_layouts.index')

@section('title', 'Data Guru')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Data_Guru/data_guru.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
<div class="konten">
    <div class="box-konten">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Data Guru</h1>
                <p>Lihat Dan Kelola Data Guru Sekolah Anda</p>
            </div>

            <div class="option-button">
                <button class="upload-guru">Upload Data Guru</button>
                <button onclick="openPopUpForm()" class="tambah-guru">Tambah Data Guru</button>
            </div>
        </div>

        <div class="option-head-box">
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search Icon">
                </div>
                <input type="text" placeholder="Cari Guru" class="search-input" id="searchInput">
            </div>
        </div>

        <div class="table-box">
            <table class="table-konten">
                <thead id="table-header">
                    <tr>
                        <th>NUPTK</th>
                        <th>Nama Guru</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>No hp</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $g)
                        <tr>
                            <td>{{ $g->nuptk }}</td>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $g->tanggal_lahir }}</td>
                            <td>{{ $g->alamat }}</td>
                            <td>{{ $g->no_hp }}</td>
                            <td>{{ $g->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Laravel pagination --}}
        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<div class="modal-pengaduan" id="PopUpForm" style="display:none;">
    <form method="POST" action="{{ route('guru.store') }}">
        @csrf
        <div class="form-box">
            <div class="sub-head-box">
                <h1>Form Penambahan Guru Baru</h1>
            </div>

            <div class="sub-form-box">
                <div class="border-form">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" required />
                    </div>
                    <div class="form-group">
                        <label for="npa">NUPTK</label>
                        <input type="text" id="nuptk" name="nuptk" required />
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required />
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No HP</label>
                        <input type="text" id="no_hp" name="no_hp" placeholder="" required />
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#searchInput').on('input', function () {
    let query = $(this).val();

    $.ajax({
        url: '{{ route("search.guru") }}',
        method: 'GET',
        data: { query: query },
        success: function (data) {
            let html = '';

            if (data.length > 0) {
                data.forEach(function (guru) {
                    html += `
                        <tr>
                            <td>${guru.nuptk}</td>
                            <td>${guru.nama}</td>
                            <td>${guru.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                            <td>${guru.tanggal_lahir}</td>
                            <td>${guru.alamat}</td>
                            <td>${guru.no_hp}</td>
                            <td>${guru.status}</td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="7" class="text-center">Tidak ada data guru.</td></tr>';
            }

            $('tbody').html(html);
        }
    });
});
</script>

<script>
document.querySelectorAll('.batal').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('PopUpForm').style.display = 'none';
    });
});
</script>

@endpush
