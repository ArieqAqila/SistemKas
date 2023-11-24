<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu Utama</title>

    <link rel="stylesheet" href="{{asset('src/css/index.css')}}">

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  
    {{-- Sweetalert --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    
</head>
<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-primary">
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
                    <a class="nav-link active" href="">
                      Home
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="">
                      Kas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                      About
                    </a>
                </li>
            </ul>
            <div class="d-flex me-3">
                <a href="{{ route('logout') }}" class="btn btn-outline-secondary" id="click">logout</a>
            </div>
          </div>
        </div>
    </nav>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>