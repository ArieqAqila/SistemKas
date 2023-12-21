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
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="">
                  Home
                </a>
            </li>
            <li class="nav-item dropdown mx-lg-3">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kas
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Kas Masuk</a></li>
                <li><a class="dropdown-item" href="#">Kas Keluar</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Riwayat Saldo Kas</a></li>
              </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                  Kegiatan
                </a>
            </li>
        </ul>
        <div class="d-flex me-3">
          <a href="#" class="btn btn-outline-info me-3" id="click">Profile</a>
          <a href="{{ route('logout') }}" class="btn btn-outline-danger" id="click">Logout</a>
        </div>
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