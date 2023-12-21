@extends('warga/index')

@section('title')
    eRTe 03 - Reset Password Warga
@endsection

@php
    function tgl_indonesia($date){
        /* ARRAY u/ hari dan bulan */
        $Hari = array ("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",);
        $Bulan = array ("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        /* Memisahkan format tanggal bulan dan tahun menggunakan substring */
        $tahun 	 = substr($date, 0, 4);
        $bulan 	 = substr($date, 5, 2);
        $tgl	 = substr($date, 8, 2);
        $waktu	 = substr($date,11, 5);
        $hari	 = date("w", strtotime($date));
            
        $result = $tgl." ".$Bulan[(int)$bulan-1]." ".$tahun."";
        return $result;
    }
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
<a href="#" class="status-warga mt-4 {{ $bgBody }}">
  <img src="{{asset('images/Profile Warga/'.Auth::user()->foto_profile)}}" alt="Profile Image Admin" class="profile-warga ms-4 me-3">
  <div class="d-flex flex-column text-white">
    <span class="fs-5 fw-semibold"><i class="{{ $icon }}"></i> {{ Auth::user()->nama_user }}</span>
    <span>{{ $deskripsi }}</span>
  </div>
</a>

<div class="container">
  <h5 class="mt-5 border-bottom border-2 border-secondary pb-1">
    <span class="ms-2 text-secondary">Kegiatan eRTe 03</span>
  </h5>
  <div class="row gy-3 mt-2 mb-4">
    @foreach ($kegiatan as $item)
    <div class="col-auto col-sm-6 col-lg-4">
      <a href="#" class="card text-secondary">
        <img src="/images/Konten Kegiatan/{{ $item->gambar }}" class="card-img-top" alt="{{ $item->judul_konten }}">
        <div class="card-body">
          <h5 class="card-title">{{ $item->judul_konten }}</h5>
          <p class="card-text truncate">{!! Str::limit($item->isi_konten, 128, '...') !!}</p>
          <div class="fw-bold text-success mt-3">Lihat Kegiatan <i class="fa-solid fa-arrow-right ms-1 opacity-50"></i></div>
        </div>
        <div class="card-footer text-body-secondary">
          <span class=" float-start">{{ $item->user->nama_user }}</span>
          <span class=" float-end">{{ tgl_indonesia($item->tgl_konten) }}</span>
        </div>
      </a>
    </div>
    @endforeach
  </div>
</div>
@endsection