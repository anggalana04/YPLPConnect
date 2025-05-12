<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/pengaduan/pengaduan.css') }}">
    <title>Pengaduan</title>
</head>
<body>
    @extends('v_layouts.index')

    <div class="konten">
        <div class="body-konten">
            <div class="head-body-konten">
                <div class="teks-body">
                    <h1>PENGADUAN</h1>
                    <p>Ajukan pengaduan jika sekolah anda mengalami masalah</p>
                </div>

                <button onclick="openModal()">Ajukan Pengaduan</button>
            </div>

            <div class="search-container">
                <div class="search-icon">
                    <img src="{{asset('image/search/search.svg') }}" alt="">
                </div>
                    <input type="text" placeholder="Cari Pengaduan..." class="search-input">
            </div>

            <div class="table-box">
                <table class="table-konten">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Deskripsi Pengaduan</th>
                            <th>ID Pengaduan</th>
                            <th>Tanggal Pengaduan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Keluhan tentang pelayanan customer service</td>
                            <td>PID-2023-001</td>
                            <td>10/05/2023</td>
                            <td><span class="status diproses">Diproses</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Laporan masalah teknis aplikasi</td>
                            <td>PID-2023-002</td>
                            <td>11/05/2023</td>
                            <td><span class="status selesai">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Pengaduan (Disembunyikan awalnya) -->
<div class="modal-pengaduan" id="modalPengaduan">
    <div class="form-box">
            <div class="sub-head-box">
                    <input type="text" placeholder="Judul Pengajuan Masalah">
            </div>

            <div class="sub-form-box">
                <div class="isi-pengaduan">
                    <textarea id="deskripsi" name="deskripsi" rows="4" cols="50" placeholder="Tulis pengaduan Anda..."></textarea>
                </div>

                <div class="button-pengaduan">
                    <div class="kategori">
                        <label for="kategori">Kategori Masalah</label>
                            <select id="kategori" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="kendala">Kendala Teknis</option>
                                <option value="pelayanan">Pelayanan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                    </div>

                    <div class="all-button">
                        <button class="batal" onclick="closeModal()">Batal</button>
                        <input type="file" id="buktiInput" accept="image/*" style="display: none;">
                        <button class="bukti" onclick="document.getElementById('buktiInput').click()">Tambahkan Bukti</button>
                        <button class="kirim">Kirim</button>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalPengaduan').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalPengaduan').style.display = 'none';
    }
</script>

</body>
</html>