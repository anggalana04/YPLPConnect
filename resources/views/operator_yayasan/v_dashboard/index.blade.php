@extends('v_layouts.index')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Operator Yayasan/dashboard_yayasan.css') }}">
<link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
@endpush

@section('content')
<div class="konten">
    <div class="konten-head @if(auth()->user()->role != 'operator_yayasan') konten-head-sekolah @endif">
        <h1>Hallo...</h1>
        <div class="welcome">
            <h2>
                Selamat datang! {{ auth()->user()->name }}
                @if(auth()->user()->role == 'operator_yayasan')
                    , Kamu adalah operator yayasan!
                @endif
            </h2>
        </div>
    </div>

    <div class="konten-body @if(auth()->user()->role == 'operator_yayasan') konten-body-yayasan @else konten-body-sekolah @endif">

        @if(auth()->user()->role == 'operator_yayasan')
            {{-- Konten untuk operator yayasan --}}
            <div class="card card-yayasan">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahGuruPerTahun) }}</p>
                </div>
                <canvas id="chartGuru" style="height: 300px;"></canvas>
            </div>

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

            <div class="card card-yayasan">
                <h1>Pengaduan</h1>

                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan ditemukan.</p>
                @else
                    <div class="pengaduan-scroll">
                        @foreach ($pengaduans as $pengaduan)
                            <div class="status-head">
                                <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                                    <div class="judul-pengaduan">
                                        Judul Pengaduan: {{ $pengaduan->judul }}
                                    </div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status Pengaduan</span>
                                    @php
                                        $statusSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                                        $currentIndex = array_search($pengaduan->status, $statusSteps);
                                    @endphp
                                    <div class="box-status-step">
                                        @foreach ($statusSteps as $index => $step)
                                            <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}">
                                                <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                            </div>
                                            @if ($index < count($statusSteps) - 1)
                                                <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card card-4">
                <h1>Dokumen</h1>
            </div>

            <div class="card card-yayasan card-full-width" style="grid-area: card5; height: 300px; overflow: hidden; position: relative;">
                <h1 class="head-keuangan">Keuangan Yayasan</h1>

                <div class="header-bar-yayasan">
                    <h1 class="head-keuangan">Rp.xxxxxx</h1>
                    <select id="kategori" name="tahun" class="select-tahun-yayasan">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="height: 300px; overflow-x: auto;">
                    <canvas id="chartKeuangan" style="max-height: 100%;"></canvas>
                </div>
            </div>

        @else
            {{-- Konten untuk operator sekolah --}}
            <div class="card card-sekolah">
                <h1 class="head-guru">Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="">
                    <p class="jumlah-data">{{ array_sum($jumlahGuruPerTahun) }}</p>
                </div>
                <canvas id="chartGuru" style="height: 300px;"></canvas>
            </div>

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

            <div class="card card-sekolah">
                <h1 style="text-align:center;">Keuangan</h1>

                <!-- Progress bar dengan gradient -->
                <div class="keuangan-bar-container">
                    <div id="keuanganBar" class="keuangan-bar"></div>
                </div>
                <!-- Tambahkan Select Tahun Di Sini -->
                <form method="GET" onsubmit="return false;">
                    <select id="kategori" name="tahun" class="select-tahun">
                        <option value="">Pilih Tahun</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @php
                    $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                @endphp

                <div class="keuangan-scroll">
                    @foreach ($bulanList as $bulan)
                        @php
                            $item = $keuangan->firstWhere('bulan', $bulan);
                            $status = $item && $item->status === 'Sudah Bayar';
                            $color = $status ? 'limegreen' : 'red';
                        @endphp
                        <div class="keuangan-item">
                            <span>{{ $bulan }}</span>
                            <span>Rp. {{ $item ? number_format(2000 * $jumlahSiswa, 0, ',', '.') : 'X.XXX.XXX' }}</span>
                            <div class="status-dot" style="background-color: {{ $color }}"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Data untuk JS -->
                <script>
                    window.keuanganData = {
                        total: {{ count($bulanList) }},
                        sudahBayar: {{ $keuangan->where('status', 'Sudah Bayar')->count() }}
                    };
                </script>
            </div>

            <div class="card card-sekolah">
                <h1>Pengaduan</h1>

                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan ditemukan.</p>
                @else
                    <div class="pengaduan-scroll">
                        @foreach ($pengaduans as $pengaduan)
                            <div class="status-head">
                                <span class="head toggle-status" style="display: flex; align-items: center; width: 100%;">
                                    <div class="judul-pengaduan">
                                        Judul Pengaduan: {{ $pengaduan->judul }}
                                    </div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" style="cursor: pointer;" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status Pengaduan</span>
                                    @php
                                        $statusSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                                        $currentIndex = array_search($pengaduan->status, $statusSteps);
                                    @endphp
                                    <div class="box-status-step">
                                        @foreach ($statusSteps as $index => $step)
                                            <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                                                <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}">
                                                <span>{{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}</span>
                                            </div>
                                            @if ($index < count($statusSteps) - 1)
                                                <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                            @endif
                                        @endforeach
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

{{-- SCRIPT UNTUK SISWA & GURU --}}
<script>
    window.guruData = @json($jumlahGuruPerTahun);
    window.siswaData = @json($jumlahSiswaPerTahun);
    window.keuanganData = @json($keuanganPerTahun); // <- tambahkan ini
    window.tahunList = @json($tahunList);
</script>

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

{{-- SCRIPT AJAX UNTUK DROPDOWN TAHUN --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectTahun = document.getElementById('kategori');
        const keuanganScroll = document.querySelector('.keuangan-scroll');
        const jumlahSiswa = {{ $jumlahSiswa }}; // data dari backend untuk hitung nominal

        selectTahun.addEventListener('change', function () {
            const tahunTerpilih = this.value;

            fetch(`/keuangan/by-tahun?tahun=${tahunTerpilih}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear dulu list bulan
                keuanganScroll.innerHTML = '';

                const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                bulanList.forEach(bulan => {
                    // cari data keuangan yang sesuai bulan
                    const item = data.find(k => k.bulan === bulan);
                    const status = item && item.status === 'Sudah Bayar';
                    const color = status ? 'limegreen' : 'tomato';
                    const jumlah = item ? (2000 * jumlahSiswa).toLocaleString('id-ID') : 'X.XXX.XXX';

                    const div = document.createElement('div');
                    div.classList.add('keuangan-item');
                    div.innerHTML = `
                        <span>${bulan}</span>
                        <span>Rp. ${jumlah}</span>
                        <div class="status-dot" style="background-color: ${color}"></div>
                    `;
                    keuanganScroll.appendChild(div);
                });

                // Update progress bar jika perlu
                const total = bulanList.length;
                const sudahBayar = data.filter(k => k.status === 'Sudah Bayar').length;
                const bar = document.getElementById('keuanganBar');
                const percent = (sudahBayar / total) * 100;
                bar.style.width = `${percent}%`;
            })
            .catch(error => console.error('Error fetch keuangan:', error));
        });
    });
</script>
@endpush
