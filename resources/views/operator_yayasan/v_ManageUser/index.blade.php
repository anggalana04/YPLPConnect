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
                                        <button class="dropdown-toggle" onclick="toggleDropdown(this)" style="background: none; border: none; padding: 0;">
                                            <img class="titik3" src="{{ asset('image/icon-User_Manage/icon-3_titik.svg') }}" alt="Menu" style="height: 15px;">
                                        </button>
                                        <div class="dropdown-menu" style="position: absolute; top: 0; right: 70%; display: none; background: white; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 10;">
                                            <a href="#" class="dropdown-item-edit" onclick="event.preventDefault(); enableRowEditing(this)">Edit</a>
                                            <a href="#" class="dropdown-item-hapus" onclick="event.preventDefault(); deleteUserRow(this)">Hapus</a>
                                        </div>
                                        <button class="btn-simpan" onclick="saveRowEdit(this.closest('tr'))" style="display: none; margin-top: 5px; font-size: 12px; color: white; background: linear-gradient(90deg,#4caf50,#43e97b); border-radius: 20px; padding: 5px 16px; border: none;">Simpan</button>
                                        <button class="btn-cancel" onclick="cancelRowEdit(this.closest('tr'))" style="display: none; margin-top: 5px; font-size: 12px; color: #333; background: #eee; border-radius: 20px; padding: 5px 16px; border: none;">Batal</button>
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
    <form action="{{ route('user.add') }}" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf
        <div class="sub-form-box" style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; background: #f7f7fa; border-radius: 18px; padding: 16px 8px;">
            <div style="flex:1 1 180px; min-width:150px;">
                <label for="name" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Nama Lengkap" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
            </div>
            <div style="flex:1 1 180px; min-width:150px;">
                <label for="email" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
            </div>
            <div style="flex:1 1 180px; min-width:150px;">
                <label for="no_hp" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Nomor HP</label>
                <input type="text" id="no_hp" name="no_hp" placeholder="Nomor HP" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
            </div>
            <div style="flex:1 1 180px; min-width:150px;">
                <label for="role" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Role</label>
                <select id="role" name="role" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
                    <option value="">Pilih Role</option>
                    <option value="operator_sekolah">Operator Sekolah</option>
                    <option value="operator_yayasan">Operator Yayasan</option>
                </select>
            </div>
            <div id="npsn-field" style="flex:1 1 180px; min-width:150px; display:none;">
                <label for="npsn" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Sekolah (NPSN)</label>
                <select id="npsn" name="npsn" style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
                    <option value="">Pilih Sekolah</option>
                    @foreach(App\Models\Sekolah::orderBy('nama')->get() as $sekolah)
                        <option value="{{ $sekolah->npsn }}">{{ $sekolah->nama }} ({{ $sekolah->npsn }})</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1 1 180px; min-width:150px;">
                <label for="password" style="font-weight:600; font-size:0.97rem; margin-bottom:2px;">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:8px 12px; margin-bottom:6px;">
            </div>
        </div>
        <div class="all-button" style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 28px; width: 100%;">
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

        function enableRowEditing(btn) {
            const tr = btn.closest('tr');
            if (tr.classList.contains('editing')) return;
            tr.classList.add('editing');
            tr._original = Array.from(tr.children).map(td => td.innerHTML);
            // Name
            const name = tr.querySelector('[data-field="name"]').innerText;
            tr.querySelector('[data-field="name"]').innerHTML = `<input type='text' value='${name}' style='width:120px; border-radius:12px; padding:4px;'>`;
            // Role
            const role = tr.querySelector('[data-field="role"]').innerText.trim().toLowerCase().replace(' ', '_');
            tr.querySelector('[data-field="role"]').innerHTML = `<select style='border-radius:12px; padding:4px;'><option value='operator_sekolah' ${role==='operator_sekolah'?'selected':''}>Operator Sekolah</option><option value='operator_yayasan' ${role==='operator_yayasan'?'selected':''}>Operator Yayasan</option></select>`;
            // Sekolah Nama
            const sekolah_nama = tr.querySelector('[data-field="sekolah_nama"]').innerText;
            tr.querySelector('[data-field="sekolah_nama"]').innerHTML = `<input type='text' value='${sekolah_nama}' style='width:120px; border-radius:12px; padding:4px;'>`;
            // Sekolah Alamat
            const sekolah_alamat = tr.querySelector('[data-field="sekolah_alamat"]').innerText;
            tr.querySelector('[data-field="sekolah_alamat"]').innerHTML = `<input type='text' value='${sekolah_alamat}' style='width:120px; border-radius:12px; padding:4px;'>`;
            // No HP
            const no_hp = tr.querySelector('[data-field="no_hp"] .no-hp-text').innerText;
            tr.querySelector('[data-field="no_hp"]').innerHTML = `<input type='text' value='${no_hp}' style='width:120px; border-radius:12px; padding:4px;'>`;
            // Show Simpan & Batal
            tr.querySelector('.btn-simpan').style.display = 'inline-block';
            tr.querySelector('.btn-cancel').style.display = 'inline-block';
        }
        function cancelRowEdit(tr) {
            if (!tr._original) return;
            Array.from(tr.children).forEach((td, i) => {
                td.innerHTML = tr._original[i];
            });
            tr.classList.remove('editing');
            delete tr._original;
        }
        function saveRowEdit(tr) {
            const id = tr.getAttribute('data-id');
            const name = tr.querySelector('[data-field="name"] input').value;
            const role = tr.querySelector('[data-field="role"] select').value;
            const sekolah_nama = tr.querySelector('[data-field="sekolah_nama"] input').value;
            const sekolah_alamat = tr.querySelector('[data-field="sekolah_alamat"] input').value;
            const no_hp = tr.querySelector('[data-field="no_hp"] input').value;
            fetch(`/user-manage/update-inline/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name,
                    role,
                    sekolah_nama,
                    sekolah_alamat,
                    no_hp
                })
            }).then(async res => {
                let data;
                try { data = await res.json(); } catch { data = {}; }
                if (res.ok && (data.success || res.status === 200)) {
                    location.reload();
                } else if (res.status === 422 && data.errors) {
                    alert('Validasi gagal:\n' + Object.values(data.errors).join('\n'));
                } else {
                    alert('Gagal update data!');
                }
            }).catch(() => alert('Gagal update data!'));
        }
        function deleteUserRow(btn) {
            const tr = btn.closest('tr');
            const id = tr.getAttribute('data-id');
            if (!confirm('Yakin ingin menghapus user ini?')) return;
            fetch(`/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(async res => {
                let data;
                try { data = await res.json(); } catch { data = {}; }
                if (res.ok && (data.success || res.status === 200)) {
                    tr.remove();
                } else {
                    alert('Gagal hapus user!');
                }
            }).catch(() => alert('Gagal hapus user!'));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const npsnField = document.getElementById('npsn-field');
            const formBox = document.querySelector('.modal-pengaduan .form-box');
            roleSelect.addEventListener('change', function() {
                if (this.value === 'operator_sekolah') {
                    npsnField.style.display = '';
                    npsnField.querySelector('select').required = true;
                    formBox.style.minHeight = '540px'; // expand modal height
                    formBox.style.height = 'auto';
                } else {
                    npsnField.style.display = 'none';
                    npsnField.querySelector('select').required = false;
                    npsnField.querySelector('select').value = '';
                    formBox.style.minHeight = '';
                    formBox.style.height = '';
                }
            });
        });

        // Modal animation logic
        function openPopUpForm() {
            const modal = document.getElementById('PopUpForm');
            modal.classList.add('show');
            modal.style.display = 'flex';
        }
        function closePopUpForm() {
            const modal = document.getElementById('PopUpForm');
            modal.classList.remove('show');
            // Animate out (optional: fade out)
            modal.style.display = 'none';
        }
    </script>

@endpush
