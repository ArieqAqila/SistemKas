@extends('admin/index')

@section('title')
    eRTe 03 - Dashboard
@endsection

@section('dashboard')
    active
@endsection

@section('title-halaman')
    Dashboard
@endsection

@section('konten')
<div class="sk-admin-container sk-card">
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

<div class="sk-admin-container mt-4 mt-md-5">
  <div class="sk-admin-table-container border-admin-secondary">
    <div class="admin-table-header bg-admin-secondary">
      <div class="ms-4 text-admin-secondary"><i class="fa-solid fa-coins me-2"></i>Riwayat Kas Masuk & Keluar Bulan Ini</div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
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
                <td>{{ $item->jenis }}</td>
                <td>Rp{{ $item->nominal }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
            </tr>
            @empty
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
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