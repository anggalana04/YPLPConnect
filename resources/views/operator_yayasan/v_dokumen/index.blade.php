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
            <button onclick="openPopUpForm()">Mengajukan SK</button>
        </div>

        <!-- Search & Filter -->
        <div class="option-head-box">
            <div class="search-container">
                <div class="search-icon">
                    <img src="{{ asset('image/search/search.svg') }}" alt="" />
                </div>
                <input type="text" id="search-input" placeholder="Cari Dokumen" class="search-input" />
            </div>

            <div class="kategori">
                <select id="kategori-select" name="kategori">
                    <option value="">Kategori SK</option>
                    <option value="SK Pengangkatan">SK Pengangkatan</option>
                    <option value="SK Pensiun">SK Pensiun</option>
                    <option value="SK Mutasi">SK Mutasi</option>
                </select>
            </div>
        </div>

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
                                @if(auth()->user()->role === 'operator_yayasan')
                                    <select class="status-dropdown" data-id="{{ $row->id }}">
                                        <option value="Menunggu" {{ $row->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="Diproses" {{ $row->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ $row->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                @else
                                    <span class="status-text">{{ $row->status }}</span>
                                @endif
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
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" required />
                    </div>
                    <div class="form-group">
                        <label for="npa">NPA PGRI</label>
                        <input type="text" id="npa" name="npa" required />
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" id="ttl" name="ttl" placeholder="Jakarta, 1990-01-01" required />
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
    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function (e) {
            // Prevent row click if the target is inside a select (dropdown)
            if (e.target.tagName.toLowerCase() === 'select') return;
            const id = this.getAttribute('data-id');
            window.location.href = `/dokumen/detail/${id}`;
        });
    });

    // Prevent row click when interacting with the dropdown
    document.querySelectorAll('.status-dropdown').forEach(function(select) {
        select.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        select.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const status = this.value;
            fetch(`/dokumen/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Status berhasil diubah!');
                } else {
                    alert('Gagal mengubah status');
                }
            });
        });
    });
</script>

{{-- SCRIPT AJAX --}}
<script>
    function fetchDokumen() {
        const query = document.getElementById('search-input').value;
        const kategori = document.getElementById('kategori-select').value;

        fetch(`/dokumen/ajax/search?q=${query}&kategori=${kategori}`)
            .then(response => response.json())
            .then data => {
                document.getElementById('dokumen-body').innerHTML = data.html;

                // Re-assign event untuk baris yang baru
                document.querySelectorAll('tr[data-id]').forEach(row => {
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        window.location.href = `/dokumen/detail/${id}`;
                    });
                });
            });
    }

    document.getElementById('search-input').addEventListener('input', fetchDokumen);
    document.getElementById('kategori-select').addEventListener('change', fetchDokumen);
</script>
@endpush
