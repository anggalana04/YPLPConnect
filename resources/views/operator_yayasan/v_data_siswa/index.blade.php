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

.tambah-siswa {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background: linear-gradient(90deg, #f6eb61 0%, #f9d423 100%);
    color: #000;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 220px;
    text-align: center;
}

.tambah-siswa:hover {
    background: linear-gradient(90deg, #f9d423 0%, #f6eb61 100%);
}

.modal-pengaduan {
    backdrop-filter: blur(8px);
}

.form-modal-blur {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            <div class="option-button" style="display:flex; flex-direction:column; gap:14px; align-items:center; justify-content:center; margin-top:18px;">
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" id="uploadForm" style="display:inline-block; width:100%;">
                    @csrf
                    <button type="button" class="upload-siswa" id="uploadDataButton" style="display:block; width:220px; margin:0 auto; padding:10px 20px; border:none; border-radius:5px; background:linear-gradient(90deg,#f6eb61 0%,#f9d423 100%); color:#000; font-weight:bold; cursor:pointer; transition:background 0.3s; text-align:center;">Upload Data Siswa</button>
                </form>
                <button type="button" class="tambah-siswa" onclick="openPopUpForm()" style="display:block; width:220px; margin:0 auto; padding:10px 20px; border:none; border-radius:5px; background:linear-gradient(90deg,#f6eb61 0%,#f9d423 100%); color:#000; font-weight:bold; cursor:pointer; transition:background 0.3s; text-align:center;">Tambah Data Siswa</button>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa as $item)
                        <tr data-nisn="{{ $item->nisn }}">
                            <td class="editable" data-field="nisn">{{ $item->nisn }}</td>
                            <td class="editable" data-field="nama">{{ $item->nama }}</td>
                            <td class="editable" data-field="jenis_kelamin">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="editable" data-field="ttl">{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                            <td class="editable" data-field="alamat">{{ $item->alamat }}</td>
                            <td class="editable" data-field="status">{{ $item->status }}</td>
                            <td class="editable" data-field="kelas" style="display:none;">{{ $item->kelas }}</td>
                            <td>
                                <form action="{{ route('siswa.destroy', $item->nisn) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="npsn" value="{{ $item->npsn }}">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</button>
                                </form>
                                <button type="button" class="btn-edit" onclick="enableRowEditSiswa(this)">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data siswa.</td>
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
    <form method="POST" action="{{ route('siswa.store') }}" class="form-box" style="padding:0; background:transparent; box-shadow:none;">
        @csrf
        <div class="form-modal-blur" style="background:rgba(255,255,255,0.97); border-radius:24px; padding:32px 32px 18px 32px; max-width:420px; margin:0 auto; box-shadow:0 8px 32px 0 rgba(67,233,123,0.12);">
            <div class="sub-head-box" style="margin-bottom:18px;">
                <h1 style="font-size:1.25rem; font-weight:600; margin-bottom:0;">Form Tambah Data Siswa</h1>
            </div>
            <div class="sub-form-box" style="background: #f7f7fa; border-radius: 18px; padding: 20px 18px 10px 18px; display: flex; flex-direction: column; gap: 16px; box-shadow:0 2px 8px 0 rgba(60,60,60,0.04);">
                <div>
                    <label for="nama" style="font-weight:600; font-size:0.97rem; margin-bottom:2px; display:block;">Nama</label>
                    <input type="text" id="nama" name="nama" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="nisn" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">NISN</label>
                    <input type="text" id="nisn" name="nisn" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="ttl" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Tempat, Tanggal Lahir</label>
                    <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="kelas" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Kelas</label>
                    <input type="text" id="kelas" name="kelas" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="alamat" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="jenis_kelamin" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="all-button">
                <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>
                <button type="submit" class="kirim">Kirim</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Edit Siswa -->
<div class="modal-pengaduan" id="EditSiswaModal" style="display:none;">
    <form method="POST" id="editSiswaForm">
        @csrf
        @method('PUT')
        <div class="form-box">
            <div class="sub-head-box">
                <h1>Edit Data Siswa</h1>
            </div>
            <div class="sub-form-box">
                <div class="border-form" style="display: flex; flex-wrap: wrap; gap: 16px;">
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_nisn">NISN</label>
                        <input type="text" id="edit_nisn" name="nisn" readonly style="background:#f5f5f5;" />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_nama">Nama</label>
                        <input type="text" id="edit_nama" name="nama" required />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_kelas">Kelas</label>
                        <input type="text" id="edit_kelas" name="kelas" required />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select id="edit_jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="edit_tempat_lahir" name="tempat_lahir" required />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required />
                    </div>
                    <div class="form-group" style="flex: 1 1 100%; min-width: 200px;">
                        <label for="edit_alamat">Alamat</label>
                        <input type="text" id="edit_alamat" name="alamat" required />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%; min-width: 200px;">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="all-button">
                <button type="button" class="batal" onclick="closeEditSiswaModal()">Batal</button>
                <button type="submit" class="kirim">Simpan</button>
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
        .then data => {
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

<script>
function openEditSiswaModal(item) {
    document.getElementById('EditSiswaModal').style.display = 'block';
    document.getElementById('edit_nisn').value = item.nisn;
    document.getElementById('edit_nama').value = item.nama;
    document.getElementById('edit_kelas').value = item.kelas;
    document.getElementById('edit_jenis_kelamin').value = item.jenis_kelamin;
    document.getElementById('edit_tempat_lahir').value = item.tempat_lahir;
    document.getElementById('edit_tanggal_lahir').value = item.tanggal_lahir;
    document.getElementById('edit_alamat').value = item.alamat;
    document.getElementById('edit_status').value = item.status;
    document.getElementById('editSiswaForm').action = '/siswa/' + item.nisn;
}
function closeEditSiswaModal() {
    document.getElementById('EditSiswaModal').style.display = 'none';
}
</script>

<script>
function enableRowEditSiswa(btn) {
    const tr = btn.closest('tr');
    if (tr.classList.contains('editing')) return;
    tr.classList.add('editing');
    // Save original values for cancel
    tr._original = Array.from(tr.children).map(td => td.innerHTML);
    // NISN (readonly)
    const nisn = tr.querySelector('[data-field="nisn"]').innerText;
    tr.querySelector('[data-field="nisn"]').innerHTML = `<input type='text' value='${nisn}' readonly style='background:#f5f5f5;width:90px;'>`;
    // Nama
    const nama = tr.querySelector('[data-field="nama"]').innerText;
    tr.querySelector('[data-field="nama"]').innerHTML = `<input type='text' value='${nama}' style='width:120px;'>`;
    // Jenis Kelamin
    const jenis_kelamin = tr.querySelector('[data-field="jenis_kelamin"]').innerText.trim() === 'Laki-laki' ? 'L' : 'P';
    tr.querySelector('[data-field="jenis_kelamin"]').innerHTML = `<select><option value='L' ${jenis_kelamin==='L'?'selected':''}>Laki-laki</option><option value='P' ${jenis_kelamin==='P'?'selected':''}>Perempuan</option></select>`;
    // Tempat, Tanggal Lahir
    const ttl = tr.querySelector('[data-field="ttl"]').innerText.split(',');
    const tempat = ttl[0].trim();
    const tanggal = ttl[1] ? ttl[1].trim().split(' ').reverse().join('-') : '';
    tr.querySelector('[data-field="ttl"]').innerHTML = `<input type='text' value='${tempat}' style='width:80px;' placeholder='Tempat'>, <input type='date' value='' style='width:120px;' placeholder='Tanggal'>`;
    // Try to parse date
    if (ttl[1]) {
        const date = new Date(ttl[1].trim());
        if (!isNaN(date)) {
            tr.querySelector('[data-field="ttl"] input[type=date]').value = date.toISOString().slice(0,10);
        }
    }
    // Alamat
    const alamat = tr.querySelector('[data-field="alamat"]').innerText;
    tr.querySelector('[data-field="alamat"]').innerHTML = `<input type='text' value='${alamat}' style='width:120px;'>`;
    // Status
    const status = tr.querySelector('[data-field="status"]').innerText;
    tr.querySelector('[data-field="status"]').innerHTML = `<select><option value='Aktif' ${status==='Aktif'?'selected':''}>Aktif</option><option value='Tidak Aktif' ${status==='Tidak Aktif'?'selected':''}>Tidak Aktif</option></select>`;
    // Kelas
    const kelas = tr.querySelector('[data-field="kelas"]').innerText;
    tr.querySelector('[data-field="kelas"]').innerHTML = `<input type='text' value='${kelas}' style='width:80px;'>`;
    // Action buttons
    const tdAksi = tr.children[tr.children.length-1];
    tdAksi._original = tdAksi.innerHTML;
    tdAksi.innerHTML = `<button type='button' class='btn-simpan' onclick='saveRowEditSiswa(this)'>Simpan</button> <button type='button' class='btn-cancel' onclick='cancelRowEditSiswa(this)'>X</button>`;
}
function cancelRowEditSiswa(btn) {
    const tr = btn.closest('tr');
    if (!tr._original) return;
    Array.from(tr.children).forEach((td, i) => {
        td.innerHTML = tr._original[i];
    });
    tr.classList.remove('editing');
    delete tr._original;
}
function saveRowEditSiswa(btn) {
    const tr = btn.closest('tr');
    const nisn = tr.querySelector('[data-field="nisn"] input').value;
    const nama = tr.querySelector('[data-field="nama"] input').value;
    const kelas = tr.querySelector('[data-field="kelas"] input').value;
    const jenis_kelamin = tr.querySelector('[data-field="jenis_kelamin"] select').value;
    const tempat = tr.querySelector('[data-field="ttl"] input[type=text]').value;
    const tanggal = tr.querySelector('[data-field="ttl"] input[type=date]').value;
    const alamat = tr.querySelector('[data-field="alamat"] input').value;
    const status = tr.querySelector('[data-field="status"] select').value;
    fetch(`/siswa/${nisn}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            _method: 'PUT',
            nisn,
            nama,
            kelas,
            jenis_kelamin,
            ttl: tempat + ', ' + tanggal,
            alamat,
            status
        })
    }).then(async res => {
        let data;
        try { data = await res.json(); } catch { data = {}; }
        if (res.ok && (data.success || res.status === 200)) {
            location.reload();
        } else if (data.errors && data.errors.ttl) {
            alert(data.errors.ttl[0]);
        } else {
            alert('Gagal update data!');
        }
    }).catch(() => alert('Gagal update data!'));
}
</script>
@endpush
