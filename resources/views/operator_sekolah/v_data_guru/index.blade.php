<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/Data_Guru/data_guru.css') }}">
    <title>Data Guru</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Data Guru</h1>
                    <p>Lihat Dan Kelola Data Guru Sekolah Anda</p>
                </div>

                <button>Upload Data Guru</button>
            </div>

            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{asset('image/search/search.svg') }}" alt="">
                    </div>
                    <input type="text" placeholder="Cari Siswa" class="search-input">
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
                                <td>
                                    @if($g->jenis_kelamin === 'L')
                                        Laki-laki
                                    @else
                                        Perempuan
                                    @endif
                                </td>
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
            <div style="margin-top: 1rem;">
                {{ $guru->links() }}
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
