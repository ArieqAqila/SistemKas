@extends('warga/index')

@section('title')
    eRTe 03 - Riwayat Saldo Kas
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

<div class="sk-admin-container sk-card mt-3 mt-lg-5">
    <div class="sk-admin-card px-4 border-admin-info">
      <div class="sk-admin-card-title">Total Kas Masuk</div>
      <div class="sk-admin-total-saldo text-admin-info">Rp{{ $total_kas_masuk }}</div>
    </div>

    <div class="sk-admin-card px-4 mx-lg-5 border-admin-danger">
      <div class="sk-admin-card-title">Total Kas Keluar</div>
      <div class="sk-admin-total-saldo text-admin-danger">Rp{{ $total_kas_keluar }}</div>
    </div>

    <div class="sk-admin-card px-4 border-admin-primary">
      <div class="sk-admin-card-title">Total Kas</div>
      <div class="sk-admin-total-saldo text-admin-primary">Rp{{ $total_kas_masuk - $total_kas_keluar }}</div>
    </div>
</div>

<div class="sk-admin-container mt-4 mt-md-5 mb-4">
    <div class="sk-admin-table-container border-admin-secondary">
      <div class="admin-table-header bg-admin-secondary">
        <div class="ms-4 text-admin-secondary"><i class="fa-solid fa-coins me-2"></i>Riwayat Pembayaran Kas</div>
      </div>
      <div class="table-body">
        <table id="table-warga" class="table table-striped" style="width:100%">
          <thead>
              <tr>
                  <th>No.</th>
                  <th>Nominal</th>
                  <th>Tanggal</th>
                  <th>Deskripsi</th>
              </tr>
          </thead>
          <tbody>
              @php $no = 1; @endphp
              @forelse ($riwayat_kas as $item)
              <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ Rupiah::format($item->nominal) }}</td>
                  <td>{{ DateHelper::formatDateIndonesia($item->tanggal) }}</td>
                  <td>{{ $item->deskripsi }}</td>
              </tr>
              @empty
              <tr>
                  <td>Saldo</td>
                  <td>Kosong</td>
                  <td></td>
                  <td></td>
              </tr>
              @endforelse
          </tbody>
          <tfoot>
              <tr>
                  <th>No.</th>
                  <th>Nominal</th>
                  <th>Tanggal</th>
                  <th>Deskripsi</th>
              </tr>
          </tfoot>
        </table>
      </div>
    </div>
</div>
@endsection