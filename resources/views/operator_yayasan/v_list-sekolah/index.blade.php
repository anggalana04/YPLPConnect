@extends('v_layouts.index')

@section('title', 'List Sekolah')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/list-sekolah/list-sekolah.css') }}">
@endpush

@section('content')
<div class="konten">
    <div class="box-konten">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>List Sekolah</h1>
                <p>Lihat Dan Kelola Data Siswa Sekolah Anda</p>
            </div>
            <button>Tambah Sekolah</button>
        </div>

        <div class="option-head-box">
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search">
                </div>
                <input type="text" placeholder="Cari sekolah.." class="search-input">
            </div>
            <div class="kategori">
                <select id="kategori" name="kategori">
                    <option value="">Pilih Jenjang</option>
                    <option value="kelas1">TK</option>
                    <option value="kelas2">SD</option>
                    <option value="kelas3">SMP</option>
                    <option value="kelas4">SMA</option>
                </select>
            </div>
        </div>

        <div class="table-box">
            <table class="table-konten">
                <thead id="table-header">
                    <tr>
                        <th>NPSN</th>
                        <th>Nama Sekolah</th>
                        <th>Jenjang</th>
                        <th>Alamat</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sekolah as $s)
                    <tr class="clickable-row" data-href="{{ route('siswa.by-sekolah', ['npsn' => $s->npsn]) }}">
                        <td>{{ $s->npsn }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->jenjang }}</td>
                        <td>{{ $s->alamat }}</td>
                        <td>{{ $s->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rows = document.querySelectorAll(".clickable-row");
        rows.forEach(row => {
            row.addEventListener("click", function () {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endpush
