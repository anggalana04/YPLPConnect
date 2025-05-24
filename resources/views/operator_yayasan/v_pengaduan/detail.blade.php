<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="stylesheet" href="{{ asset('css/pengaduan/detail-pengaduan.css') }}" />
    <title>Detail Pengaduan</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <h1>DETAIL PENGADUAN</h1>

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

            <div class="ket-status">
                <p>ID Pengaduan :</p>
                <p>Tanggal Pengaduan :</p>
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
                        <td>1</td>
                        <td>Keluhan tentang pelayanan customer service</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Laporan masalah teknis aplikasi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
