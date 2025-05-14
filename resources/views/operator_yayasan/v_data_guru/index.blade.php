<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/Data_Guru/data_guru.css') }}">
    <title>Data Guru</title>
</head>
<body>
    @extends('operator_yayasan.v_layouts.index')

    <div class="konten">
        <div class="box-konten">
            <div class="head-box-konten">
                <div class="teks-head-box-konten">
                    <h1>Data Guru</h1>
                    <p>Lihat Dan Kelola Data Guru Sekolah Anda</p>
                </div>

                <button>Upload Data Guru</button>
            </div>

            <div class="option-head-box">
                <div class="search-container">
                    <div class="search-icon">
                        <img src="{{asset('image/search/search.svg') }}" alt="">
                    </div>
                    <input type="text" placeholder="Cari Siswa" class="search-input">
                </div>
            </div>
            
            <div class="table-box">
                <table class="table-konten">
                    <thead id="table-header">
                        <tr>
                            <th>NUPTK</th>
                            <th>Nama Guru</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>No hp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                            <td>1234567890120001</td>
                            <td>Agus Santoso</td>
                            <td>Laki-laki</td>
                            <td>1980-04-12</td>
                            <td>Jl. Kenanga No. 14, Jakarta</td>
                            <td>081234567890</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120002</td>
                            <td>Sri Wahyuni</td>
                            <td>Perempuan</td>
                            <td>1975-11-20</td>
                            <td>Jl. Mawar No. 22, Bandung</td>
                            <td>082134567891</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120003</td>
                            <td>Budi Hartono</td>
                            <td>Laki-laki</td>
                            <td>1982-08-05</td>
                            <td>Jl. Melati No. 3, Surabaya</td>
                            <td>083145678912</td>
                            <td>Nonaktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120004</td>
                            <td>Rina Lestari</td>
                            <td>Perempuan</td>
                            <td>1988-02-15</td>
                            <td>Jl. Anggrek No. 7, Medan</td>
                            <td>084156789123</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120005</td>
                            <td>Hendra Wijaya</td>
                            <td>Laki-laki</td>
                            <td>1979-06-30</td>
                            <td>Jl. Cemara No. 9, Yogyakarta</td>
                            <td>085167891234</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120005</td>
                            <td>Hendra Wijaya</td>
                            <td>Laki-laki</td>
                            <td>1979-06-30</td>
                            <td>Jl. Cemara No. 9, Yogyakarta</td>
                            <td>085167891234</td>
                            <td>Aktif</td>
                        </tr>
                        <tr>
                            <td>1234567890120005</td>
                            <td>Hendra Wijaya</td>
                            <td>Laki-laki</td>
                            <td>1979-06-30</td>
                            <td>Jl. Cemara No. 9, Yogyakarta</td>
                            <td>085167891234</td>
                            <td>Aktif</td>
                        </tr>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>

<script>
    const tableBox = document.querySelector('.table-box');
    const tableHeader = document.getElementById('table-header');
    let lastScrollTop = 0;
    let isHidden = false;

    tableBox.addEventListener('scroll', function () {
        const currentScrollTop = tableBox.scrollTop;

        // Scroll down: hide header
        if (currentScrollTop > lastScrollTop + 10 && !isHidden) {
            tableHeader.style.transform = 'translateY(-100%)';
            tableHeader.style.opacity = '0';
            isHidden = true;
        }

        // Scroll up: show header
        if (currentScrollTop < lastScrollTop - 10 && isHidden) {
            tableHeader.style.transform = 'translateY(0)';
            tableHeader.style.opacity = '1';
            isHidden = false;
        }

        lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;
    });
</script>



</html>