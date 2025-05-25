<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
    <title>Dashboard</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="konten-head">
            <h1>Hallo...</h1>
            <h2>Selamat datang! {{ Auth::user()->name }}</h2>

            @if (Auth::user()->role == 'operator_yayasan')
                <h2>Kamu adalah operator yayasan!</h2>
            @endif
        </div>

        <div class="konten-body">
            <div class="card">
                <h1>Jumlah Guru</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Guru.svg') }}" alt="" />
                    <p>xxx</p>
                </div>
            </div>

            <div class="card">
                <h1>Jumlah Siswa</h1>
                <div class="detail-card">
                    <img src="{{ asset('image/icon-dashboard/icon-Jumlah-Siswa.svg') }}" alt="" />
                    <p>{{ $jumlahSiswa }}</p>
                </div>
            </div>

            <div class="card">
                <h1>Keuangan</h1>
                <div class="keuangan-bar">
                    @php
                        $bulanList = [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                    @endphp

                    @foreach ($bulanList as $bulan)
                        @php
                            $item = $keuangan->firstWhere('bulan', $bulan);
                            $color = $item && $item->status == 'Sudah Bayar' ? 'green' : 'red';
                        @endphp
                        <div class="bar-item" title="{{ $bulan }}" style="background-color: {{ $color }}"></div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <h1>Pengaduan</h1>

                @if ($pengaduans->isEmpty())
                    <p style="padding: 10px;">Tidak ada pengaduan ditemukan.</p>
                @else
                    @foreach ($pengaduans as $pengaduan)
                        <div class="status-head">
                            <span
                                class="head toggle-status"
                                style="cursor: default; display: flex; align-items: center; width: 100%;"
                            >
                                <div class="judul-pengaduan">
                                    Judul Pengaduan: {{ $pengaduan->judul }}
                                </div>
                                <img
                                    src="{{ asset('image/icon-row/row.svg') }}"
                                    class="arrow-icon"
                                    alt="Toggle Arrow"
                                    style="cursor: pointer;"
                                />
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
                                            <img
                                                src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}"
                                                alt="{{ $step }}"
                                            />
                                            <span>
                                                {{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}
                                            </span>
                                        </div>

                                        @if ($index < count($statusSteps) - 1)
                                            <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtns = document.querySelectorAll('.toggle-status');

    toggleBtns.forEach(function (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const statusHead = this.closest('.status-head');
            const detailStatus = statusHead.querySelector('.detail-status');
            const arrowIcon = this.querySelector('.arrow-icon');

            const isAlreadyOpen = detailStatus.classList.contains('show');

            // Tutup semua dulu
            document.querySelectorAll('.detail-status.show').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.arrow-icon.rotate').forEach(el => el.classList.remove('rotate'));

            // Jika sebelumnya belum terbuka, buka; kalau sudah terbuka, jangan buka lagi (jadi toggle)
            if (!isAlreadyOpen) {
                detailStatus.classList.add('show');
                arrowIcon.classList.add('rotate');
            }
        });
    });
});
</script>

<script>
    document.getElementById('judul-pengaduan').addEventListener('click', function () {
        this.classList.toggle('truncate-text');
    });
</script>

</html>
