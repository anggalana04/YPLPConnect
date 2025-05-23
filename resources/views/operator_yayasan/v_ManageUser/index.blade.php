<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Manage</title>

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/Operator Yayasan/User Manage/UserManage.css') }}">
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="body-konten">
            <!-- Header -->
            <div class="head-body-konten">
                <div class="teks-body">
                    <h1>Manage User</h1>
                    <p>Lihat Dan Kelola Data User</p>
                </div>
                <button onclick="openPopUpForm()">Tambah User</button>
            </div>

            <!-- Search -->
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="Search Icon">
                </div>
                <input type="text" placeholder="Cari User..." class="search-input">
            </div>

            <!-- Tabel Pengaduan -->
            <div class="table-box">
                <table class="table-konten">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Sekolah</th>
                            <th>Alamat</th>
                            <th>No Hp</th>
                        </tr>
                    </thead>
                    @php
                        $data = [
                            ['nama' => 'Ahmad Ramadhan', 'role' => 'Guru', 'sekolah' => 'SMA Negeri 1 Jakarta', 'alamat' => 'Jl. Merdeka No. 10', 'hp' => '081234567890'],
                            ['nama' => 'Siti Aminah', 'role' => 'Kepala Sekolah', 'sekolah' => 'SMP Negeri 2 Bandung', 'alamat' => 'Jl. Pahlawan No. 22', 'hp' => '082134567891'],
                            ['nama' => 'Rizky Hidayat', 'role' => 'Wakil Kepala', 'sekolah' => 'SMA Negeri 3 Surabaya', 'alamat' => 'Jl. Kartini No. 7', 'hp' => '083134567892'],
                            ['nama' => 'Lina Marlina', 'role' => 'Staff TU', 'sekolah' => 'SMK Negeri 4 Malang', 'alamat' => 'Jl. Mawar No. 12', 'hp' => '084134567893'],
                            ['nama' => 'Andi Saputra', 'role' => 'Guru', 'sekolah' => 'SMA Negeri 5 Medan', 'alamat' => 'Jl. Cempaka No. 15', 'hp' => '085234567894'],
                            ['nama' => 'Desi Rahmawati', 'role' => 'Kepala Sekolah', 'sekolah' => 'SMP Negeri 6 Semarang', 'alamat' => 'Jl. Teratai No. 8', 'hp' => '086234567895'],
                            ['nama' => 'Herman Wijaya', 'role' => 'Wakil Kepala', 'sekolah' => 'SMK Negeri 7 Yogyakarta', 'alamat' => 'Jl. Dahlia No. 21', 'hp' => '087234567896'],
                            ['nama' => 'Rina Anggraini', 'role' => 'Guru', 'sekolah' => 'SMA Negeri 8 Makassar', 'alamat' => 'Jl. Melati No. 4', 'hp' => '088234567897'],
                            ['nama' => 'Bagas Pratama', 'role' => 'Staff TU', 'sekolah' => 'SMP Negeri 9 Palembang', 'alamat' => 'Jl. Anggrek No. 2', 'hp' => '089234567898'],
                            ['nama' => 'Nur Aini', 'role' => 'Guru', 'sekolah' => 'SMA Negeri 10 Balikpapan', 'alamat' => 'Jl. Kenanga No. 17', 'hp' => '081234567899'],
                        ];
                    @endphp

                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d['nama'] }}</td>
                                <td>{{ $d['role'] }}</td>
                                <td>{{ $d['sekolah'] }}</td>
                                <td>{{ $d['alamat'] }}</td>
                                <td>{{ $d['hp'] }}
                                     <img src="{{ asset('image/icon-User_Manage/icon-3_titik.svg') }}" alt="">
                                </td>
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

    <!-- Script -->
    <script src="{{ asset('JavaScript/Pagination.js') }}"></script>
</body>
</html>
