<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
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
                            <td><a href="{{ route('pengaduan.detail') }}">1</a></td>

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

        <!-- Tempat preview gambar -->
        <div id="preview"></div>
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

            <input type="file" id="buktiInput" accept="image/*" style="display: none;" onchange="previewImage(event)">
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

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ''; // Hapus pratinjau sebelumnya

    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Bungkus dalam div container
            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.display = 'inline-block';

            // Gambar
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '60px';
            img.style.borderRadius = '8px';
            img.style.marginTop = '10px';

            // Tombol X
            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '0px';
            closeBtn.style.right = '0px';
            closeBtn.style.color = 'white';
            closeBtn.style.backgroundColor = 'grey';
            closeBtn.style.borderRadius = '50%';
            closeBtn.style.padding = '2px 7px';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.fontWeight = 'bold';
            closeBtn.style.transform = 'translateY(-50%)';

            // Hapus gambar saat tombol X diklik
            closeBtn.onclick = function () {
                preview.innerHTML = ''; // Hapus konten preview
                document.getElementById('buktiInput').value = ''; // Reset input file
            };

            container.appendChild(img);
            container.appendChild(closeBtn);
            preview.appendChild(container);
        }
        reader.readAsDataURL(file);
    }
}
</script>


</body>
</html>
