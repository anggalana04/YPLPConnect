@extends('v_layouts.index')

@section('title', 'Data Guru')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Data_Guru/data_guru.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@if(session('success'))
    <style>
    #uploadSuccessNotification {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #28a745;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 9999;
        font-weight: bold;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        animation: fadeOut 4s forwards;
    }

    @keyframes fadeOut {
        0%   { opacity: 1; }
        80%  { opacity: 1; }
        100% { opacity: 0; display: none; }
    }
    </style>

    <div id="uploadSuccessNotification">
        {{ session('success') }}
    </div>
@endif



@section('content')
<div class="konten">
    <div class="box-konten {{ auth()->user()->role === 'operator_sekolah' ? 'sekolah' : (auth()->user()->role === 'operator_yayasan' ? 'yayasan' : '') }}">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Data Guru</h1>
                <p>Lihat Dan Kelola Data Guru Sekolah Anda</p>
            </div>
            <div class="option-button" style="display:flex; flex-direction:column; gap:14px; align-items:center; justify-content:center; margin-top:18px;">
                @if(auth()->user()->role === 'operator_sekolah')
                    <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" id="uploadGuruForm" style="display:inline-block; width:100%;">
                        @csrf
                        <button type="button" class="upload-guru" id="uploadGuruButton" style="display:block; width:220px; margin:0 auto; padding:10px 20px; border:none; border-radius:5px; background:linear-gradient(90deg,#f6eb61 0%,#f9d423 100%); color:#000; font-weight:bold; cursor:pointer; transition:background 0.3s; text-align:center;">Upload Data Guru</button>
                    </form>
                    <button type="button" class="tambah-guru" onclick="openPopUpForm()" style="display:block; width:220px; margin:0 auto; padding:10px 20px; border:none; border-radius:5px; background:linear-gradient(90deg,#f6eb61 0%,#f9d423 100%); color:#000; font-weight:bold; cursor:pointer; transition:background 0.3s; text-align:center;">Tambah Data Guru</button>
                @endif
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

        <div class="table-box {{ auth()->user()->role === 'operator_sekolah' ? 'sekolah' : (auth()->user()->role === 'operator_yayasan' ? 'yayasan' : '') }}">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $g)
                        <tr data-nuptk="{{ $g->nuptk }}">
                            <td class="editable" data-field="nuptk">{{ $g->nuptk }}</td>
                            <td class="editable" data-field="nama">{{ $g->nama }}</td>
                            <td class="editable" data-field="jenis_kelamin">{{ $g->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="editable" data-field="tanggal_lahir">{{ $g->tanggal_lahir }}</td>
                            <td class="editable" data-field="alamat">{{ $g->alamat }}</td>
                            <td class="editable" data-field="no_hp">{{ $g->no_hp }}</td>
                            <td class="editable" data-field="status">{{ $g->status }}</td>
                            <td>
                                <form action="{{ route('guru.destroy', $g->nuptk) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data guru ini?')">Hapus</button>
                                </form>
                                <button type="button" class="btn-edit" onclick="enableRowEditGuru(this)">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data guru.</td>
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
    <form method="POST" action="{{ route('guru.store') }}" class="form-box" style="padding:0; background:transparent; box-shadow:none;">
        @csrf
        <div class="form-modal-blur" style="background:rgba(255,255,255,0.97); border-radius:24px; padding:32px 32px 18px 32px; max-width:420px; margin:0 auto; box-shadow:0 8px 32px 0 rgba(67,233,123,0.12);">
            <div class="sub-head-box" style="margin-bottom:18px;">
                <h1 style="font-size:1.25rem; font-weight:600; margin-bottom:0;">Form Tambah Data Guru</h1>
            </div>
            <div class="sub-form-box" style="background: #f7f7fa; border-radius: 18px; padding: 20px 18px 10px 18px; display: flex; flex-direction: column; gap: 16px; box-shadow:0 2px 8px 0 rgba(60,60,60,0.04);">
                <div>
                    <label for="nama" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Nama</label>
                    <input type="text" id="nama" name="nama" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="nuptk" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">NUPTK</label>
                    <input type="text" id="nuptk" name="nuptk" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="ttl" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Tempat, Tanggal Lahir</label>
                    <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
                </div>
                <div>
                    <label for="no_hp" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">No HP</label>
                    <input type="text" id="no_hp" name="no_hp" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px;">
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
            <div class="all-button" style="display: flex; flex-direction:column; justify-content: center; align-items: center; gap: 10px; margin-top: 32px; width: 100%; background:transparent; padding-top: 18px;">
                <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>
                <button type="submit" class="kirim">Kirim</button>
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

{{-- JS UPLOAD FILE EXCEL --}}

<script>
document.getElementById('uploadGuruButton').addEventListener('click', function () {
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'file';
    input.accept = '.csv, .xlsx, .xls';
    input.style.display = 'none';

    const form = document.getElementById('uploadGuruForm');
    form.appendChild(input);

    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            form.submit();
        }
    });

    input.click();
});
</script>
{{-- JS UPLOAD FILE EXCEL --}}

<script>
function enableRowEditGuru(btn) {
    const tr = btn.closest('tr');
    if (tr.classList.contains('editing')) return;
    tr.classList.add('editing');
    tr._original = Array.from(tr.children).map(td => td.innerHTML);
    // NUPTK (readonly)
    const nuptk = tr.querySelector('[data-field="nuptk"]').innerText;
    tr.querySelector('[data-field="nuptk"]').innerHTML = `<input type='text' value='${nuptk}' readonly style='background:#f5f5f5;width:90px;'>`;
    // Nama
    const nama = tr.querySelector('[data-field="nama"]').innerText;
    tr.querySelector('[data-field="nama"]').innerHTML = `<input type='text' value='${nama}' style='width:120px;'>`;
    // Jenis Kelamin
    const jenis_kelamin = tr.querySelector('[data-field="jenis_kelamin"]').innerText.trim() === 'Laki-laki' ? 'L' : 'P';
    tr.querySelector('[data-field="jenis_kelamin"]').innerHTML = `<select><option value='L' ${jenis_kelamin==='L'?'selected':''}>Laki-laki</option><option value='P' ${jenis_kelamin==='P'?'selected':''}>Perempuan</option></select>`;
    // Tanggal Lahir (convert to yyyy-mm-dd if needed)
    let tanggal_lahir = tr.querySelector('[data-field="tanggal_lahir"]').innerText.trim();
    // If format is dd/mm/yyyy, convert to yyyy-mm-dd
    if (/^\d{2}\/\d{2}\/\d{4}$/.test(tanggal_lahir)) {
        const [d, m, y] = tanggal_lahir.split('/');
        tanggal_lahir = `${y}-${m}-${d}`;
    }
    tr.querySelector('[data-field="tanggal_lahir"]').innerHTML = `<input type='date' value='${tanggal_lahir}' style='width:120px;'>`;
    // Alamat
    const alamat = tr.querySelector('[data-field="alamat"]').innerText;
    tr.querySelector('[data-field="alamat"]').innerHTML = `<input type='text' value='${alamat}' style='width:120px;'>`;
    // No HP
    const no_hp = tr.querySelector('[data-field="no_hp"]').innerText;
    tr.querySelector('[data-field="no_hp"]').innerHTML = `<input type='text' value='${no_hp}' style='width:120px;'>`;
    // Status
    const status = tr.querySelector('[data-field="status"]').innerText;
    tr.querySelector('[data-field="status"]').innerHTML = `<select><option value='Aktif' ${status==='Aktif'?'selected':''}>Aktif</option><option value='Nonaktif' ${status==='Nonaktif'?'selected':''}>Nonaktif</option></select>`;
    // Action buttons
    const tdAksi = tr.children[tr.children.length-1];
    tdAksi._original = tdAksi.innerHTML;
    tdAksi.innerHTML = `<button type='button' class='btn-simpan' onclick='saveRowEditGuru(this)'>Simpan</button> <button type='button' class='btn-cancel' onclick='cancelRowEditGuru(this)'>X</button>`;
}
function cancelRowEditGuru(btn) {
    const tr = btn.closest('tr');
    if (!tr._original) return;
    Array.from(tr.children).forEach((td, i) => {
        td.innerHTML = tr._original[i];
    });
    tr.classList.remove('editing');
    delete tr._original;
}
function saveRowEditGuru(btn) {
    const tr = btn.closest('tr');
    const nuptk = tr.querySelector('[data-field="nuptk"] input').value;
    const nama = tr.querySelector('[data-field="nama"] input').value;
    const jenis_kelamin = tr.querySelector('[data-field="jenis_kelamin"] select').value;
    const tanggal_lahir = tr.querySelector('[data-field="tanggal_lahir"] input').value;
    const alamat = tr.querySelector('[data-field="alamat"] input').value;
    const no_hp = tr.querySelector('[data-field="no_hp"] input').value;
    const status = tr.querySelector('[data-field="status"] select').value;
    fetch(`/guru/${nuptk}`, {
        method: 'PUT', // changed from 'POST' to 'PUT'
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            nuptk,
            nama,
            jenis_kelamin,
            tanggal_lahir,
            alamat,
            no_hp,
            status
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
</script>
@endpush
