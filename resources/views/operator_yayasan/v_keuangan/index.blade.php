@extends('v_layouts.index')

@section('title', 'Data Keuangan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data_keuangan/DataKeuangan.css') }}">
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@if (session('success'))
    <div id="notif-success" style="
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    ">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="notif-error" style="
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #f44336;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    ">
        {{ session('error') }}
    </div>
@endif

@section('content')
    @php
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $statusColor = [
            'Menunggu' => 'kuning',
            'Disetujui' => 'hijau',
            'Ditolak'   => 'merah',
            'default'   => 'kuning',
        ];
        $keuanganData = collect($keuangan ?? []);
        $allApproved = $keuanganData->count() === count($bulanList) && $keuanganData->every(function($item) {
            return ($item->status ?? null) === 'Disetujui';
        });
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');
        $isCurrentYear = ($tahunDipilih == $currentYear);
    @endphp

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Data Keuangan</h1>
                    <p>Lihat dan kelola data keuangan sekolah anda</p>
                </div>
            </div>

            <div class="option-box-konten
                {{ auth()->user()->role === 'operator_sekolah' ? 'gap-operator-sekolah' : '' }}
                {{ auth()->user()->role === 'operator_yayasan' ? 'gap-operator-yayasan' : '' }}
            ">
                <div class="kategori">
                    <form id="filter-form" method="GET" action="{{ route('keuangan.index') }}">
                        @if(isset($sekolahList) && count($sekolahList))
                            <select name="npsn" onchange="this.form.submit()">
                                <option value="">Pilih Sekolah</option>
                                @foreach($sekolahList as $npsn => $nama)
                                    <option value="{{ $npsn }}" {{ $npsn == ($npsnDipilih ?? '') ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        <select id="kategori" name="tahun" onchange="this.form.submit()">
                            <option value="">Pilih Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="download">
                    <form method="GET" action="{{ route('keuangan.download.recap') }}">
                        <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">
                        <button type="submit"
                            class="download-btn
                            {{ auth()->user()->role === 'operator_sekolah' ? 'download-sekolah' : '' }}
                            {{ auth()->user()->role === 'operator_yayasan' ? 'download-yayasan' : '' }}"
                            @if(!$allApproved) disabled style="background: #ccc; cursor: not-allowed;" title="Lengkapi verifikasi semua bulan untuk download recap" @endif>
                            Download Recap
                        </button>
                    </form>
                </div>
            </div>

            <div class="bulan-wrapper">
                <div class="bulan-list">
                    @foreach ($bulanList as $i => $bulan)
                        @php
                            $data = $keuanganData->first(fn($d) => ($d->bulan ?? null) === $bulan);
                            $status = $data->status ?? 'Menunggu';
                            $color = $statusColor[$status] ?? $statusColor['default'];
                            $hasBukti = $data && $data->bukti_path;
                            $isUpcoming = $isCurrentYear && ($i + 1) > $currentMonth;
                        @endphp

                        <div class="bulan-item {{ $loop->iteration % 2 == 0 ? 'genap' : '' }} {{ $isUpcoming ? 'bulan-upcoming' : '' }}" @if($isUpcoming) style="opacity:0.5;pointer-events:none;cursor:not-allowed;" @endif>
                            <img src="{{ asset('image/icon-Data_Keuangan/icon-Plus.svg') }}" alt="Toggle Icon" class="icon-plus toggle-icon" @if($isUpcoming) style="pointer-events:none;filter:grayscale(1);" @endif>
                            <span class="nama">{{ $bulan }}</span>
                            @if ($hasBukti && !$isUpcoming)
                                <button class="status status-{{ $color }}"
                                    onclick="window.open('{{ asset('storage/' . $data->bukti_path) }}', '_blank')">
                                    <img src="{{ asset('image/icon-Data_Keuangan/icon-download.svg') }}" alt="Download Icon">
                                </button>
                            @else
                                <button class="status status-{{ $color }}" disabled>
                                    <img src="{{ asset('image/icon-Data_Keuangan/icon-download.svg') }}" alt="Download Icon">
                                </button>
                            @endif
                        </div>

                        <div class="detail-collapsible" style="max-height:0;overflow:hidden;transition:max-height 0.3s; @if($isUpcoming) display:none; @endif">
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
                                    @if (auth()->user()->role === 'operator_sekolah' && !$isUpcoming)
                                        <form action="{{ route('keuangan.upload', $data->id ?? 0) }}" method="POST" enctype="multipart/form-data" class="form-upload" data-bulan="{{ $bulan }}">
                                            @csrf
                                            <label for="uploadBukti{{ $bulan }}" class="upload-bukti-button">Upload Bukti</label>
                                            <input type="file" id="uploadBukti{{ $bulan }}" name="bukti" class="upload-input" accept="image/*,application/pdf" style="display:none;">
                                            <div class="preview-container" style="display:none;">
                                                <div class="preview-file"></div>
                                                <button type="submit" class="upload-confirm-btn">Upload</button>
                                                <button type="button" class="upload-cancel-btn">Batal</button>
                                            </div>
                                        </form>
                                        <button class="bayar-button" data-bulan="{{ $bulan }}">Bayar</button>
                                    @elseif (auth()->user()->role === 'operator_yayasan' && !$isUpcoming)
                                        <button class="cek-bukti-button" data-bulan="{{ $bulan }}">Cek Bukti</button>
                                        <form method="POST" action="{{ route('keuangan.validasi', $data->id ?? 0) }}" class="form-validasi">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="sudah-bayar-button" data-bulan="{{ $bulan }}">Sudah Bayar</button>
                                        </form>
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

    <!-- Modal Preview Upload Bukti Operator Sekolah -->
    <div id="modalUploadPreview" class="popup-rekening" style="display:none;">
        <div class="popup-content" style="max-height: 80vh; overflow-y: auto; width: auto; max-width: 90vw;">
            <h3>Preview Bukti Pembayaran</h3>
            <div id="uploadPreviewContainer" style="text-align:center; margin-bottom: 1rem;"></div>
            <div style="text-align: right;">
                <button id="uploadConfirmBtn" style="margin-right: 10px; background-color:lime">Upload</button>
                <button onclick="tutupUploadPreview()">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal Cek Bukti -->
    <div id="modalCekBukti" class="popup-rekening" style="display:none;">
        <div class="popup-content">
            <h3>Bukti Pembayaran</h3>
            <div id="buktiContainer"></div>
            <button onclick="tutupBukti()">Tutup</button>
        </div>
    </div>
@endsection

@push('scripts')
{{-- JS Toggle --}}
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
    });

    function tampilkanPopup() {
        document.getElementById('popupRekening').style.display = 'flex';
    }

    function tutupPopup() {
        document.getElementById('popupRekening').style.display = 'none';
    }
</script>

{{-- JS Upload --}}
<script>
    let selectedForm = null;

    document.querySelectorAll('.upload-input').forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            selectedForm = this.closest('form');
            const previewContainer = document.getElementById('uploadPreviewContainer');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" style="max-height: 70vh;">`;
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                const objectURL = URL.createObjectURL(file);
                previewContainer.innerHTML = `<embed src="${objectURL}" type="application/pdf" width="100%" height="600px">`;
            } else {
                previewContainer.innerHTML = `<p>File: ${file.name}</p>`;
            }

            document.getElementById('modalUploadPreview').style.display = 'flex';
        });
    });

    document.getElementById('uploadConfirmBtn').addEventListener('click', function () {
        if (selectedForm) {
            selectedForm.submit();
            selectedForm = null;
            tutupUploadPreview();
        }
    });

    function tutupUploadPreview() {
        document.getElementById('modalUploadPreview').style.display = 'none';
        document.getElementById('uploadPreviewContainer').innerHTML = '';
        if (selectedForm) {
            selectedForm.querySelector('.upload-input').value = '';
            selectedForm = null;
        }
    }
</script>

{{-- JS Cek Bukti --}}
<script>
    document.querySelectorAll('.cek-bukti-button').forEach(button => {
        button.addEventListener('click', function () {
            const bulan = this.dataset.bulan;
            const data = {!! json_encode($keuangan) !!}.find(item => item.bulan === bulan);
            if (data && data.bukti_path) {
                const filePath = `/storage/${data.bukti_path}`;
                const container = document.getElementById('buktiContainer');
                if (filePath.match(/\.(jpg|jpeg|png)$/)) {
                    container.innerHTML = `<img src="${filePath}" style="max-width: 100%;">`;
                } else {
                    container.innerHTML = `<a href="${filePath}" target="_blank">Lihat Bukti (PDF)</a>`;
                }
                document.getElementById('modalCekBukti').style.display = 'flex';
            } else {
                alert('Bukti tidak ditemukan.');
            }
        });
    });

    function tutupBukti() {
        document.getElementById('modalCekBukti').style.display = 'none';
    }
</script>

{{-- JS Close Notif --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifError = document.getElementById('notif-error');
        if (notifError) {
            setTimeout(() => {
                notifError.style.transition = 'opacity 0.5s ease';
                notifError.style.opacity = 0;
                setTimeout(() => notifError.remove(), 500);
            }, 3000);
        }

        const notifSuccess = document.getElementById('notif-success');
        if (notifSuccess) {
            setTimeout(() => {
                notifSuccess.style.transition = 'opacity 0.5s ease';
                notifSuccess.style.opacity = 0;
                setTimeout(() => notifSuccess.remove(), 500);
            }, 3000);
        }
    });
</script>

<script>
    document.querySelectorAll('.form-validasi').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Optionally update the status and button color in the UI
                    // You may want to reload the page or update only the relevant DOM
                    location.reload(); // simplest way
                } else {
                    alert(data.message || 'Gagal memvalidasi pembayaran.');
                }
            })
            .catch(() => alert('Terjadi kesalahan.'));
        });
    });
</script>
@endpush
