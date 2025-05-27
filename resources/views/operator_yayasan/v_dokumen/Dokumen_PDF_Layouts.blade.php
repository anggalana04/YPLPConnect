<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dokumen SK PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 10px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('image/logoYPLP/logo.svg') }}" alt="Logo">
        <h1>Dokumen Surat Keputusan</h1>
        <p>YPLPConnect</p>
    </div>

    <table>
        <tr>
            <th>ID Pengajuan</th>
            <td>{{ $dokumen->id }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $dokumen->nama }}</td>
        </tr>
        <tr>
            <th>NPA PGRI</th>
            <td>{{ $dokumen->nuptk }}</td>
        </tr>
        <tr>
            <th>Jenis SK</th>
            <td>{{ $dokumen->jenis_sk }}</td>
        </tr>
        <tr>
            <th>Alamat Unit Kerja</th>
            <td>{{ $dokumen->alamat_unit_kerja }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $dokumen->status }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $dokumen->created_at->format('d-m-Y') }}</td>
        </tr>
    </table>

</body>
</html>
