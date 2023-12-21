@extends('warga/index')

@section('title')
    eRTe 03 - Reset Password Warga
@endsection

@section('anotherJs')
@vite('resources/assets/js/warga/reset-password.js')
@endsection

@section('konten')
<div class="container d-flex justify-content-center">
  <div class="col-12 col-md-9 col-lg-5 mt-4">
    <div class="card">
      <h5 class="card-header">Reset Password</h5>
      <div class="card-body">
        <form method="POST" id="form-reset-password">
          {{ csrf_field() }}
          <div class="mb-3">
            <label for="oldPassword" class="form-label">Password Saat Ini</label>
            <input type="password" name="oldPassword" id="oldPassword" class="form-control" aria-describedby="oldPassword">
            <div id="oldPassword" class="form-text">
              Samakan dengan password anda saat ini.
            </div>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">Password Baru</label>
            <input type="password" name="newPassword" id="newPassword" class="form-control" aria-describedby="newPassword">
            <div id="newPassword" class="form-text">
              Password baru panjang karakternya wajib 6-12 karakter. Tidak boleh sama dengan password saat ini.
            </div>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" aria-describedby="confirmPassword">
            <div id="confirmPassword" class="form-text">
              Konfirmasi password baru anda.
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection