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
                @if ($pengaduans->isEmpty())
                    <p class="No-data-pengaduan">Tidak ada pengaduan dengan status "Menunggu" ditemukan.</p>
                @else
                    <div class="pengaduan-scroll">
                        @foreach ($pengaduans as $pengaduan)
                            <div class="status-head">
                                <span class="head toggle-status">
                                    <div class="judul-pengaduan">Judul Pengaduan: {{ $pengaduan->judul }}</div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status: {{ ucfirst($pengaduan->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Card Dokumen --}}
            <div class="card card-yayasan">
                <h1>Dokumen</h1>
                @if ($dokumens->isEmpty())
                    <p class="No-data-dokumen">Tidak ada dokumen dengan status "Menunggu" ditemukan.</p>
                @else
                    <div class="dokumen-scroll">
                        @foreach ($dokumens as $dokumen)
                            <div class="status-head">
                                <span class="head toggle-status">
                                    <div class="judul-dokumen">Judul Dokumen: {{ $dokumen->jenis_sk }}</div>
                                    <img src="{{ asset('image/icon-row/row.svg') }}" class="arrow-icon" alt="Toggle Arrow" />
                                </span>
                                <div class="detail-status">
                                    <span class="head-detail">Status: {{ ucfirst($dokumen->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Card Keuangan Yayasan --}}
            <div class="card card-yayasan card-full-width" style="grid-area: card5; height: 300px; overflow: hidden; position: relative;">
                <h1 class="head-keuangan">Keuangan Yayasan</h1>
                <div class="header-bar-yayasan">
                    <h1 id="totalKeuanganTahun" class="head-keuangan">
                        Rp.{{ isset($totalKeuanganTahun) ? number_format($totalKeuanganTahun, 0, ',', '.') : '0' }}
                    </h1>
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
                    <div id="keuanganBar" class="keuangan-bar"></div>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" id="formTahun">
                    <select id="kategori" name="tahun" class="select-tahun" onchange="document.getElementById('formTahun').submit()">
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
                <div class="keuangan-scroll" id="keuanganScroll">
                    @foreach ($bulanList as $bulan)
                        @php
                            $item = $keuangan->firstWhere('bulan', $bulan);
                            $status = $item && $item->status === 'Disetujui';
                            $color = $status ? 'limegreen' : 'red';
                        @endphp
                        <div class="keuangan-item">
                            <span class="bulan">{{ $bulan }}</span>
                            <span class="jumlah">Rp. {{ $item ? number_format(2000 * $jumlahSiswa, 0, ',', '.') : 'X.XXX.XXX' }}</span>
                            <div class="status-dot" style="background-color: {{ $color }}"></div>
                        </div>
                    @endforeach
                </div>
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
                    const detail = this.closest('.status-head').querySelector('.detail-status');
                    if (detail) {
                        detail.style.display = (detail.style.display === 'none' || detail.style.display === '') ? 'block' : 'none';
                    }
                });
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


    {{-- JS: Keuangan Yayasan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectTahun = document.getElementById('kategori');
            const totalElem = document.getElementById('totalKeuanganTahun');
            const bulanList = @json($bulanList);

            selectTahun.addEventListener('change', function () {
                const tahun = this.value;
                if (!tahun) return;

                fetch(`/yayasan/keuangan/by-tahun?tahun=${tahun}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    const total = data.reduce((sum, item) => sum + item.jumlah_spp, 0);
                    totalElem.textContent = 'Rp.' + new Intl.NumberFormat('id-ID').format(total);

                    const dataPerBulan = bulanList.map(b => {
                        const bulanData = data.find(item => item.bulan === b);
                        return bulanData ? bulanData.jumlah_spp : 0;
                    });

                    renderBarChart('chartKeuangan', dataPerBulan, ['#43e97b', '#38f9d7'], bulanList);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    totalElem.textContent = 'Rp.0';
                });
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
@endpush