@extends('v_layouts.index')

@section('title', 'Data Keuangan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data_keuangan/DataKeuangan.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
<div class="konten">
    <div class="box-konten">
        <div class="head-box-konten">
            <div class="teks-head-box-konten">
                <h1>Data Keuangan</h1>
                <p>Lihat dan kelola data keuangan sekolah anda</p>
            </div>
        </div>

        <div class="option-box-konten {{ auth()->user()->role === 'operator_sekolah' ? 'gap-operator-sekolah' : 'gap-default' }}">
            <div class="kategori">
                <form id="filter-form" method="GET" action="{{ route('keuangan.index') }}">
                    @if(isset($sekolahList) && count($sekolahList))
                    <select name="npsn" onchange="this.form.submit()">
                        <option value="">Pilih Sekolah</option>
                        @foreach($sekolahList as $npsn => $nama)
                            <option value="{{ $npsn }}" {{ $npsn == ($npsnDipilih ?? '') ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @endif
                    <select id="kategori" name="tahun" onchange="this.form.submit()">
                        <option value="">Pilih Tahun</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="download">
                <button class="download-btn" type="button">Download Recap</button>
            </div>
        </div>

        <div class="bulan-wrapper">
            <div class="bulan-list">
                @php
                    $daftarBulan = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    $statusColor = [
                        'Menunggu' => 'kuning',
                        'Disetujui' => 'hijau',
                        'Ditolak'   => 'merah'
                    ];
                @endphp

                @foreach ($daftarBulan as $bulan)
                    @php
                        $data = $keuangan->firstWhere('bulan', $bulan);
                        $status = $data->status ?? 'Menunggu';
                        $color = $statusColor[$status] ?? 'kuning';
                    @endphp
                    <div class="bulan-item {{ $loop->iteration % 2 == 0 ? 'genap' : '' }}" @if($data) data-id="{{ $data->id }}" @endif>
                        <img src="{{ asset('image/icon-Data_Keuangan/icon-Plus.svg') }}" alt="Toggle Icon" class="icon-plus toggle-icon">
                        <span class="nama">{{ $bulan }}</span>
                        <button class="status status-{{ $color }}" type="button">
                            <img src="{{ asset('image/icon-Data_Keuangan/icon-download.svg') }}" alt="Download Icon">
                        </button>
                    </div>
                    <div class="detail-collapsible" style="max-height:0;overflow:hidden;transition:max-height 0.3s;">
                        <div class="detail-content">
                            <div class="detail-teks">
                                @if ($data)
                                    <p><strong>Detail:</strong> Rp 2.000 x {{ $jumlahSiswa }} Siswa</p>
                                    <p><strong>Total:</strong> Rp {{ number_format(2000 * $jumlahSiswa, 2, ',', '.') }}</p>
                                    @if ($data->catatan)
                                        <p><strong>Catatan:</strong> {{ $data->catatan }}</p>
                                    @endif
                                @else
                                    <p>Belum ada data keuangan bulan ini.</p>
                                @endif
                            </div>
                            <div class="upload-button-container">
                                @if (auth()->user()->role === 'operator_sekolah')
                                    <form action="{{ route('keuangan.upload', $data->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">
                                        <label for="uploadBukti{{ $bulan }}" class="upload-bukti-button">Upload Bukti</label>
                                        <input type="file" id="uploadBukti{{ $bulan }}" name="bukti" class="upload-input" accept="image/*,application/pdf">
                                        <button type="submit" class="upload-submit" style="display:none;">Submit</button>
                                    </form>
                                    <button class="bayar-button" data-bulan="{{ $bulan }}">Bayar</button>
                                @elseif (auth()->user()->role === 'operator_yayasan')
                                    <button class="cek-bukti-button" data-id="{{ $data->id ?? '' }}" data-bulan="{{ $bulan }}">Cek Bukti</button>
                                    <button class="sudah-bayar-button" data-bulan="{{ $bulan }}">Sudah Bayar</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Popup Rekening -->
<div id="popupRekening" class="popup-rekening" style="display:none;">
    <div class="popup-content">
        <h3>No Rekening</h3>
        <p>Bank ABC - 1234567890 a.n. Yayasan Pendidikan Contoh</p>
        <button onclick="tutupPopup()">Tutup</button>
    </div>
</div>

<!-- Popup Bukti Bayar -->
<div id="popupBukti" class="popup-bukti" style="display:none;">
    <div class="popup-content">
        <span class="close-popup" onclick="tutupPopupBukti()">&times;</span>
        <div id="buktiContainer"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const bulanItem = this.parentElement;
            const detail = bulanItem.nextElementSibling;
            if (!detail || !detail.classList.contains('detail-collapsible')) return;
            const isOpen = detail.style.maxHeight && detail.style.maxHeight !== '0px';
            if (isOpen) {
                detail.style.maxHeight = '0px';
                this.src = "{{ asset('image/icon-Data_Keuangan/icon-Plus.svg') }}";
            } else {
                detail.style.maxHeight = detail.scrollHeight + 'px';
                this.src = "{{ asset('image/icon-Data_Keuangan/icon-Minus.svg') }}";
            }
        });
    });

    document.querySelectorAll('.bayar-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            tampilkanPopup();
        });
    });

    document.querySelectorAll('.upload-input').forEach(input => {
        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                this.closest('form').querySelector('.upload-submit').style.display = 'inline-block';
            }
        });
    });

    document.querySelectorAll('.sudah-bayar-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const bulan = this.getAttribute('data-bulan');
            // Ambil id keuangan dari data bulan
            const keuanganRow = [...document.querySelectorAll('.bulan-list .bulan-item')].find(item => item.querySelector('.nama').textContent.trim() === bulan);
            if (!keuanganRow) return;
            // Ambil id dari data-id pada bulan-item (lebih baik tambahkan data-id di div bulan-item)
            const id = keuanganRow.getAttribute('data-id');
            if (!id) return alert('Data tidak ditemukan');

            if (confirm('Setujui pembayaran bulan ' + bulan + '?')) {
                fetch(`/keuangan/${id}/validasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: 'Disetujui' })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        alert('Status pembayaran disetujui!');
                        location.reload();
                    } else {
                        alert('Gagal validasi: ' + (data.message || ''));
                    }
                });
            }
        });
    });

    document.querySelectorAll('.cek-bukti-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            if (!id) return alert('Bukti belum diupload.');
            fetch(`/keuangan/${id}/bukti`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.url) {
                        const ext = data.url.split('.').pop().toLowerCase();
                        let html = '';
                        if (['jpg','jpeg','png','gif','bmp','webp'].includes(ext)) {
                            html = `<img src="${data.url}" alt="Bukti Bayar">`;
                        } else if (ext === 'pdf') {
                            html = `<embed src="${data.url}" type="application/pdf" width="600" height="800"/>`;
                        } else {
                            html = 'Format file tidak didukung.';
                        }
                        document.getElementById('buktiContainer').innerHTML = html;
                        document.getElementById('popupBukti').style.display = 'flex';
                    } else {
                        alert('Bukti tidak ditemukan!');
                    }
                });
        });
    });
});

function tampilkanPopup() {
    document.getElementById('popupRekening').style.display = 'flex';
}
function tutupPopup() {
    document.getElementById('popupRekening').style.display = 'none';
}
function tutupPopupBukti() {
    document.getElementById('popupBukti').style.display = 'none';
    document.getElementById('buktiContainer').innerHTML = '';
}
</script>
@endpush
