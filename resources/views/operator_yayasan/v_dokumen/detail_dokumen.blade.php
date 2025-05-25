<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/Dokumen/detail_dokumen.css') }}" />
    <title>Detail Dokumen</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Dokumen SK</h1>
                </div>
            </div>

            <label for="">Status</label>

            @php
                $statusSteps = ['terkirim', 'diterima', 'diproses', 'selesai'];
                $currentIndex = array_search($dokumen->status, $statusSteps);
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
            </div>

            <div class="ket-detail">
                <p id="no"></p>
                <p><strong>ID Pengajuan : </strong>{{ $dokumen->id }}</p>
                <p><strong>NPA PGRI : </strong>{{ $guru->nuptk }}</p>
                <p><strong>Nama : </strong>{{ $dokumen->nama }}</p>
                <p><strong>Jenis SK : </strong>{{ $dokumen->jenis_sk }}</p>
                <p><strong>Alamat Kerja : </strong>{{ $dokumen->alamat_unit_kerja }}</p>
            </div>

            <div class="download">
                <a href="{{ route('dokumen.download', $dokumen->id) }}" class="btn-download">Download</a>
            </div>
        </div>
    </div>

</body>
    <script>
        document.querySelectorAll('.ket-detail #no').forEach((p, i) => {
            p.innerHTML = '<strong>No :</strong> ' + (i + 1);
        });

    </script>
</html>
