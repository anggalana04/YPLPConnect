@extends('v_layouts.index')

@section('title', 'Dokumen SK')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Dokumen/dokumen.css') }}" />
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
@endpush

@section('content')
<div class="konten">
    <div class="box-konten">

        <!-- Header Konten -->
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Dokumen SK</h1>
                <p>Mengajukan dan melihat file Surat Keputusan</p>
            </div>
            @if(auth()->user()->role === 'operator_sekolah' || auth()->user()->role === 'operator_yayasan')
                <button onclick="openPopUpForm()">Mengajukan SK</button>
            @endif

        </div>

        <!-- Search & Filter -->
        <form id="search-form" action="{{ route('dokumen.index') }}" method="GET">
            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{ asset('image/search/search.svg') }}" alt="" />
                    </div>
                    <input type="text" name="q" id="search-input" value="{{ request('q') }}" placeholder="Cari Dokumen" class="search-input" />
                </div>

                <div class="kategori">
                    <select name="kategori" id="kategori-select">
                        <option value="">Kategori SK</option>
                        <option value="SK Pengangkatan" {{ request('kategori') == 'SK Pengangkatan' ? 'selected' : '' }}>SK Pengangkatan</option>
                        <option value="SK Pensiun" {{ request('kategori') == 'SK Pensiun' ? 'selected' : '' }}>SK Pensiun</option>
                        <option value="SK Mutasi" {{ request('kategori') == 'SK Mutasi' ? 'selected' : '' }}>SK Mutasi</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Tabel Data -->
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
                <tbody id="dokumen-body">
                    @foreach ($dokumen as $row)
                        <tr class="clickable-row" data-id="{{ $row->id }}">
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nuptk }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->jenis_sk }}</td>
                            <td>
                                <span class="status-text">{{ $row->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<div class="modal-pengaduan" id="PopUpForm" style="display:none;">
    <form method="POST" action="{{ route('dokumen.store') }}">
        @csrf
        <div class="form-box">
            <div class="sub-head-box">
                <h1>Form Pengajuan Surat Keputusan</h1>
            </div>

            <div class="sub-form-box">
                <div class="border-form">
                    <div class="form-group">
                        <label for="guru">Pilih Guru</label>
                        <select id="nuptk" name="nuptk" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guruList as $guru)
                                <option value="{{ $guru->nuptk }}">{{ $guru->nama }} ({{ $guru->nuptk }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat Unit Kerja</label>
                        <input type="text" id="alamat" name="alamat" required />
                    </div>
                    <div class="form-group">
                        <label for="kategori">Jenis SK</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">-- Pilih Jenis SK --</option>
                            <option value="SK Pengangkatan">SK Pengangkatan</option>
                            <option value="SK Pensiun">SK Pensiun</option>
                            <option value="SK Mutasi">SK Mutasi</option>
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
<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
<script src="{{ asset('JavaScript/PopUpForm/PopUpform.js') }}"></script>

<script>
    // Tombol batal untuk menutup popup
    const batalButton = document.querySelector('.batal');
    const popup = document.querySelector('.modal-pengaduan');

    batalButton.addEventListener('click', function () {
        popup.style.display = 'none';
    });

    // Membuat setiap baris tabel bisa diklik untuk menuju detail
    function setRowClickListeners() {
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function (e) {
                if (e.target.tagName.toLowerCase() === 'select') return;
                const id = this.getAttribute('data-id');
                window.location.href = `/dokumen/detail/${id}`;
            });
        });
    }
    setRowClickListeners();

    {{-- SCRIPT AJAX --}}
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('search-form');
        const tableBody = document.getElementById('dokumen-body');

        // Fungsi untuk kirim AJAX dan update tabel
        function fetchData() {
            const q = form.querySelector('input[name="q"]').value;
            const kategori = form.querySelector('select[name="kategori"]').value;

            let url = '{{ route("dokumen.ajaxSearch") }}?';
            if (q) url += 'q=' + encodeURIComponent(q) + '&';
            if (kategori) url += 'kategori=' + encodeURIComponent(kategori);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = data.html || '<tr><td colspan="5" style="text-align:center;">Data tidak ditemukan</td></tr>';
                setRowClickListeners(); // Pasang ulang event klik setelah update tabel
            })
            .catch(err => {
                console.error('Error:', err);
            });
        }

        // Submit form dicegah supaya ajax jalan
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchData();
        });

        // Auto search saat input berubah
        form.querySelector('input[name="q"]').addEventListener('input', function() {
            fetchData();
        });

        // Auto search saat kategori berubah
        form.querySelector('select[name="kategori"]').addEventListener('change', function() {
            fetchData();
        });
    });
</script>


@endpush
