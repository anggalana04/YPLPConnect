<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/Landing_Page/landing_page.css') }}">

    <title>Landing Page</title>
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
                <button><img class="logo-login" src="{{ asset('image/icon-Landing_Page/icon-login.svg') }}" alt="">Login</button>
            </div>
    </div>
</nav>
{{-- NAVBAR END --}}

{{-- BERANDA START --}}
<div id="Beranda" class="beranda">

</div>
{{-- BERANDA END --}}

{{-- ABOUT START --}}
<div id="about">
    <div class="about-desc">
        <div class="about-header">
            <h1>About Us</h1>
        </div>
        <p>
            Sebagai organisasi yang menaungi para pendidik, Perwakilan YPLP PGRI Kabupaten Bogor terus menjadi penggerak perubahan dalam dunia pendidikan. Melalui berbagai program, kami mendorong peningkatan keterampilan, memperjuangkan hak-hak profesi, dan membangun komunitas guru yang saling mendukung. Kami yakin setiap guru punya peran besar dalam membentuk masa depan bangsa. Lewat platform digital ini, mari kita wujudkan kolaborasi dan inovasi demi kemajuan pendidikan bersama.
        </p>

        {{-- Statistik START --}}
        <div class="statistik-container">
            <div class="statistik-box">
                <h2 class="angka">111</h2>
                <p class="label">Sekolah</p>
            </div>
            <div class="statistik-box">
                <h2 class="angka">111</h2>
                <p class="label">Pelajar</p>
            </div>
            <div class="statistik-box">
                <h2 class="angka">111</h2>
                <p class="label">Tenaga Pendidik</p>
            </div>
        </div>
        {{-- Statistik END --}}
    </div>
    <div class="foto-about">
    </div>
</div>
{{-- ABOUT END --}}

{{-- SERVICES START --}}
<div id="services">
    <h1 class="services-header">Our Services</h1>
        <div class="box-konten">
            <div class="sub-box-konten"><h1>Pembinaan Guru</h1></div>
            <div class="sub-box-konten"><h1>Tata Kelola Administasi</h1></div>
            <div class="sub-box-konten"><h1>Mendukung Komunitas</h1></div>
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
                        <span class="detail_contact">Jl. in aja dulu</span>
                    </div>
                    
                    <div class="sub-contact-info">
                        <img src="{{ asset('image/icon-Landing_Page/icon-contact-telpon.svg') }}" alt="">
                        <span class="detail_contact">08587684765736</span>
                    </div>
                    
                    <div class="sub-contact-info">
                        <img src="{{ asset('image/icon-Landing_Page/icon-contact-email.svg') }}" alt="">
                        <span class="detail_contact">yplppgri@gmail.com</span>
                    </div>
                    
                </div>
            </div>
            <div class="contact-box2"><img class="img-lokasi" src="{{ asset('image/Lokasi/lokasi.svg') }}" alt=""></div>
        </div>
    </div>
{{-- CONTACT US END --}}
</body>

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

</html>