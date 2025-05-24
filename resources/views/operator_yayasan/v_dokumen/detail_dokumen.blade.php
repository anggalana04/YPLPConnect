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
                    <p>Mengajukan dan melihat file Surat Keputusan</p>
                </div>
            </div>

            <label for="">Status</label>

            <div class="box-status-step">
                <div class="status-step">
                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-email-status.svg') }}" alt="" />
                    <span>Terkirim</span>
                </div>
                <div class="status-step">
                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-diterima.svg') }}" alt="" />
                    <span>Diterima & Dilihat</span>
                </div>
                <div class="status-step">
                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-proses.svg') }}" alt="" />
                    <span>Diproses</span>
                </div>
                <div class="status-step">
                    <img src="{{ asset('image/icon-status&detail_dokumen/icon-selesai.svg') }}" alt="" />
                    <span>Selesai</span>
                </div>
            </div>

            <div class="ket-detail">
                <p for="">No : </p>
                <p for="">ID Pengajuan : </p>
                <p for="">NPA PGRI : </p>
                <p for="">Nama : </p>
                <p for="">Jenis SK : </p>
                <p for="">Alamat Kerja : </p>
            </div>

            <div class="download">
                <a href="" class="btn-download" download>Download</a>
            </div>
        </div>
    </div>
</body>
</html>
