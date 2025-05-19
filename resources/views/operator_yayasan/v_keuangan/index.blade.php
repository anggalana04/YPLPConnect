<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/data_keuangan/DataKeuangan.css') }}">
    <title>Data Keuangan</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <h1>Data Keuangan</h1>
                <p>Lihat dan kelola data keuangan sekolah anda</p>
            </div>

            <div class="option-box-konten">
                <div class="kategori">
                    <select id="kategori" name="kategori">
                        <option value="">Pilih Tahun</option>
                        <option value="tahun">2000</option>
                        <option value="tahun">2001</option>
                        <option value="tahun">2002</option>
                    </select>
                </div>

                <div class="download">
                    <button>Download Recap</button>
                </div>
            </div>

            <div class="bulan-wrapper">
                <div class="bulan-list">
                    @php
                        $dataBulan = [
                            ['nama' => 'Januari', 'status' => 'hijau', 'detail' => 'Detail data Januari di sini...'],
                            ['nama' => 'Februari', 'status' => 'merah', 'detail' => 'Detail data Februari di sini...'],
                            ['nama' => 'Maret', 'status' => 'merah', 'detail' => 'Detail data Maret di sini...'],
                            ['nama' => 'April', 'status' => 'merah', 'detail' => 'Detail data April di sini...'],
                            ['nama' => 'Mei', 'status' => 'merah', 'detail' => 'Detail data Mei di sini...'],
                            ['nama' => 'Juni', 'status' => 'merah', 'detail' => 'Detail data Juni di sini...'],
                            ['nama' => 'Juli', 'status' => 'merah', 'detail' => 'Detail data Juli di sini...'],
                            ['nama' => 'Agustus', 'status' => 'merah', 'detail' => 'Detail data Agustus di sini...'],
                            ['nama' => 'September', 'status' => 'merah', 'detail' => 'Detail data September di sini...'],
                            ['nama' => 'Oktober', 'status' => 'merah', 'detail' => 'Detail data Oktober di sini...'],
                            ['nama' => 'November', 'status' => 'merah', 'detail' => 'Detail data November di sini...'],
                            ['nama' => 'Desember', 'status' => 'merah', 'detail' => 'Detail data Desember di sini...'],
                        ];
                    @endphp

                    @foreach ($dataBulan as $bulan)
                        <div class="bulan-item {{ $loop->iteration % 2 == 0 ? 'genap' : '' }}">
                            <img src="{{ asset('image/icon-Data_Keuangan/icon-Plus.svg') }}" alt="" class="icon-plus toggle-icon">
                            <span class="nama">{{ $bulan['nama'] }}</span>

                            <button class="status status-{{ $bulan['status'] }}">
                                <img src="{{ asset('image/icon-Data_Keuangan/icon-download.svg') }}" alt="Download Icon">
                            </button>
                        </div>

                        <div class="detail-collapsible">
                            <div class="detail-content">
                                <div class="detail-teks">
                                    <p><strong>Detail:</strong> Rp. 2000 x Jumlah Siswa</p>
                                    <p><strong>Total:</strong></p>
                                </div>

                                <div class="upload-button-container" style="display: flex; gap: 10px;">
                                    <label for="uploadBukti{{ $loop->iteration }}" class="upload-bukti-button">Cek Bukti</label>
                                    <input type="file" id="uploadBukti{{ $loop->iteration }}" class="upload-input" style="display: none;">

                                    <button class="bayar-button">Sudah Bayar</button>
                                    @if(Auth::user()->role == 'operator_yayasan')
                                        <label for="uploadBukti{{ $loop->iteration }}" class="upload-bukti-button">Cek Bukti</label>
                                        <input type="file" id="uploadBukti{{ $loop->iteration }}" class="upload-input" style="display: none;">
                                        <button class="bayar-button">Sudah Bayar</button>
                                    @else
                                        <label for="uploadBukti{{ $loop->iteration }}" class="upload-bukti-button">Upload Bukti</label>
                                        <input type="file" id="uploadBukti{{ $loop->iteration }}" class="upload-input" style="display: none;">
                                        <button class="bayar-button">Bayar</button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Rekening -->
    <div id="popupRekening" class="popup-rekening">
        <div class="popup-content">
            <h3>No Rekening</h3>
            <p>Bank ABC - 1234567890 a.n. Yayasan Pendidikan Contoh</p>
            <button onclick="tutupPopup()">Tutup</button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-icon').forEach(icon => {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();

                const bulanItem = this.parentElement;
                const detail = bulanItem.nextElementSibling;

                if (!detail || !detail.classList.contains('detail-collapsible')) return;

                const isOpen = detail.style.maxHeight && detail.style.maxHeight !== '0px';

                if (isOpen) {
                    detail.style.maxHeight = '0px';
                    this.src = "{{ asset('image/icon-Data_Keuangan/icon-Plus.svg') }}";
                } else {
                    detail.style.maxHeight = detail.scrollHeight + 'px';
                    this.src = "{{ asset('image/icon-Data_Keuangan/icon-Minus.svg') }}";
                }
            });
        });

        function tampilkanPopup() {
            document.getElementById('popupRekening').style.display = 'flex';
        }

        function tutupPopup() {
            document.getElementById('popupRekening').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const bayarButtons = document.querySelectorAll('.bayar-button');
            bayarButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    tampilkanPopup();
                });
            });
        });
    </script>
</body>
</html>
