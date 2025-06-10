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
                <p>Lihat Dan Kelola Data Sekolah Anda</p>
            </div>
            <button type="button" onclick="openPopUpForm()" class="btn-tambah-sekolah">Tambah Sekolah</button>
        </div>

        <form method="GET" action="{{ route('sekolah.index') }}">
            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{ asset('image/search/search.svg') }}" alt="Search">
                    </div>
                    <input type="text" name="q" placeholder="Cari sekolah.." class="search-input" value="{{ request('q') }}">
                </div>

                <div class="kategori">
                    <select id="kategori" name="kategori">
                        <option value="">Pilih Jenjang</option>
                        <option value="TK" {{ request('kategori') == 'TK' ? 'selected' : '' }}>TK</option>
                        <option value="SD" {{ request('kategori') == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ request('kategori') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('kategori') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </div>

                <button type="submit" class="btn-search" style="display: none;"></button> <!-- jika ingin pakai enter saja -->
            </div>
        </form>

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
                <tbody id="result-table">
                    @forelse ($sekolah as $item)
                    <tr data-npsn="{{ $item->npsn }}">
                        <td>{{ $item->npsn }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jenjang }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->email }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Tidak ada data ditemukan.</td>
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
    <form method="POST" action="{{ route('sekolah.store') }}" class="form-box">
        @csrf
        <div class="form-modal-blur">
            <div class="sub-head-box">
                <h1>Form Penambahan Sekolah </h1>
            </div>
            <div class="sub-form-box" style="background: #f7f7fa; border-radius: 18px; padding: 24px 24px 12px 24px; display: flex; flex-direction: column; gap: 18px;">
                <div>
                    <label for="npsn" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">NPSN</label>
                    <input type="text" id="npsn" name="npsn" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px; margin-bottom:0;">
                </div>
                <div>
                    <label for="nama" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Nama Sekolah</label>
                    <input type="text" id="nama" name="nama" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px; margin-bottom:0;">
                </div>
                <div>
                    <label for="jenjang" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Jenjang</label>
                    <select id="jenjang" name="jenjang" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px; margin-bottom:0;">
                        <option value="">Pilih Jenjang</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                    </select>
                </div>
                <div>
                    <label for="alamat" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px; margin-bottom:0;">
                </div>
                <div>
                    <label for="email" style="font-weight:600; font-size:0.97rem; margin-bottom:4px; display:block;">Email</label>
                    <input type="email" id="email" name="email" required style="width:100%; border-radius:12px; border:1px solid #e0e0e0; padding:10px 14px; margin-bottom:0;">
                </div>
            </div>
            <div class="all-button" style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 28px; width: 100%;">
                <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>
                <button type="submit" class="kirim">Kirim</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
<script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.querySelectorAll('.batal').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('PopUpForm').style.display = 'none';
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const from = "{{ $from ?? '' }}";
    const rows = document.querySelectorAll("tbody#result-table tr[data-npsn]");

    rows.forEach(row => {
        row.addEventListener("click", function () {
            const npsn = this.dataset.npsn;
            if (from === 'siswa') {
                // Langsung ke route siswa.by-sekolah dengan npsn
                window.location.href = `/siswa/by-sekolah/${npsn}`;
            } else if (from) {
                // Untuk from lain, pakai pola ?npsn=...
                window.location.href = `/${from}?npsn=${npsn}`;
            } else {
                // Default fallback
                window.location.href = `/siswa/by-sekolah/${npsn}`;
            }
        });
    });
});

</script>

<script>
$(document).ready(function () {
    // Event listener: setiap ada input di search atau perubahan dropdown kategori
    $('.search-input, #kategori').on('input change', function () {
        let keyword = $('.search-input').val();
        let kategori = $('#kategori').val();

        $.ajax({
            url: "{{ route('sekolah.index') }}",  // route pencarian di controller
            method: "GET",
            data: {
                q: keyword,
                kategori: kategori
            },
            success: function (response) {
                // response.html adalah full halaman (view index),
                // kita ambil isi tbody #result-table dari response dan update tabel di halaman
                let html = $('<div>').html(response.html).find('#result-table').html();
                $('#result-table').html(html);
            },
            error: function () {
                alert('Terjadi kesalahan saat mencari data.');
            }
        });
    });
});
</script>

<script>
// Modal blur animation logic
function openPopUpForm() {
    const modal = document.getElementById('PopUpForm');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show-blur'), 10);
}
function closePopUpForm() {
    const modal = document.getElementById('PopUpForm');
    modal.classList.remove('show-blur');
    setTimeout(() => modal.style.display = 'none', 250);
}
</script>
@endpush
