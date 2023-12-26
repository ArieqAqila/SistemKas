@extends('warga/index')

@section('title')
    eRTe 03 - Reset Password Warga
@endsection

@php
    $date = Auth::user()->tgl_lahir;
    $user = Auth::user();
    if ($user->tagihan->status_tagihan === "Belum Lunas") {
      $icon = "fa-solid fa-circle-xmark me-2";
      $deskripsi = "Belum Membayar Iuran Kas Bulan Ini!";
      $bgBody = "bg-danger";
    } else {
      $icon = "fa-solid fa-circle-check me-2";
      $deskripsi = "Sudah Membayar Iuran Kas Bulan Ini!";
      $bgBody = "bg-success";
    }
@endphp

@section('konten')
@if (session('success'))
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                {{ session('success') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.toast').toast('show');
        });
    </script>
@endif
{{-- Modal --}}
    <div class="modal fade ff-asap" id="ubahUsername" tabindex="-1" aria-labelledby="modalEditUsername" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile-edit') }}" method="POST">
            @csrf
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEditUsername">Ubah Username</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">          
                <div class="mb-3">
                    <label for="editUsername" class="form-label fw-semiBold">Username</label>
                    <input type="text" name="editUsername" class="form-control" id="editUsername" minlength="6" maxlength="15" aria-describedby="usernameUnique" value="{{Auth::user()->username}}">
                    <div id="usernameUnique" class="form-text">Username bersifat "UNIK". Pastikan username anda sudah benar</div>
                </div>          
            </div>
            <div class="modal-footer">          
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </div>


    <div class="modal fade ff-asap" id="editNama" tabindex="-1" aria-labelledby="modalEditNama" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile-edit') }}" method="POST">
            @csrf
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEditNama">Ubah Nama Lengkap</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editNama" class="form-label fw-semiBold">Nama Lengkap</label>
                    <input type="text" name="editNama" class="form-control" id="editNama" aria-describedby="namaUser" maxlength="30" value="{{Auth::user()->nama_user}}">
                    <div id="namaUser" class="form-text">Pastikan nama anda sudah benar.</div>
                </div>                    
            </div>
            <div class="modal-footer">          
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </div>


    <div class="modal fade ff-asap" id="editTglLahir" tabindex="-1" aria-labelledby="modalEditTglLahir" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile-edit') }}" method="POST">
            @csrf
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEditTglLahir">Ubah Tanggal Lahir</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                <div class="mb-3">
                    <label for="editTglLahir" class="form-label fw-semiBold">Tanggal Lahir</label>
                    <input type="date" name="editTglLahir" class="form-control" id="editTglLahir" aria-describedby="tglLahir" value="{{Auth::user()->tgl_lahir}}">
                    <div id="tglLahir" class="form-text">Pastikan tanggal lahir anda sudah benar.</div>
                </div>
            </div>
            <div class="modal-footer">          
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </div>


    <div class="modal fade ff-asap" id="ubahEmail" tabindex="-1" aria-labelledby="modalEditEmail" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile-edit') }}" method="POST">
            @csrf
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEditEmail">Ubah Email</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                            
                <div class="mb-3">
                    <label for="editEmail" class="form-label fw-semiBold">Email</label>
                    <input type="email" name="ubahEmail" class="form-control" id="editEmail" aria-describedby="emailUser" value="{{Auth::user()->email}}">
                    <div id="emailUser" class="form-text">Pastikan email anda sudah benar.</div>
                </div>  
            </div>
            <div class="modal-footer">          
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </div>


    <div class="modal fade ff-asap" id="editNoTelp" tabindex="-1" aria-labelledby="modalEditNoTelp" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile-edit') }}" method="POST">
            @csrf
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEditNoTelp">Nomor Telepon</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editNoTelp" class="form-label fw-semiBold">Nomor Telepon</label>
                    <input type="text" name="editNoTelp" class="form-control" id="editNoTelp" aria-describedby="noTelp" value="{{Auth::user()->notelp}}">
                    <div id="noTelp" class="form-text">Pastikan nomor telepon anda sudah benar.</div>
                </div>                                        
            </div>
            <div class="modal-footer">          
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </div>
{{-- Modal --}}

<div class="row row-cols-1 row-cols-lg-2 flex-column flex-lg-row justify-content-lg-between">
    <div class="col-lg-3 mt-3 mt-lg-0">
        <div class="mt-5 mt-md-0">
            <a href="#" class="status-warga mt-4 {{ $bgBody }}">
                <img src="{{asset('images/Profile Warga/'.Auth::user()->foto_profile)}}" alt="Profile Image Admin" class="profile-warga ms-4 me-3">
                <div class="d-flex flex-column text-white">
                  <span class="fs-5 fw-semibold"><i class="{{ $icon }}"></i> {{ Auth::user()->nama_user }}</span>
                  <span>{{ $deskripsi }}</span>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-9 mt-4">
        <div class="bg-white rounded mt-5 mt-md-0 border border-gray-500">
            <div class="d-flex">
                <a href="javascript:void(0)" class="px-4 py-2 fw-semiBold text-primary">Biodata Anda</a>
            </div>
            <hr class="m-0">
            <div class="row row-cols-1 row-cols-lg-2">
                <div class="col-lg-4">
                    <form action="{{ route('profile-edit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="m-3 p-3 bg-white rounded-3 border border-gray-500 shadow-sm">
                        @if (Auth::user()->foto_profile == null)
                            <span id="txtImage">Tidak ada foto profile.</span>
                            <img src="" alt="Your Profile" id="previewImage" class="img-fluid" style="max-height: 300px;" hidden>
                        @else
                            <img src="{{asset('images/Profile Warga/'.Auth::user()->foto_profile)}}" alt="Your Profile" class="img-fluid" id="previewImage" style="max-height: 300px;">
                        @endif
                        
                        <div class="d-grid gap-2 mt-3">
                            <label for="editProfilePic" class="btn btn-outline-primary rounded-2 fw-medium fs-7">
                                Pilih Foto
                                <input name="editProfilePic" type="file" id="editProfilePic" accept="image/png, image/jpeg, image/jpg" max="10MB" hidden>
                            </label>                                                         
                        </div>
                        <div class="fs-8 mt-2 text-gray-100">
                            Besar file: maksimum 10.000.000 bytes (10 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG
                        </div>
                    </div>
                    <div class="d-grid gap-2 m-3">
                        <button class="btn btn-info text-white fs-7" type="submit">Simpan Foto Profile</button>
                    </div>
                    </form>
                </div>
                <div class="col-lg-7 m-3">
                    <div class="mt-4 fw-semiBold text-gray-100">Ubah Biodata</div>
                    <div class="">
                        <dl class="row gy-1 ms-1 mt-1 fs-7">
                            <dt class="col-md-3 fw-medium">Username</dt>
                            <dd class="col-md-8 p-0">
                                {{ Auth::user()->username }}
                                <button class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#ubahUsername">Ubah Username</button>
                            </dd>                                

                            <dt class="col-md-3 fw-medium">Nama</dt>
                            <dd class="col-md-8 p-0">
                                {{ Auth::user()->nama_user }}
                                <button class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#editNama">Ubah</button>
                            </dd>                                                               
        
                            <dt class="col-md-3 fw-medium">Tanggal Lahir</dt>
                            <dd class="col-md-8 p-0">
                                @if (Auth::user()->tgl_lahir == null)
                                    <button class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#editTglLahir">Masukkan tanggal lahir anda.</button>
                                @else
                                    {{ DateHelper::formatDateIndonesia($date) }}
                                    <button class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#editTglLahir">Ubah Tgl Lahir</button>
                                @endif                                    
                            </dd>                                                    
                        </dl>
                    </div>

                    <div class="mt-4 fw-semiBold text-gray-100">Ubah Kontak</div>
                    <div class="">
                        <dl class="row gy-1 ms-1 mt-1 fs-7">                                                            
                            <dt class="col-md-3 fw-medium">No. Telepon</dt>
                            <dd class="col-md-8 p-0">
                                {{ Auth::user()->notelp }}
                                <button class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#editNoTelp">Ubah</button>
                            </dd>
                            
                            
                            <dt class="col-8 col-md-7 fw-medium mt-3">
                                <a href="{{ route('reset-password') }}" class="btn btn-warning text-white fw-medium px-4">Reset Password</a>
                            </dt>
                        </dl>
                    </div>
                </div>                                       
            </div>
        </div>
    </div>
</div>
@endsection