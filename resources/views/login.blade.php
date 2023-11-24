<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Login - eRTe 03</title>
    
    @vite(['resources/js/app.js', 'resources/assets/scss/login.scss'])
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="login-container">
        <div class="fw-medium text-primary fs-3 text-center mb-4">Login eRTe 03</div>
        <form action="{{ route('proses-login') }}" method="POST" class="col-10">
            {{ csrf_field() }}
            <div class="">
                <div class="mb-3">
                <label class="form-text text-black">Username</label>
                <input type="text" class="form-control" name="username">
                <div class="form-text"></div>
                </div>
                <div class="mb-3">
                <label class="form-text text-black">Password</label>
                <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="d-grid gap-2 mx-auto mt-4">
                <button type="submit" class="btn btn-primary text-white fw-medium">Login</button>
            </div>
        </form>
    </div>
</body>
</html>