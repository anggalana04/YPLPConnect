@extends('v_layouts.index')

@section('title', 'User Manage')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/Operator Yayasan/User Manage/UserManage.css') }}">
@endpush

@section('content')
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

            <!-- Tabel User -->
            <div class="table-box">
                <table class="table-konten">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Sekolah</th>
                            <th>Alamat</th>
                            <th>No Hp</th>
                            <th>Aksi</th> <!-- Kolom aksi untuk tombol titik3 -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td class="editable" data-field="name">{{ $user->name }}</td>
                                <td class="editable" data-field="role">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                <td class="editable" data-field="sekolah_nama">{{ $user->sekolah->nama ?? '-' }}</td>
                                <td class="editable" data-field="sekolah_alamat">{{ $user->sekolah->alamat ?? '-' }}</td>
                                <td class="editable" data-field="no_hp">
                                    <span class="no-hp-text">{{ $user->no_hp ?? '-' }}</span>
                                </td>
                                <td class="aksi" style="position: relative; width: 80px;">
                                    <div class="aksi-wrapper">
                                        <!-- Tombol titik tiga -->
                                        <button class="dropdown-toggle" onclick="toggleDropdown(this)" style="background: none; border: none; padding: 0;">
                                            <img class="titik3" src="{{ asset('image/icon-User_Manage/icon-3_titik.svg') }}" alt="Menu" style="height: 15px;">
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div class="dropdown-menu" style="position: absolute; top: 0; right: 70%; display: none; background: white; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 10;">
                                            <a href="#" class="dropdown-item-edit" onclick="event.preventDefault(); enableRowEditing(this)">Edit</a>
                                            <a href="#" class="dropdown-item-hapus">Hapus</a>
                                        </div>

                                        <!-- Tombol simpan -->
                                        <button class="btn-simpan" onclick="saveRowEdit(this.closest('tr'))" style="display: none; margin-top: 5px; font-size: 12px; color: green;">Simpan</button>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination">
                    <!-- Pagination buttons akan dibuat otomatis lewat JS -->
                </ul>
            </nav>
        </div>
    </div>
    <!-- Modal Form Pengaduan (disembunyikan awalnya) -->
  <!-- Modal Form Tambah User (disembunyikan awalnya) -->
<div class="modal-pengaduan" id="PopUpForm" style="display: none;">
    <form action="/user/add" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf
        <div class="sub-head-box">
            <input
                type="text"
                name="name"
                placeholder="Nama Lengkap"
                required
            />
        </div>

        <div class="sub-form-box">
            <div class="isi-user">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                />
                <input
                    type="text"
                    name="no_hp"
                    placeholder="Nomor HP"
                    required
                />
                <select name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="operator_sekolah">Operator Sekolah</option>
                    <option value="operator_yayasan">Operator Yayasan</option>
                </select>
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                />
            </div>
        </div>
        <div class="all-button">
            <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>
            <button type="submit" class="kirim">Tambah User</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('JavaScript/Pagination.js') }}"></script>
    <script src="{{ asset('JavaScript/Edit_User/Edit_user.js') }}"></script>
    <script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
    <script>



        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

            // Tutup dropdown lain
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.style.display = 'none';
                }
            });
        }

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.dropdown-menu') || event.target.closest('button');
            if (!isClickInside) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });
    </script>

@endpush
