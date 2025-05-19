<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

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

            <div class="bulat">
                <div class="bulat1"></div>
                <div class="bulat2"></div>
                <div class="bulat3"></div>
            </div>

            <div class="table-box">
                <table class="table-konten">
                    <thead id="table-header">
                        <tr>
                            <th>No</th>
                            <th>ID Pengajuan</th>
                            <th>NPA PGRI</th>
                            <th>Nama</th>
                            <th>Jenis SK</th>
                            <th>Alamat Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = [
                            ['PGJ001', '1234567890', 'Ahmad Fauzi', 'Pengangkatan', 'SMAN 1 Jakarta'],
                            ['PGJ002', '9876543210', 'Siti Rahma', 'Perpanjangan', 'SDN 05 Bandung'],
                            ['PGJ003', '1122334455', 'Rudi Hartono', 'Pensiun', 'SMP 3 Surabaya'],
                            ['PGJ004', '2233445566', 'Dewi Lestari', 'Mutasi', 'SMA 2 Yogyakarta'],
                            ['PGJ005', '3344556677', 'Budi Santoso', 'Kenaikan Pangkat', 'SDN 10 Semarang'],
                            ['PGJ006', '4455667788', 'Nina Marlina', 'Pengangkatan', 'SMPN 1 Malang'],
                            ['PGJ007', '5566778899', 'Arif Setiawan', 'Pensiun', 'SMAN 5 Medan'],
                            ['PGJ008', '6677889900', 'Fitri Amalia', 'Perpanjangan', 'SDN 15 Palembang'],
                            ['PGJ009', '7788990011', 'Agus Salim', 'Mutasi', 'SMA 4 Bandung'],
                            ['PGJ010', '8899001122', 'Lina Kurnia', 'Kenaikan Pangkat', 'SMPN 2 Bogor'],
                        ];
                        ?>

                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $row[0] ?></td>
                                <td><?= $row[1] ?></td>
                                <td><?= $row[2] ?></td>
                                <td><?= $row[3] ?></td>
                                <td><?= $row[4] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination">
                    <!-- Pagination buttons akan dibuat otomatis lewat JS -->
                </ul>
            </nav>
        </div>
    </div>
</body>

<script src="{{ asset('JavaScript/Pagination.js') }}"></script>
</html>
