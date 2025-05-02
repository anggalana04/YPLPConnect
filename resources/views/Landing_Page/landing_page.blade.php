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
      <nav class="navbar">
        <div class="konten-nav">
           <a href="#"> <img class="logo" src="{{ asset('image/logoYPLP/logo.svg') }}" alt="LogoYPLP">
           </a> 
        <div class="headerNavbar">
            <h1>YPLP PGRI</h1>
            <p>KABUPATEN BOGOR</p>
        </div>
            <ul>
                <li>
                    <a href="#about" id="">About</a>
                </li>
                <li>
                    <a href="#services" id="">Services</a>
                </li>
                <li>
                    <a href="#works" id="">Works</a>
                </li>
                <li>
                    <a href="#contact" id="">Contact</a>
                </li>
            </ul>

            <div class="login">
                <button>Login</button>
            </div>
    </div>
</nav>
{{-- NAVBAR END --}}

{{-- FOTO START --}}
<div class="konten-foto"></div>
{{-- FOTO END --}}

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

{{-- WORKS START --}}
    <div id="works">
        <h1 class="works-header">WORKS</h1>
        <div class="works-konten">
            <div class="sub-works-konten"></div>
            <div class="sub-works-konten"></div>
            <div class="sub-works-konten"></div>
        </div>
    </div>
{{-- WORKS END --}}

{{-- CONTACT US START --}}
    <div id="contact">
        <h1 class="contact-header">Contact Us</h1>
        <div class="contact-konten">
            <div class="contact-box">Ini Kontak</div>
            <div class="contact-box">Ini lokasi</div>
        </div>
    </div>
{{-- CONTACT US END --}}
</body>
</html>