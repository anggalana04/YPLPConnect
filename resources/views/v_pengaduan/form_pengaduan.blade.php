<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/pengaduan/form_pengaduan.css') }}">
    <title>Form Pengaduan</title>
</head>
<body>
    <div class="konten">
        <div class="form-box">
            <div class="sub-head-box">
                <input type="text" placeholder="Judul Pengajuan Masalah...">
            </div>

            <div class="isi-pengaduan">
                <textarea id="deskripsi" name="deskripsi" rows="4" cols="50" placeholder="Tulis pengaduan Anda..."></textarea>

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
                        <button class="batal">Batal</button>
                        <button class="bukti">Tambahkan Bukti</button>
                        <button class="kirim">kirim</button>
                    </div>
                    
                </div>
            </div>


        </div>
    </div>
</body>
</html>