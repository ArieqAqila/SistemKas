@extends('admin/index')

@section('title')
    eRTe 03 - Data Kategori
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/kategori.js')
@endsection

@section('kategori')
    active
@endsection

@section('title-halaman')
    Data Kategori
@endsection

@section('konten')

<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-user-group me-2"></i>Data Kategori</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-kategori"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Kategori</th>
                <th>Nominal Kategori</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($kategori as $item)
            <tr>
                <td>{{ $no++; }}</td>
                <td>{{ $item->nama_kategori }}</td>
                <td>Rp{{ $item->nominal_kategori }}</td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-kategori" data-id-kategori="{{ $item->id_kategori }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-kategori="{{ $item->id_kategori }}"><i class="fa-solid fa-trash text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Kategori</td>
                    <td>masih</td>
                    <td>kosong!</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Nama Kategori</th>
                <th>Nominal Kategori</th>
                <th>Action</th>
            </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modal-tambah')
<form method="POST" enctype="multipart/form-data" id="form-tambah-kategori">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-kategori">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Tambah Data Kategori</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nominal Kategori" class="form-control" name="inNamaKategori" id="inNamaKategori" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="number" placeholder="Masukan Tanggal Kategori" class="form-control" name="inNominalKategori" id="inNominalKategori" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-admin-danger text-white" data-bs-dismiss="modal">Close</button>
            <button type="reset" class="btn btn-admin-info text-white">Reset</button>
            <button type="submit" class="btn btn-admin-primary text-white">Save changes</button>
            </div>
        </div>
        </div>
    </div>
</form>
@endsection

@section('modal-edit')
<form method="POST" enctype="multipart/form-data" id="form-edit-kategori">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-kategori">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Edit Data Kategori</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_kategori" id="id_kategori" hidden>
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Kategori" class="form-control" name="editNamaKategori" id="editNamaKategori" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="number" placeholder="Masukan Nominal Kategori" class="form-control" name="editNominalKategori" id="editNominalKategori" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-admin-danger text-white" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-admin-primary text-white">Save changes</button>
            </div>
        </div>
        </div>
    </div>
</form>
@endsection