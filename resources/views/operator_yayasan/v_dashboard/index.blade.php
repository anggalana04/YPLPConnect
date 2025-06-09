@extends('v_layouts.index')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/Operator Yayasan/dashboard_yayasan.css') }}">
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
@php
    // Centralized config for months and status colors (always defined)
    $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $statusColor = [
        'Menunggu' => 'orange',
        'Disetujui' => 'limegreen',
        'Ditolak'   => 'red',
    ];
    // Always define $keuanganData as a collection for both roles
    if (auth()->user()->role === 'operator_yayasan') {
        // For yayasan, get all keuangan for the selected year, all sekolah, all status
        $keuanganData = \App\Models\Keuangan::where('tahun', $tahunDipilih)->get();
    } else {
        $keuanganData = collect($keuangan ?? $keuanganPerBulan ?? []);
    }
    // $allApproved: true if all months are present and status is Disetujui
    $allApproved = $keuanganData->count() === count($bulanList) && $keuanganData->every(function($item) {
        $status = is_array($item) ? ($item['status'] ?? null) : ($item->status ?? null);
        return $status === 'Disetujui';
    });
    // Use $keuanganPerBulan and $totalKeuanganTahun from controller
    if (!isset($keuanganPerBulan)) {
        $keuanganPerBulan = array_fill(0, count($bulanList), 0);
    }
    // Ensure only show Disetujui in chart (defensive for all years)
    if (isset($keuanganData) && $keuanganData->count()) {
        $keuanganPerBulan = [];
        foreach ($bulanList as $bulan) {
            $item = $keuanganData->first(function($d) use ($bulan) {
                $b = is_array($d) ? ($d['bulan'] ?? null) : ($d->bulan ?? null);
                $status = is_array($d) ? ($d['status'] ?? null) : ($d->status ?? null);
                return $b === $bulan && $status === 'Disetujui';
            });
            $keuanganPerBulan[] = $item ? (is_array($item) ? ($item['jumlah_spp'] ?? 0) : ($item->jumlah_spp ?? 0)) : 0;
        }
    }
@endphp
<div class="konten">
    <div class="konten-head @if(auth()->user()->role != 'operator_yayasan') konten-head-sekolah @endif">
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>
                Selamat datang! {{ auth()->user()->name }}
                @if(auth()->user()->role == 'operator_yayasan')
                    , Kamu adalah operator yayasan!
                @elseif(auth()->user()->role == 'operator_sekolah')
                    , Kamu adalah operator sekolah
                    @if(auth()->user()->sekolah)
                        di <b>{{ auth()->user()->sekolah->nama }}</b>
                    @endif
                @endif
            </h2>
        </div>
    </div>

    <div class="konten-body @if(auth()->user()->role == 'operator_yayasan') konten-body-yayasan @else konten-body-sekolah @endif">
        @if(auth()->user()->role == 'operator_yayasan')
            {{-- Card Jumlah Guru --}}
            <div class="card card-yayasan">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahGuruPerTahun) }}</p>
                </div>
                <canvas id="chartGuru" style="height: 300px;"></canvas>
            </div>

            {{-- Card Jumlah Siswa --}}
            <div class="card card-yayasan">
                <h1 class="head-guru">Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahSiswaPerTahun) }}</p>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chartSiswa" style="height: 300px"></canvas>
                </div>
            </div>

            {{-- Card Pengaduan --}}
            <div class="card card-yayasan">
                <h1>Pengaduan</h1>
                <div style="margin-bottom:10px; font-size:1rem; color:#ff9800; font-weight:500;">
                    @php $jumlahMenungguPengaduan = $pengaduans->where('status', 'menunggu')->count(); @endphp
                    @if($jumlahMenungguPengaduan > 0)
                        <span class="badge-pending">{{ $jumlahMenungguPengaduan }} pengaduan menunggu</span>
                    @else
                        <span style="color:#43e97b;">Tidak ada pengaduan menunggu</span>
                    @endif
                </div>
                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan dengan status "Menunggu" ditemukan.</p>
                @else
                    <div class="pengaduan-scroll">
                        @foreach ($pengaduans as $pengaduan)
                            @php
                                $allSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                                $statusMapping = [
                                    'menunggu' => 'terkirim',
                                    'terkirim' => 'terkirim',
                                    'diterima' => 'diterima',
                                    'diproses' => 'diproses',
                                    'selesai' => 'selesai',
                                ];
                                $statusDb = strtolower($pengaduan->status);
                                $mappedStatus = $statusMapping[$statusDb] ?? 'terkirim';
                                $statusSteps = array_slice($allSteps, 1); // yayasan: skip 'terkirim'
                                $currentIndex = array_search($mappedStatus, $statusSteps);
                                $sekolahNama = $pengaduan->sekolah->nama ?? ($pengaduan->sekolah_nama ?? '-');
                            @endphp
                            <div class="status-head" data-id="{{ $pengaduan->id }}" style="cursor:pointer;">
                                <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                                    <div class="judul-pengaduan">
                                        Judul Pengaduan: {{ $pengaduan->judul }}<br>
                                        <span style="font-size:12px;color:#888;">{{ $sekolahNama }}</span>
                                    </div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status: {{ ucfirst($pengaduan->status) }}</span>
                                    <div class="status-container yayasan">
                                        <div class="box-status-step">
                                            @foreach ($statusSteps as $index => $step)
                                                <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                                                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                                                    <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                                </div>
                                                @if ($index < count($statusSteps) - 1)
                                                    <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Card Dokumen --}}
            <div class="card card-yayasan">
                <h1>Dokumen</h1>
                <div style="margin-bottom:10px; font-size:1rem; color:#ff9800; font-weight:500;">
                    @php $jumlahMenungguDokumen = $dokumens->where('status', 'Menunggu')->count(); @endphp
                    @if($jumlahMenungguDokumen > 0)
                        <span class="badge-pending">{{ $jumlahMenungguDokumen }} dokumen menunggu</span>
                    @else
                        <span style="color:#43e97b;">Tidak ada dokumen menunggu</span>
                    @endif
                </div>
                @if ($dokumens->isEmpty())
                    <p class="No-data-dokumen">Tidak ada dokumen dengan status "Menunggu" ditemukan.</p>
                @else
                    <div class="dokumen-scroll">
                        @foreach ($dokumens as $dokumen)
                            @php
                                $sekolahNama = $dokumen->guru->sekolah->nama ?? ($dokumen->guru->sekolah_nama ?? '-');
                            @endphp
                            <div class="status-head dokumen-row" data-id="{{ $dokumen->id }}" style="cursor:pointer;">
                                <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                                    <div class="judul-dokumen">
                                        Judul Dokumen: {{ $dokumen->jenis_sk }}<br>
                                        <span style="font-size:12px;color:#888;">{{ $sekolahNama }}</span>
                                    </div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status: {{ ucfirst($dokumen->status) }}</span>
                                    <div class="status-container yayasan">
                                        <div class="box-status-step">
                                            @php
                                                $allSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                                                $statusMapping = [
                                                    'menunggu' => 'terkirim',
                                                    'terkirim' => 'terkirim',
                                                    'diterima' => 'diterima',
                                                    'diproses' => 'diproses',
                                                    'selesai' => 'selesai',
                                                    'ditolak' => 'selesai',
                                                ];
                                                $statusDb = strtolower($dokumen->status);
                                                $mappedStatus = $statusMapping[$statusDb] ?? 'terkirim';
                                                $statusSteps = array_slice($allSteps, 1);
                                                $currentIndex = array_search($mappedStatus, $statusSteps);
                                            @endphp
                                            @foreach ($statusSteps as $index => $step)
                                                <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                                                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                                                    <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                                </div>
                                                @if ($index < count($statusSteps) - 1)
                                                    <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Card Keuangan Yayasan --}}
            <div class="card card-yayasan" style="grid-column: span 2; width: 100%; min-width: 600px;">
                <h1 class="head-guru">Rekap Keuangan Tahunan</h1>
                <div class="chart-wrapper" style="width:100%; min-width:600px;">
                    <canvas id="chartKeuanganYayasan" style="height: 300px; width:100%"></canvas>
                </div>
                <div style="text-align:center;margin-top:10px;font-weight:500;">
                    <span id="totalKeuanganYayasan">Rp.0</span>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" id="formTahunYayasan">
                    <select id="tahunKeuanganYayasan" name="tahun" class="select-tahun-yayasan" onchange="document.getElementById('formTahunYayasan').submit()">
                        <option value="">Pilih Tahun</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            {{-- End Card Keuangan Yayasan --}}

        @else
            {{-- Konten untuk Operator Sekolah --}}
            {{-- Card Jumlah Guru --}}
            <div class="card card-sekolah">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahGuruPerTahun) }}</p>
                </div>
                <canvas id="chartGuru" style="height: 300px;"></canvas>
            </div>

            {{-- Card Jumlah Siswa --}}
            <div class="card card-sekolah">
                <h1 class="head-guru">Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahSiswaPerTahun) }}</p>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chartSiswa" style="height: 300px"></canvas>
                </div>
            </div>

            {{-- Card Keuangan Sekolah --}}
            <div class="card card-sekolah">
                <h1 style="text-align:center;">Keuangan</h1>
                <div class="keuangan-bar-container">
                    @php
                        $totalBulan = count($bulanList);
                        $sudahBayar = $keuanganData->where('status', 'Disetujui')->count();
                        $percent = $totalBulan > 0 ? round(($sudahBayar / $totalBulan) * 100) : 0;
                        $currentMonth = (int) date('n');
                        $currentYear = (int) date('Y');
                        $isCurrentYear = ($tahunDipilih == $currentYear);
                    @endphp
                    <div id="keuanganBar" class="keuangan-bar" style="width: {{ $percent }}%;"></div>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" id="formTahun">
                    <select id="kategori" name="tahun" class="select-tahun" onchange="document.getElementById('formTahun').submit()">
                        <option value="">Pilih Tahun</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </form>
                <div class="keuangan-scroll" id="keuanganScroll">
                    @foreach ($bulanList as $i => $bulan)
                        @php
                            $item = $keuanganData->first(fn($d) => ($d->bulan ?? null) === $bulan);
                            $status = $item->status ?? 'Menunggu';
                            $color = $statusColor[$status] ?? 'gray';
                            $jumlah = $item->jumlah_spp ?? $item['jumlah_spp'] ?? ($item ? 2000 * ($jumlahSiswa ?? 0) : null);
                            $isUpcoming = $isCurrentYear && ($i + 1) > $currentMonth;
                        @endphp
                        @if(!$isUpcoming)
                        <div class="keuangan-item">
                            <span class="bulan">{{ $bulan }}</span>
                            <span class="jumlah">Rp. {{ $jumlah ? number_format($jumlah, 0, ',', '.') : 'X.XXX.XXX' }}</span>
                            <div class="status-dot" style="background-color: {{ $color }}" title="{{ $status }}"></div>
                        </div>
                        @endif
                    @endforeach
                    @if($keuanganData->isEmpty())
                        <div style="padding:10px;color:#888;">Tidak ada data keuangan untuk tahun ini.</div>
                    @endif
                </div>
                {{-- Download Recap button removed for operator_sekolah --}}
                @if($allApproved && false)
                    <form method="GET" action="{{ route('keuangan.download.recap') }}" style="margin-top:10px;">
                        <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">
                        <button type="submit" class="download-btn download-sekolah">Download Recap</button>
                    </form>
                @else
                    {{-- <button class="download-btn download-sekolah" disabled style="margin-top:10px;" title="Lengkapi pembayaran semua bulan untuk download recap">Download Recap</button> --}}
                @endif
            </div>

            {{-- Card Pengaduan --}}
            <div class="card card-sekolah">
                <h1>Pengaduan</h1>
                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan dengan status "Menunggu" ditemukan.</p>
                @else
                    <div class="pengaduan-scroll">
                        @foreach ($pengaduans as $pengaduan)
                            @php
                                $allSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                                $statusMapping = [
                                    'menunggu' => 'terkirim',
                                    'terkirim' => 'terkirim',
                                    'diterima' => 'diterima',
                                    'diproses' => 'diproses',
                                    'selesai' => 'selesai',
                                ];
                                $statusDb = strtolower($pengaduan->status);
                                $mappedStatus = $statusMapping[$statusDb] ?? 'terkirim';
                                $user = auth()->user();
                                if ($user->role === 'operator_yayasan') {
                                    $statusSteps = array_slice($allSteps, 1);
                                } else {
                                    $statusSteps = $allSteps;
                                }
                                $currentIndex = array_search($mappedStatus, $allSteps);
                                if ($user->role === 'operator_yayasan' && $currentIndex < 1) {
                                    $currentIndex = 1;
                                }
                                $currentIndexForDisplay = $user->role === 'operator_yayasan' ? $currentIndex - 1 : $currentIndex;
                            @endphp
                            <div class="status-head">
                                <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                                    <div class="judul-pengaduan">
                                        Judul Pengaduan: {{ $pengaduan->judul }}
                                    </div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status: {{ ucfirst($pengaduan->status) }}</span>
                                    <div class="status-container {{ $user->role === 'operator_yayasan' ? 'yayasan' : '' }}">
                                        <div class="box-status-step">
                                            @foreach ($statusSteps as $index => $step)
                                                <div class="status-step {{ $index <= $currentIndexForDisplay ? 'active' : '' }}">
                                                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                                                    <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                                </div>
                                                @if ($index < count($statusSteps) - 1)
                                                    <div class="status-line {{ $index < $currentIndexForDisplay ? 'active' : '' }}"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('JavaScript/JS_Data_pengaduan/data_pengaduan.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('JavaScript/Show_Data-Guru/Data_Guru_/Tahun.js') }}"></script>
    <script>
        window.guruData = @json($jumlahGuruPerTahun);
        window.siswaData = @json($jumlahSiswaPerTahun);
        window.tahunList = @json($tahunList);
        window.bulanList = @json($bulanList);
        window.keuanganData = @json(isset($keuanganPerBulan) ? $keuanganPerBulan : []);
    </script>

    {{-- JS: Toggle Pengaduan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-status').forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    // Close all others
                    document.querySelectorAll('.detail-status').forEach(function (el) {
                        el.style.display = 'none';
                    });
                    document.querySelectorAll('.arrow-icon').forEach(function (el) {
                        el.classList.remove('rotate');
                    });
                    // Toggle this one
                    const statusHead = this.closest('.status-head');
                    const detail = statusHead.querySelector('.detail-status');
                    const arrowIcon = this.querySelector('.arrow-icon');
                    if (detail.style.display === 'block') {
                        detail.style.display = 'none';
                        arrowIcon.classList.remove('rotate');
                    } else {
                        detail.style.display = 'block';
                        arrowIcon.classList.add('rotate');
                    }
                });
            });
            // Hide all on load
            document.querySelectorAll('.detail-status').forEach(function (el) {
                el.style.display = 'none';
            });
        });
    </script>

    {{-- JS: Progress Keuangan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = window.keuanganData;
            const bar = document.getElementById('keuanganBar');
            if (data.total > 0) {
                const percent = (data.sudahBayar / data.total) * 100;
                bar.style.width = `${percent}%`;
            }
        });
    </script>

    {{-- JS: Dropdown AJAX Sekolah --}}
<script>
    document.getElementById('kategori').addEventListener('change', function () {
        const tahun = this.value;

        fetch(`/keuangan/by-tahun?tahun=${tahun}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('keuanganScroll');
                container.innerHTML = ''; // Kosongkan isi sebelumnya

                const jumlahSiswa = {{ $jumlahSiswa ?? 0 }};
                const bulanList = @json($bulanList);

                bulanList.forEach(bulan => {
                    const item = data.find(d => d.bulan === bulan);
                    const status = item && item.status === 'Disetujui';
                    const color = status ? 'limegreen' : 'red';
                    const jumlah = item ? `Rp. ${(2000 * jumlahSiswa).toLocaleString('id-ID')}` : 'Rp. X.XXX.XXX';

                    container.innerHTML += `
                        <div class="keuangan-item">
                            <span class="bulan">${bulan}</span>
                            <span class="jumlah">${jumlah}</span>
                            <div class="status-dot" style="background-color: ${color}"></div>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error('Gagal mengambil data keuangan:', error);
            });
    });
</script>



    {{-- JS: Toggle Dokumen --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dokumen-scroll .status-head').forEach(function (head) {
                head.addEventListener('click', function () {
                    this.classList.toggle('active');
                });
            });
        });
    </script>

    {{-- JS: Default Message Check --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pengaduanScroll = document.querySelector('.pengaduan-scroll');
            const dokumenScroll = document.querySelector('.dokumen-scroll');

            if (pengaduanScroll && pengaduanScroll.children.length === 0) {
                pengaduanScroll.innerHTML = '<p class="No-data-pengaduan">Tidak ada pengaduan dengan status "Menunggu" ditemukan.</p>';
            }

            if (dokumenScroll && dokumenScroll.children.length === 0) {
                dokumenScroll.innerHTML = '<p class="No-data-dokumen">Tidak ada dokumen dengan status "Menunggu" ditemukan.</p>';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pengaduan expand/collapse and click
            document.querySelectorAll('.pengaduan-scroll .status-head').forEach(function (row) {
                const toggle = row.querySelector('.toggle-status');
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Close all others
                    document.querySelectorAll('.pengaduan-scroll .detail-status').forEach(function (el) {
                        el.style.display = 'none';
                    });
                    document.querySelectorAll('.pengaduan-scroll .arrow-icon').forEach(function (el) {
                        el.classList.remove('rotate');
                    });
                    const detail = row.querySelector('.detail-status');
                    const arrowIcon = row.querySelector('.arrow-icon');
                    if (detail.style.display === 'block') {
                        detail.style.display = 'none';
                        arrowIcon.classList.remove('rotate');
                    } else {
                        detail.style.display = 'block';
                        arrowIcon.classList.add('rotate');
                    }
                });
                // Make row clickable to detail
                row.addEventListener('click', function (e) {
                    // Only if not clicking the toggle-status
                    if (!e.target.closest('.toggle-status')) {
                        const id = row.getAttribute('data-id');
                        if (id) window.location.href = '/pengaduan/detail/' + id;
                    }
                });
            });
            // Dokumen expand/collapse and click
            document.querySelectorAll('.dokumen-scroll .status-head').forEach(function (row) {
                const toggle = row.querySelector('.toggle-status');
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Close all others
                    document.querySelectorAll('.dokumen-scroll .detail-status').forEach(function (el) {
                        el.style.display = 'none';
                    });
                    document.querySelectorAll('.dokumen-scroll .arrow-icon').forEach(function (el) {
                        el.classList.remove('rotate');
                    });
                    const detail = row.querySelector('.detail-status');
                    const arrowIcon = row.querySelector('.arrow-icon');
                    if (detail.style.display === 'block') {
                        detail.style.display = 'none';
                        arrowIcon.classList.remove('rotate');
                    } else {
                        detail.style.display = 'block';
                        arrowIcon.classList.add('rotate');
                    }
                });
                // Make row clickable to detail
                row.addEventListener('click', function (e) {
                    if (!e.target.closest('.toggle-status')) {
                        const id = row.getAttribute('data-id');
                        if (id) window.location.href = '/dokumen/detail/' + id;
                    }
                });
            });
            // Hide all on load
            document.querySelectorAll('.detail-status').forEach(function (el) {
                el.style.display = 'none';
            });
        });
    </script>

    {{-- JS: Chart.js Configuration --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Always render chart on page load with initial data
            const ctx = document.getElementById('chartKeuangan').getContext('2d');
            const bulanList = @json($bulanList);
            const dataPerBulan = @json($keuanganPerBulan);
            let chartKeuangan = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulanList,
                    datasets: [{
                        label: 'Pemasukan',
                        data: dataPerBulan,
                        backgroundColor: '#43e97b',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return value > 0 ? 'Rp. ' + value.toLocaleString('id-ID') : '';
                            },
                            color: '#333',
                            font: { weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp. ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            // AJAX: update chart and total on tahun change
            document.getElementById('kategori').addEventListener('change', function () {
                const tahun = this.value;
                if (!tahun) return;
                fetch(`/yayasan/keuangan/by-tahun?tahun=${tahun}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    // Only use Disetujui
                    const bulanList = @json($bulanList);
                    let dataPerBulan = bulanList.map(b => {
                        const bulanData = data.find(item => item.bulan === b && item.status === 'Disetujui');
                        return bulanData ? bulanData.jumlah_spp : 0;
                    });
                    chartKeuangan.data.datasets[0].data = dataPerBulan;
                    chartKeuangan.update();
                    // Update total
                    const total = dataPerBulan.reduce((sum, v) => sum + v, 0);
                    document.getElementById('totalKeuanganTahun').textContent = 'Rp.' + total.toLocaleString('id-ID');
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    chartKeuangan.data.datasets[0].data = Array(12).fill(0);
                    chartKeuangan.update();
                    document.getElementById('totalKeuanganTahun').textContent = 'Rp.0';
                });
            });
        });
    </script>

    {{-- JS: Keuangan All Sekolah --}}
    <script>
        const tahunList = @json($tahunList);
        let sekolahList = @json(isset($sekolahList) ? $sekolahList : []);
        // Data: {tahun: {sekolah: total}}
        let yearlySchoolData = {};
        @php
            $allTahun = $tahunList;
            $allSekolah = isset($sekolahList) ? $sekolahList : [];
            $allData = [];
            foreach ($allTahun as $tahun) {
                $totals = [];
                foreach ($allSekolah as $npsn => $nama) {
                    // Defensive: always set value, even if 0
                    $sum = \App\Models\Keuangan::where('npsn', $npsn)
                        ->where('tahun', $tahun)
                        ->where('status', 'Disetujui')
                        ->sum('jumlah_spp');
                    $totals[$nama] = (float) $sum;
                }
                $allData[$tahun] = $totals;
            }
        @endphp
        yearlySchoolData = @json($allData);

        function renderKeuanganAllChart(tahun) {
            const sekolahNames = Object.keys(yearlySchoolData[tahun] || {});
            const totals = sekolahNames.map(nama => yearlySchoolData[tahun][nama] || 0);
            const ctx = document.getElementById('chartKeuanganAll').getContext('2d');
            const chartElem = document.getElementById('chartKeuanganAll');
            const noDataElem = document.getElementById('noDataKeuanganAll');
            // Fallback: if no sekolah or all totals are zero, show no data message
            if (sekolahNames.length === 0 || totals.every(v => v === 0)) {
                chartElem.style.display = 'none';
                noDataElem.style.display = 'block';
                document.getElementById('totalKeuanganTahunAll').textContent = 'Rp.0';
                if (window.chartKeuanganAll) window.chartKeuanganAll.destroy();
                return;
            } else {
                chartElem.style.display = 'block';
                noDataElem.style.display = 'none';
            }
            if (window.chartKeuanganAll) window.chartKeuanganAll.destroy();
            window.chartKeuanganAll = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sekolahNames,
                    datasets: [{
                        label: 'Total Pemasukan Disetujui',
                        data: totals,
                        backgroundColor: totals.map(v => v > 0 ? '#43e97b' : '#e0e0e0'),
                        borderRadius: 8,
                        maxBarThickness: 32,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return value > 0 ? 'Rp. ' + value.toLocaleString('id-ID') : '';
                            },
                            color: '#222',
                            font: { weight: 'bold', size: 13 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.raw > 0 ? 'Rp. ' + ctx.raw.toLocaleString('id-ID') : 'Belum Disetujui';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold', size: 12 }, color: '#333' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#eee' },
                            ticks: {
                                callback: function(value) {
                                    return value > 0 ? 'Rp. ' + value.toLocaleString('id-ID') : '';
                                },
                                font: { size: 12 }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
            // Update total
            const total = totals.reduce((sum, v) => sum + v, 0);
            document.getElementById('totalKeuanganTahunAll').textContent = 'Rp.' + total.toLocaleString('id-ID');
        }
    </script>

    {{-- JS: Chart Keuangan Yayasan (Operator Yayasan) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ({{ auth()->user()->role === 'operator_yayasan' ? 'true' : 'false' }}) {
                const ctx = document.getElementById('chartKeuanganYayasan').getContext('2d');
                const bulanList = @json($bulanList);
                // Use keuanganData as array of objects for yayasan (handle both array and collection)
                let keuanganData = @json($keuanganData);
                if (Array.isArray(keuanganData) && keuanganData.length && typeof keuanganData[0] !== 'object') {
                    // If keuanganData is array of numbers (from $keuanganPerBulan), convert to objects
                    keuanganData = bulanList.map((bulan, i) => ({ bulan, status: 'Disetujui', jumlah_spp: keuanganData[i] }));
                } else if (keuanganData && typeof keuanganData === 'object' && !Array.isArray(keuanganData)) {
                    // If keuanganData is an object (Laravel collection as object), convert to array
                    keuanganData = Object.values(keuanganData);
                }
                let dataPerBulan = bulanList.map(bulan => {
                    const item = keuanganData.find(d => (d.bulan ?? null) === bulan && (d.status ?? null) === 'Disetujui');
                    return item ? (item.jumlah_spp ?? 0) : 0;
                });
                let chartKeuanganYayasan = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: bulanList,
                        datasets: [{
                            label: 'Jumlah Diterima',
                            data: dataPerBulan,
                            backgroundColor: '#43e97b',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: function(value) {
                                    return value > 0 ? 'Rp. ' + value.toLocaleString('id-ID') : '';
                                },
                                color: '#333',
                                font: { weight: 'bold' }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp. ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
                // Show total
                const total = dataPerBulan.reduce((sum, v) => sum + (typeof v === 'number' ? v : parseFloat(v) || 0), 0);
                document.getElementById('totalKeuanganYayasan').textContent = 'Rp.' + total.toLocaleString('id-ID');
                // AJAX update on tahun change (if needed)
                document.getElementById('tahunKeuanganYayasan').addEventListener('change', function () {
                    // Optionally, fetch new data if you want AJAX, or just submit form
                });
            }
        });
    </script>
@endpush
