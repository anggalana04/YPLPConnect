<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/Dokumen/dokumen.css') }}">
    <title>Dokumen</title>
</head>
<body>
    @extends('operator_yayasan.v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Dokumen SK</h1>
                    <p>Mengajukan dan melihat file Surat Keputusan</p>
                </div>

                <button>Mengajukan SK</button>
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
                            <option value="">Kategori SK</option>
                            <option value="kelas1">SK Kepala Sekolah</option>
                            <option value="kelas2">SK Guru</option>
                        </select>
                </div>
            </div>
            
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
                            $data = [
                            ['PGJ001', '10123456', 'Ahmad Ramadhan', 'SK Pengangkatan', 'Disetujui'],
                            ['PGJ002', '10123457', 'Siti Aminah', 'SK Pensiun', 'Diproses'],
                            ['PGJ003', '10123458', 'Rizky Hidayat', 'SK Mutasi', 'Ditolak'],
                            ['PGJ004', '10123459', 'Nur Aini', 'SK Kenaikan Pangkat', 'Disetujui'],
                            ['PGJ005', '10123460', 'Bagas Pratama', 'SK Pengangkatan', 'Diproses'],
                            ['PGJ006', '10123461', 'Lina Marlina', 'SK Pensiun', 'Disetujui'],
                            ['PGJ007', '10123462', 'Andi Saputra', 'SK Mutasi', 'Diproses'],
                            ['PGJ008', '10123463', 'Desi Rahmawati', 'SK Pengangkatan', 'Ditolak'],
                            ['PGJ009', '10123464', 'Herman Wijaya', 'SK Kenaikan Pangkat', 'Disetujui'],
                            ['PGJ010', '10123465', 'Rina Anggraini', 'SK Mutasi', 'Diproses'],
                            ];
                        @endphp

                        @foreach ($data as $row)
                            <tr class="clickable-row" data-id="{{ $row[0] }}">
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td>{{ $row[4] }}</td>
                            </tr>
                        @endforeach
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
        row.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            window.location.href = `/detail_dokumen/${id}`;
        });
    });
});
</script>

</html>