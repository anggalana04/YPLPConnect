<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Pembayaran</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Rekap Pembayaran Bulanan</h2>
    <p>Sekolah : {{ $namaSekolah }}</p>
    <p>Tahun : {{ $tahun }}</p>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $item)
                <tr>
                    <td>{{ $item['bulan'] }}</td>
                    <td>
                        {{ $item['status'] === 'Lunas' ? 'Lunas' : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
