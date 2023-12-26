@extends('admin/index')

@section('title')
    eRTe 03 - Data Petugas
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/user/petugas.js')
@endsection

@section('data-petugas')
    active
@endsection

@section('title-halaman')
    Data Petugas
@endsection

@section('konten')
<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-user-nurse me-2"></i></i>Data Petugas</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-petugas"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Petugas</th>
                <th>Username</th>
                <th>Password</th>
                <th>Tanggal Lahir</th>
                <th>No. Telp</th>
                <th>Alamat</th>
                <th>Foto Profile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($petugas as $user)
            <tr>
                <td>{{ $no++; }}</td>
                <td>{{ $user->nama_user }}</td>
                <td>{{ $user->username }}</td>
                <td><b>HIDDEN</b></td>
                <td class="tagihan-warga">
                    @if ($user->tgl_lahir)
                    {{ DateHelper::formatDateIndonesia($user->tgl_lahir) }}
                    @else
                        <span class="fst-italic text-danger fw-semibold">NULL</span>
                    @endif
                </td>
                <td>{{ $user->notelp }}</td>
                <td>{{ $user->alamat }}</td>
                <td><button class="btn btn-admin-primary sk-fs text-white mb-2 preview-foto" data-foto="{{ $user->foto_profile }}"><i class="fa-solid fa-eye"></i> Lihat Foto</button></td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-petugas" data-id-petugas="{{ $user->id_user }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-petugas="{{ $user->id_user }}"><i class="fa-solid fa-user-xmark text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Petugas</td>
                    <td>masih</td>
                    <td>kosong!</td>
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
                <th>Nama Petugas</th>
                <th>Username</th>
                <th>Password</th>
                <th>Tanggal Lahir</th>
                <th>No. Telp</th>
                <th>Alamat</th>
                <th>Foto Profile</th>
                <th>Action</th>
            </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modal-tambah')
<form method="POST" enctype="multipart/form-data" id="form-tambah-petugas">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-petugas">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-nurse me-2"></i>Tambah Data Petugas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Nama Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Petugas" class="form-control" name="inNamaPetugas" id="inNamaPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Username Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Petugas" class="form-control" name="inUsernamePetugas" id="inUsernamePetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Password Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" placeholder="Masukan Password Petugas" class="form-control" name="inPasswordPetugas" id="inPasswordPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Petugas" class="form-control" name="inTglLahirPetugas" id="inTglLahirPetugas">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>No Telepon Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="Masukan No Telepon Petugas" class="form-control" name="inNoTelpPetugas" id="inNoTelpPetugas">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Alamat Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Petugas" class="form-control" name="inAlamatPetugas" id="inAlamatPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    
                    <label for="inFotoProfile" class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Foto Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" class="form-control" name="inFotoPetugas" id="inFotoPetugas" required>                                                
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
<form method="POST" enctype="multipart/form-data" id="form-edit-petugas">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-petugas">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-nurse me-2"></i>Edit Data Petugas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_petugas" id="id_petugas" hidden>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Nama Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Petugas" class="form-control" name="editNamaPetugas" id="editNamaPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Username Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Petugas" class="form-control" name="editUsernamePetugas" id="editUsernamePetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="text" placeholder="Masukan Password Petugas" class="form-control" name="editPasswordPetugas" id="editPasswordPetugas">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Petugas" class="form-control" name="editTglLahirPetugas" id="editTglLahirPetugas">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>No Telepon Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="Masukan No Telepon Petugas" class="form-control" name="editNoTelpPetugas" id="editNoTelpPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Alamat Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Petugas" class="form-control" name="editAlamatPetugas" id="editAlamatPetugas" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Petugas</label>
                    <button class="btn btn-admin-primary text-white mb-2 preview-foto-petugas"><i class="fa-solid fa-eye"></i> Lihat Foto</button>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" class="form-control" name="editFotoPetugas" id="editFotoPetugas">                                                
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