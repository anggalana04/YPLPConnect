<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/Landing_Page/landing_page.css') }}">
    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">

    <title>YPLP PGRI</title>
</head>


<body>
    {{-- NAVBAR START --}}
      <nav class="navbar" id="navbar">
        <div class="konten-nav">
           <a href="#"> <img class="logo" src="{{ asset('image/logoYPLP/logo.svg') }}" alt="LogoYPLP">
           </a>
        <div class="headerNavbar">
            <h1>YPLP PGRI</h1>
            <p>KABUPATEN BOGOR</p>
        </div>
            <ul>
                <li>
                    <a href="#" id="">Beranda</a>
                </li>
                <li>
                    <a href="#about" id="">About</a>
                </li>
                <li>
                    <a href="#services" id="">Services</a>
                </li>
                <li>
                    <a href="#contact" id="">Contact</a>
                </li>
            </ul>

            <div class="login">
                <a href="{{ route('login') }}">
                <button><img class="logo-login" src="{{ asset('image/icon-Landing_Page/icon-login.svg') }}" alt="">Login</button>
                </a>
            </div>
    </div>
</nav>
{{-- NAVBAR END --}}

{{-- BERANDA START --}}
<div id="Beranda" class="beranda">
    <img class="slide" src="{{ asset('image/Image_LandingPage/gambar-header-pgri.jpeg') }}" alt="">
    <img class="slide" src="{{ asset('image/Image_LandingPage/img-index-2.svg') }}" alt="">
    <img class="slide" src="{{ asset('image/Image_LandingPage/img-index-3.svg') }}" alt="">
    <img class="slide" src="{{ asset('image/Image_LandingPage/img-index-1.svg') }}" alt="">
</div>

{{-- BERANDA END --}}

{{-- ABOUT START --}}
<div id="about">
    <div class="about-desc animate-left hidden">
        <div class="about-header">
            <h1>About Us</h1>
        </div>
        <p>
            Sebagai organisasi yang menaungi para pendidik, Perwakilan YPLP PGRI Kabupaten Bogor terus menjadi penggerak perubahan dalam dunia pendidikan. Melalui berbagai program, kami mendorong peningkatan keterampilan, memperjuangkan hak-hak profesi, dan membangun komunitas guru yang saling mendukung. Kami yakin setiap guru punya peran besar dalam membentuk masa depan bangsa. Lewat platform digital ini, mari kita wujudkan kolaborasi dan inovasi demi kemajuan pendidikan bersama.
        </p>

        {{-- Statistik START --}}
        <div class="statistik-container">
            <div class="statistik-box">
                <h2 class="angka">{{ $jumlahSekolah }}</h2>
                <p class="label">Sekolah</p>
            </div>
            <div class="statistik-box">
                <h2 class="angka">{{ $jumlahSiswa }}</h2>
                <p class="label">Pelajar</p>
            </div>
            <div class="statistik-box">
                <h2 class="angka">{{ $jumlahGuru }}</h2>
                <p class="label">Tenaga Pendidik</p>
            </div>
        </div>

        {{-- Statistik END --}}
    </div>
    <div class="foto-about animate-right hidden">
        <img src="{{ asset('image/Image_LandingPage/img-about.svg') }}" alt="">
    </div>
</div>
{{-- ABOUT END --}}



{{-- SERVICES START --}}
<div id="services">
    <h1 class="services-header">Our Services</h1>
        <div class="box-konten">
            <div class="sub-box-konten" style="background-color: #5F5E5E; color:white;"><h1>Pembinaan Guru</h1>
                <img class="img-box-konten" src="{{ asset('image/Image_LandingPage/img-Binaguru.svg') }}" alt="">
            </div>
            <div class="sub-box-konten" style="background-color: #D2D2D2; color;black;"><h1>Tata Kelola Administasi</h1>
                <img class="img-box-konten" src="{{ asset('image/Image_LandingPage/img-kelolaAdmin.svg') }}" alt="">
            </div>
            <div class="sub-box-konten" style="background-color: #5F5E5E; color:white;"><h1>Mendukung Komunitas</h1>
                <img class="img-box-konten" style="border: 1px solid black; border-radius:40px;" src="{{ asset('image/Image_LandingPage/img-komunitas.svg') }}" alt="">
            </div>
        </div>
</div>
{{-- SERVICES END --}}

{{-- CONTACT US START --}}
    <div id="contact">
        <h1 class="contact-header">Contact Us</h1>
        <div class="contact-konten">
            <div class="contact-box1">
                <h1 class="head-contact">Contact Information</h1>
                <div class="contact_info">
                    <div class="sub-contact-info">
                        <img src="{{ asset('image/icon-Landing_Page/icon-contact-location.svg') }}" alt="">
                        <span class="detail_contact">Jl. Pangeran Assogiri</span>
                    </div>

                    <div class="sub-contact-info">
                        <img src="{{ asset('image/icon-Landing_Page/icon-contact-telpon.svg') }}" alt="">
                        <span class="detail_contact">0218755887</span>
                    </div>

                    <div class="sub-contact-info">
                        <img src="{{ asset('image/icon-Landing_Page/icon-contact-email.svg') }}" alt="">
                        <span class="detail_contact">yplppgribgrkab@gmail.com</span>
                    </div>

                </div>
            </div>
            <div class="contact-box2">
                <a href="https://maps.app.goo.gl/hsGvNZGSwaNMx4W66">
                    <img class="img-lokasi" src="{{ asset('image/Lokasi/lokasi.svg') }}" alt="">
                </a>
            </div>
        </div>
    </div>
{{-- CONTACT US END --}}
</body>


<script src="{{ asset('JavaScript/ANIMASI/Animasi-AboutUs.js') }}"></script>
<script src="{{ asset('JavaScript/ANIMASI/Animasi-Services.js') }}"></script>
<script src="{{ asset('JavaScript/ANIMASI/Animasi-Contact.js') }}"></script>

<script>
    let prevScrollPos = window.pageYOffset;
    const navbar = document.getElementById("navbar");

    window.addEventListener("scroll", function () {
        const currentScrollPos = window.pageYOffset;

        if (prevScrollPos > currentScrollPos) {
            // Scroll ke atas -> tampilkan navbar
            navbar.style.top = "0";
        } else {
            // Scroll ke bawah -> sembunyikan navbar
            navbar.style.top = "-100px"; // sesuaikan dengan tinggi navbar
        }

        prevScrollPos = currentScrollPos;
    });
</script>

{{-- JS SLIDE OTOMATIS --}}
<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlides() {
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
        });

        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }

        slides[slideIndex - 1].classList.add('active');
        setTimeout(showSlides, 3000); // ganti slide setiap 3 detik
    }

    showSlides();
</script>
{{-- JS SLIDE OTOMATIS --}}

</html>
