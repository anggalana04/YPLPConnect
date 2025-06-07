<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keputusan YPLP-PGRI Kabupaten Bogor</title>

  <link rel="shortcut icon" href="{{ public_path('image/logoYPLP/LogoYPLP.jpeg') }}" type="image/x-icon">
  <link rel="stylesheet" href='{{ public_path('css/PDF-SK_Layouts/SK_layouts.css') }}'>
</head>
<body>
  <div class="body-container">
    <div class="header">
      <img src="{{ public_path('image/logoYPLP/LogoYPLP.jpeg') }}" alt="">
    </div>

    <div class="judul">
      KEPUTUSAN PENGURUS PERWAKILAN YAYASAN PEMBINA LEMBAGA PENDIDIKAN<br>
      PERSATUAN GURU REPUBLIK INDONESIA KABUPATEN BOGOR
    </div>

    <div class="nomor">
      Nomor: 039.A/PWK.YPLP-PGRI KAB./SKEP/KS/2023
    </div>

    <div class="subjudul">
      Pengurus Perwakilan YPLP PGRI Kabupaten Bogor
    </div>

    <div class="section section1">
    <span class="label">Menimbang<span class="colon-menimbang"> :</span></span> Bahwa yang namanya tersebut dalam keputusan ini, memenuhi syarat dan dipandang cakap untuk diangkat dalam jabatan Kepala Sekolah.
    </div>

    <div class="section horizontal-paragraph">
    <span class="label">Mengingat<span class="colon-mengingat"> :</span></span>
    <span class="content">
        1. Undang-undang RI Nomor 8 Tahun 1974, jo Undang-undang RI Nomor 05 Th. 2014 tentang Aparatur Sipil Negara. <br>
        <span class="span-text-indent">2. Undang-undang RI Nomor 20 Tahun 2003, tentang Sistem Pendidikan Nasional. <br></span>
        <span class="span-text-indent">3. Undang-undang RI Nomor 14 Tahun 2005; tentang Guru dan Dosen. <br></span>
        <span class="span-text-indent">4. Peraturan Pemerintah RI Nomor 19 Tahun 2005; tentang Standar Nasional Pendidikan. <br></span>
        <span class="span-text-indent">5. Peraturan Pemerintah RI Nomor 74 Tahun 2008 tentang Guru. <br></span>
        <span class="span-text-indent">6. Keputusan Menteri Hukum dan HAM RI No: AHU-0006651.AH.01.04. Tahun 2016 tentang pengesahan pendirian <span class="span-text-indent"> badan hukum PGRI. <br></span>
        <span class="span-text-indent">7. Akta Notaris/PPAT Irma Bonita, S.H., No. 47, Tanggal 23 Maret 2018 tentang Hukum Pendirian YPLP Pusat PGRI. <br></span>
        <span class="span-text-indent">8. Keputusan Kongres PGRI Nomor IV/KONGRES/XXII/PGRI/2019; tentang AD/ART PGRI. <br></span>
        <span class="span-text-indent">9. Keputusan YPLP PGRI Pusat No. 23/YPPLP-PGRI/III/2020 tgl 16 Maret 2020; tentang AD/ART YPLP. <br></span>
    </span>
    </div>

    <div class="section">
    <span class="label">Memperhatikan<span class="colon-memperhatikan"> :</span></span> Hasil Keputusan Pleno Pengurus Perwakilan YPLP PGRI Kabupaten Bogor tanggal 03 Juli 2023.
    </div>


    <h3 class="memutuskan">MEMUTUSKAN</h3>

<table class="outer-table">
  <tr>
    <td class="menetapkan-cell">
      <strong>Menetapkan</strong>
        <table class="decision-table">
            <tr>
                <td class="main-item">PERTAMA</td>
                <td class="colon">:</td>
                <td class="sub-item">1. Nama dan gelar</td>
                <td class="colon">:</td>
                <td>{{ $guru->nama_gelar ?? $dokumen->nama }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="sub-item">2. Tempat, Tanggal lahir</td>
                <td class="colon">:</td>
                <td>
                    {{ $guru->tempat_lahir ?? $dokumen->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($guru->tanggal_lahir ?? $dokumen->tanggal_lahir)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="sub-item">3. Pendidikan Umum</td>
                <td class="colon">:</td>
                <td>{{ $guru->pendidikan_umum ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="sub-item">4. NUPTK</td>
                <td class="colon">:</td>
                <td>{{ $guru->nuptk ?? $dokumen->nuptk ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="sub-item">5. NPA-PGRI</td>
                <td class="colon">:</td>
                <td>{{ $guru->npa ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="sub-item">6. Unit Kerja</td>
                <td class="colon">:</td>
                <td>{{ $guru->unit_kerja ?? $dokumen->alamat_unit_kerja }}</td>
            </tr>
        </table>
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id'); // <- Tambahkan ini untuk mengatur locale ke Bahasa Indonesia

            $mulai = $dokumen->tanggal_mulai ? Carbon::parse($dokumen->tanggal_mulai) : now();
            $berakhir = $mulai->copy()->addYears(4)->subDay();
        @endphp

        <p class="effective-date">
            Terhitung mulai tanggal <strong>{{ $mulai->translatedFormat('d F Y') }}</strong>
            sampai dengan <strong>{{ $berakhir->translatedFormat('d F Y') }}</strong>
            diangkat menjadi Kepala <strong>{{ $sekolah->nama }}</strong>, Kabupaten Bogor.
        </p>
    </td>
  </tr>
</table>

    <div class="menetapkan-row">
      <div class="menetapkan-col">KEDUA</div>
      <div class="colon-point">:</div>
      <div class="paragraf-col">
        Sistem penggajian sebagaimana diktum PERTAMA, diserahkan kepada pimpinan satuan pendidikan ybs, dengan disesuaikan standar kemampuan.
      </div>
    </div>

    <div class="menetapkan-row">
      <div class="menetapkan-col">KETIGA</div>
      <div class="colon-point">:</div>
      <div class="paragraf-col">
        Apabila dikemudian hari ternyata terdapat kekeliruan dalam keputusan ini, akan diadakan perbaikan sebagaimana mestinya.
      </div>
    </div>

    <div class="menetapkan-row">
      <div class="menetapkan-col">KEEMPAT</div>
      <div class="colon-point-4">:</div>
      <div class="paragraf-col">
      Asli keputusan ini diberikan kepada yang bersangkutan untuk digunakan sesuai dengan keperluan.
      </div>
    </div>

    
<table class="table-akhiran">
  <tr>
    <td>
      <div class="tembusan">
        <strong>Tembusan Yth. :</strong><br>
        1. Pengurus Perwakilan YPLP PGRI Provinsi Jawa Barat;<br>
        2. Kepala Dinas Pendidikan Kabupaten Bogor;<br>
        3. Ketua PGRI Kabupaten Bogor;<br>
        4. Pengurus Cabang PGRI Tenjo;<br>
        5. Arsip.
      </div>
    </td>
    <td>
      <div class="ttd">
        <p>Ditetapkan di : Bogor</p>
        <p>Pada tanggal : {{ $mulai->translatedFormat('d F Y') }}</p>
        <p>Pengurus Perwakilan YPLP PGRI</p>
        <p>Kabupaten Bogor</p><br><br>
        <div class="ketua-yayasan">
            <p><strong>Drs. H. Nuradi, S.Sos., S.Pd.</strong></p>
            <p><em>NPA-PGRI: 10094000401</em></p>
        </div>
        </div>
    </td>
  </tr>
</table>

</div>
</body>
</html>
