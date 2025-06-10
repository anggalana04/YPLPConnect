@extends('v_layouts.index')

@section('title', 'Pengaduan')

@push('styles')
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
<link rel="stylesheet" href="{{ asset('css/pengaduan/pengaduan.css') }}" />
@endpush

@section('content')
<div class="konten">
    <div class="body-konten">
        <div class="head-body-konten">
            <div class="teks-body">
                <h1>PENGADUAN</h1>
                <p>Ajukan pengaduan jika sekolah anda mengalami masalah</p>
            </div>
            @if(auth()->user()->role === 'operator_sekolah' || auth()->user()->role === 'operator_yayasan')
                <button onclick="openPopUpForm()">Ajukan Pengaduan</button>
            @endif
        </div>

        <div class="search-container">
            <div class="search-icon">
                <img src="{{ asset('image/search/search.svg') }}" alt="" />
            </div>
            <input type="text" placeholder="Cari Pengaduan..." class="search-input" />
        </div>

        <div class="table-box">
            <table class="table-konten">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Deskripsi Pengaduan</th>
                        <th>ID Pengaduan</th>
                        <th>Tanggal Pengaduan</th>
                        <th>Status</th>
                    </tr>
                </thead>
               <tbody>
                    @forelse ($data as $index => $row)
                        <tr class="clickable-row" data-id="{{ $row->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->judul }}</td>
                           <td>{{ $row->id }}</td>
                            <td>{{ $row->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="status {{ strtolower($row->status) }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pengaduan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- Modal Form Pengaduan (disembunyikan awalnya) -->
<div class="modal-pengaduan" id="PopUpForm" style="display: none;">
    <form action="/pengaduan/submit" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf
        <div class="sub-head-box">
            <input
                type="text"
                name="judul"
                placeholder="Judul Pengajuan Masalah"
                required
                minlength="15"
            />
        </div>

        <div class="sub-form-box">
            <div class="isi-pengaduan">
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    cols="50"
                    placeholder="Tulis pengaduan Anda..."
                    required
                ></textarea>

                <!-- Tempat preview gambar -->
                <div id="preview"></div>
            </div>

            <div class="button-pengaduan">
                <div class="kategori">
                    <label for="kategori">Kategori Masalah</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Kendala Teknis">Kendala Teknis</option>
                        <option value="Pelayanan">Pelayanan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="all-button">
                    <button type="button" class="batal" onclick="closePopUpForm()">Batal</button>

                    <input
                        type="file"
                        id="buktiInput"
                        name="bukti"
                        accept="image/*"
                        style="display: none"
                        onchange="previewImage(event)"
                    />
                    <button type="button" class="bukti" onclick="document.getElementById('buktiInput').click()">
                        Tambahkan Bukti
                    </button>

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
<script src="{{ asset('JavaScript/Preview/Preview.js') }}"></script>
<script src="{{ asset('JavaScript/JS_Data_pengaduan/data_pengaduan.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    const tableBody = document.querySelector('.table-konten tbody');

    searchInput.addEventListener('keyup', function () {
        const keyword = searchInput.value.trim();

        fetch(`/pengaduan/search?q=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    `;
                    return;
                }

                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.classList.add('clickable-row');
                    row.setAttribute('data-id', item.id);

                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.judul}</td>
                        <td>${item.id}</td>
                        <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                        <td>
                            <span class="status ${item.status.toLowerCase()}">
                                ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                            </span>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            });
    });

    // Optional: klik baris untuk redirect detail
    document.addEventListener('click', function (e) {
        // CEGAH redirect jika klik pada dropdown status
        if (e.target.classList.contains('status-dropdown')) return;
        if (e.target.closest('.clickable-row')) {
            const id = e.target.closest('.clickable-row').dataset.id;
            window.location.href = `/pengaduan/${id}`;
        }
    });

    // Cegah bubbling pada dropdown
    document.querySelectorAll('.status-dropdown').forEach(function(select) {
        select.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        select.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const status = this.value;
            fetch(`/pengaduan/${id}/status`, {
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
                    // Optional: reload page or update row
                    // location.reload();
                } else {
                    alert('Gagal mengubah status');
                }
            });
        });
    });
});
</script>
@endpush
