<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  @yield('csrf')
  <title>@yield('title')</title>

  {{-- Additional CSS --}}
  @yield('anotherCss')

  {{-- Basic Assets --}}
  @vite([
    'resources/assets/scss/index-admin.scss',
    'resources/assets/scss/responsive-admin.scss',
    
    'resources/js/app.js',
    'resources/assets/js/index-admin.js',
  ])

  {{-- CKEditor 5 --}}
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script> --}}

  {{-- Additional Assets --}}
  @yield('anotherJs')
</head>
<body>
  @php
      $prefix = Auth::user()->hak_akses === 'admin'
                ? 'Profile Admin'
                : 'Profile Petugas'
  @endphp
  <div class="wrapper navbar">
    <div class="top-navbar">
          <div class="left-topbar">
            <img src="{{asset('images/'.$prefix.'/'.Auth::user()->foto_profile)}}" alt="Profile Image Admin" class="profile-admin-image">
            <div>{{ Auth::user()->nama_user }}</div>
          </div>
          <div class="right-topbar">
            <div class="topbar-caption">
              @yield('title-halaman')
              <span>Welcome to eRTe 3 Admin!</span>
            </div>
            <div class="topbar-caption">
              08:00 WIB
              <span>18 Desember 2023</span>
            </div>
          </div>
    </div>
    
    <div class="sidebar aktif" id="sk-sidebar">
      <div class="inner-sidebar">
        <a href="{{route('dashboard-admin')}}" class="side-items @yield('dashboard')"><i class="fa-solid fa-gauge-high me-2"></i>DASHBOARD</a>
        <a href="{{route('index-konten')}}" class="side-items @yield('konten-kegiatan')"><i class="fa-solid fa-pen-to-square me-2"></i>KONTEN KEGIATAN</a>

        @admin
        <p class="side-items"><i class="fa-solid fa-users me-2"></i>DATA USER</p>
        <ul class="flex-column">
          <li class="side-sub-items @yield('data-warga')">
            <a href="{{route('index-warga')}}"><i class="fa-solid fa-user-group me-2"></i>Data Warga</a>
          </li>
          <li class="side-sub-items @yield('data-petugas')">
            <a href="{{route('index-petugas')}}"><i class="fa-solid fa-user-secret me-2"></i>Data Petugas</a>
          </li>
          <li class="side-sub-items @yield('data-admin')">
            <a href="{{route('index-admin')}}"><i class="fa-solid fa-user-tie me-2"></i>Data Admin</a>
          </li>
        </ul>
        @endadmin
  
        <p class="side-items"><i class="fa-solid fa-chart-line me-2"></i>DATA KAS</p>
        <ul class="flex-column">
          <li class="side-sub-items @yield('tagihan-kas')">
            <a href="{{route('index-tagihan')}}"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Tagihan Kas</a>
          </li>
        @admin
          <li class="side-sub-items @yield('kategori')">
            <a href="{{route('index-kategori')}}"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Kategori</a>
          </li>
          <li class="side-sub-items @yield('kas-masuk')">
            <a href="{{ route('index-kas-masuk') }}"><i class="fa-solid fa-money-bill-wave me-2"></i>Kas Masuk</a>
          </li>
          <li class="side-sub-items @yield('kas-keluar')">
            <a href="{{ route('index-kas-keluar') }}"><i class="fa-solid fa-money-bill-transfer me-2"></i>Kas Keluar</a>
          </li>
        </ul>
        @endadmin

        <a href="{{ route('logout') }}" class="side-items py-3"><i class="fa-solid fa-right-from-bracket me-2"></i>LOGOUT</a>
  
      </div>
    </div>
    <button class="closebar close" id="sidebarCollapse">
      <i class="fa-solid fa-bars" id="km-icon"></i>
    </button>
  </div>

  <div class="wrapper content aktif" id="sk-konten">
    @yield('konten')

    <div class="admin-footer">
      <span>Copyright © 2023 Asal Tabrak Team</span>
    </div>
  </div>

  @yield('modal-tambah')

  @yield('modal-edit')
</body>
</html>