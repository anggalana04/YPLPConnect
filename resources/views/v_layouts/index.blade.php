<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="shortcut icon" href="{{ asset('image/logoYPLP/logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/v_layouts/v_layouts.css') }}">
    <title>@yield('title') - YPLP PGRI</title>

    @stack('styles')
</head>
<body>
    @yield('content')
    {{-- SIDEBAR START --}}
<div class="sidebar">
        <div class="head-sidebar">
            <img class="img-sidebar" src="{{ asset('image/logoYPLP/logo.svg') }}" alt="logoYPLP">
            <div class="head-sidebar-text">
                <h1>YPLP PGRI</h1>
                <h4>Kabupaten Bogor</h4>
            </div>
        </div>

        <div class="menu-sidebar">
            <ul class="menu-item">
                <img src="{{ asset('image/icon-sidebar/Icon-dashboard.svg') }}" alt="">
                <Span><a href="{{ route('dashboard') }}">Dashboard</a></Span>
            </ul>

            <ul class="menu-item">
                <a id="laporanToggle" data-bs-toggle="collapse" href="#laporanCollapse" role="button" aria-expanded="false" aria-controls="laporanCollapse" style="text-decoration: none; color: inherit;">
                    <img src="{{ asset('image/icon-sidebar/icon_laporan.svg') }}" alt="">
                    <Span> Data Sekolah</Span>
                    <img class="icon-dropdown" src="{{ asset('image/icon-sidebar/icon-dropdown.svg') }}" alt="">
                </a>
            </ul>

            <!-- PISAHKAN dropdown dari menu utama -->
            <div class="collapse" id="laporanCollapse">
                <ul class="nav flex-column">
                    <li><a class="dropdown-item" href="{{ route('siswa.index') }}">Siswa</a></li>
                    <li><a class="dropdown-item" href="{{ route('guru.index') }}">Guru</a></li>
                </ul>
            </div>

            <ul class="menu-item">
                <img src="{{ asset('image/icon-sidebar/icon-keuangan.svg') }}" alt="">
                <SPan><a href="{{ route('keuangan.index') }}">Keuangan</a></SPan>
            </ul>

            <ul class="menu-item">
                <img src="{{ asset('image/icon-sidebar/icon-dokumen.svg') }}" alt="">
                <SPan><a href="{{ route('dokumen.index') }}">Dokumen</a></SPan>
            </ul>

            <ul class="menu-item">
                <img src="{{ asset('image/icon-sidebar/icon-pengaduan.svg') }}" alt="">
                <SPan><a href="{{ route('pengaduan.index') }}">Pengaduan</a></SPan>
            </ul>

            @if (auth()->user()->role == 'operator_yayasan')
            <ul class="menu-item">
                <img src="{{ asset('image/icon-sidebar/icon-ManageUser.svg') }}" alt="">
                <SPan><a href="{{ route('users.index') }}">Users</a></SPan>
            </ul>
            @endif

            <form class="logout" method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="logout-btn"
                onclick="event.preventDefault(); this.closest('form').submit();">
                    <span class="logout-icon">
                        <img src="{{ asset('image/icon-sidebar/Icon-Logout.svg') }}" alt="Logout Icon" class="icon-img">
                    </span>
                    <span class="logout-text">LogOut</span>
                </a>
            </form>


            {{-- <form class="logout" method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
            </form> --}}
        </div>


</div>

    {{-- SIDEBAR END --}}
    @stack('scripts')
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const laporanToggle = document.getElementById("laporanToggle");
        const iconDropdown = laporanToggle.querySelector(".icon-dropdown");

        // Gunakan Bootstrap collapse event
        const collapseElement = document.getElementById('laporanCollapse');
        collapseElement.addEventListener('show.bs.collapse', function () {
            iconDropdown.classList.add("rotate");
        });
        collapseElement.addEventListener('hide.bs.collapse', function () {
            iconDropdown.classList.remove("rotate");
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</html>




{{-- <div class="menu-sidebar">
        <ul><img src="{{ asset('image/icon-sidebar/Icon-dashboard.svg') }}" alt=""> Dashboard</ul>

        <ul>
            <a id="laporanToggle" data-bs-toggle="collapse" href="#laporanCollapse" role="button" aria-expanded="false" aria-controls="laporanCollapse" style="text-decoration: none; color: inherit;">
                <img src="{{ asset('image/icon-sidebar/icon_laporan.svg') }}" alt="">
                Laporan
                <img class="icon-dropdown" src="{{ asset('image/icon-sidebar/icon-dropdown.svg') }}" alt="">
            </a>

            <div class="collapse" id="laporanCollapse">
                <ul class="nav flex-column">
                    <li><a class="dropdown-item" href="#">Keuangan</a></li>
                    <li><a class="dropdown-item" href="#">Data Sekolah</a></li>
                </ul>
            </div>

        </ul>
        <ul><img src="{{ asset('image/icon-sidebar/icon-dokumen.svg') }}" alt=""> Dokumen</ul>
        <ul><img src="{{ asset('image/icon-sidebar/icon-pengaduan.svg') }}" alt=""> Pengaduan</ul>
    </div> --}}
