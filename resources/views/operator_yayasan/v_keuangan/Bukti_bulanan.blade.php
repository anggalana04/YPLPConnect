<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Bulan {{ $bulan }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Bukti Pembayaran Bulan {{ $bulan }} Tahun {{ $tahun }}</h2>

    <table>
        <tr>
            <th>Jumlah Siswa</th>
            <td>{{ $jumlahSiswa }}</td>
        </tr>
        <tr>
            <th>Biaya per Siswa</th>
            <td>Rp {{ number_format($biayaPerSiswa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total</th>
            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        @if ($catatan)
        <tr>
            <th>Catatan</th>
            <td>{{ $catatan }}</td>
        </tr>
        @endif
    </table>

@if($buktiPath)
    <p style="margin-top: 50px;"><strong>Terlampir Bukti:</strong></p>
    <img src="{{ public_path('storage/' . $buktiPath) }}" alt="Bukti Pembayaran" style="max-width: 40%; margin-top: 10px;">
@endif

</body>
</html>
