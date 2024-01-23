@extends('warga/index')

@section('title')
    Selamat Datang di eRTe 03!
@endsection

@php
    $user = Auth::user();
    if (!$user->tagihan || $user->tagihan->status_tagihan === "Belum Lunas") {
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
<a href="{{ route('profile-warga') }}" class="status-warga mt-4 {{ $bgBody }}">
  @if ($user->foto_profile === NULL)
  <span class="profile-warga ms-4 me-3 text-white overflow-hidden d-flex justify-content-center align-items-center fs-4 fw-semibold">
    {!! strtoupper(substr($user->nama_user, 0, 2)) !!}
  </span>
  <div class="d-flex flex-column text-white">
    <span class="fs-5 fw-semibold"><i class="{{ $icon }}"></i> {{ Auth::user()->nama_user }}</span>
    <span>{{ $deskripsi }}</span>
  </div>
  @else
    <img src="{{asset('images/Profile Warga/'.Auth::user()->foto_profile)}}" alt="Profile Image Admin" class="profile-warga ms-4 me-3">
    <div class="d-flex flex-column text-white">
      <span class="fs-5 fw-semibold"><i class="{{ $icon }}"></i> {{ Auth::user()->nama_user }}</span>
      <span>{{ $deskripsi }}</span>
    </div>
  @endif
</a>

<div class="container">
  <h5 class="mt-5 border-bottom border-2 border-secondary pb-1">
    <span class="ms-2 text-secondary">Kegiatan eRTe 03</span>
  </h5>
  <div class="row gy-3 mt-2 mb-4">
    @foreach ($kegiatan as $item)
    <div class="col-auto col-sm-6 col-lg-4">
      <a href="{{ route('view-kegiatan', $item->id_konten) }}" class="card text-secondary">
        <img src="/images/Konten Kegiatan/{{ $item->gambar }}" class="card-img-top" alt="{{ $item->judul_konten }}">
        <div class="card-body">
          <h5 class="card-title">{{ $item->judul_konten }}</h5>
          <p class="card-text truncate">{!! Str::limit($item->isi_konten, 128, '...') !!}</p>
          <div class="fw-bold text-success mt-3">Lihat Kegiatan <i class="fa-solid fa-arrow-right ms-1 opacity-50"></i></div>
        </div>
        <div class="card-footer text-body-secondary">
          <span class=" float-start">{{ $item->user->nama_user }}</span>
          <span class=" float-end">{{ DateHelper::formatDateIndonesia($item->tgl_konten) }}</span>
        </div>
      </a>
    </div>
    @endforeach
  </div>

  <div class="mt-5 ms-2 ms-md-5 pb-2">
    {{ $kegiatan->links('vendor.pagination.default') }}
  </div> 
</div>
@endsection