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

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  {{-- Basic Assets --}}
  @vite([
    'resources/assets/scss/index-warga.scss',

    'resources/js/app.js',
    'resources/assets/js/index-warga.js',
  ])

  {{-- Additional Assets --}}
  @yield('anotherJs')
</head>
<body>
  <div id="loading" class="loading-wrapper">
    <div class="spinner"></div>
  </div>
  <nav class="navbar navbar-dark navbar-expand-lg bg-secondary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="{{asset('images/rt3.png')}}" alt="Logo RT 03" width="60px" class="mx-3">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @warga
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard-warga') }}">
                  Home
                </a>
            </li>
            <li class="nav-item mx-lg-3">
              <a class="nav-link" href="{{ route('riwayat-saldo') }}">Riwayat Saldo Kas</a>
            </li>
        </ul>
        <div class="d-flex me-3">
          <a href="{{ route('profile-edit') }}" class="btn btn-outline-info me-3" id="click">Profile</a>
          <a href="{{ route('logout') }}" class="btn btn-outline-danger" id="click">Logout</a>
        </div>
        @endwarga
        
        @admin
        <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
        <div class="d-flex me-3">
          <a href="{{ route('index-konten') }}" class="btn btn-outline-info me-3" id="click">Kembali</a>
        </div>
        @endadmin
      </div>
    </div>
  </nav>

  <div class="container-fluid ps-0">
    @yield('konten')
  </div>

  <div class="footer-warga bg-secondary text-white text-center py-3">
    <span>Copyright © {{now()->year}} Asal Tabrak Team</span>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>