<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/pengaduan/detail-pengaduan.css') }}" />
    <title>Detail Pengaduan</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <h1>DETAIL PENGADUAN</h1>

        <label for="">Status</label>

        @php
            $statusSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
            $currentIndex = array_search($pengaduan->status, $statusSteps);
        @endphp

        <div class="status-container">
            <div class="box-status-step">
                @foreach ($statusSteps as $index => $step)
                    <div class="status-step {{ $index <= $currentIndex ? 'active' : '' }}">
                        <img src="{{ asset('image/icon-status&detail_dokumen/icon-' . $step . '.svg') }}" alt="{{ $step }}" />
                        <span>
                            {{ $step == 'diterima' ? 'Diterima & Dilihat' : ucfirst($step) }}
                        </span>
                    </div>

                    @if ($index < count($statusSteps) - 1)
                        <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                    @endif
                @endforeach
            </div>

            <div class="ket-status">
                <p><strong>ID Pengaduan : </strong>{{ $pengaduan->id }}</p>
                <p><strong>Tanggal Pengaduan : </strong>{{ \Carbon\Carbon::parse($pengaduan->created_at)->format('d-m-Y') }}</p>
            </div>
        </div>

        <div class="table-box">
            <table class="table-detail">
                <thead>
                    <tr>
                        <th>Deskripsi Pengaduan</th>
                        <th>Bukti Pengaduan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $pengaduan->deskripsi }}</td>
                        <td>
                            @if ($pengaduan->bukti_path)
                                <img src="{{ asset('storage/' . $pengaduan->bukti_path) }}" alt="Bukti Pengaduan" width="150" />
                            @else
                                *Tidak ada bukti
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
