@extends('admin/index')

@section('title')
    eRTe 03 - Data Admin
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/user/admin.js')
@endsection

@section('data-admin')
    active
@endsection

@section('title-halaman')
    Data Admin
@endsection

@section('konten')
<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-user-tie me-2"></i></i>Data Admin</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-admin"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Admin</th>
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
            @forelse ($admin as $user)
            <tr>
                <td>{{ $no++; }}</td>
                <td>{{ $user->nama_user }}</td>
                <td>{{ $user->username }}</td>
                <td><b>HIDDEN</b></td>
                <td>{!! date('d F Y', $user->tgL_lahir) !!}</td>
                <td>{{ $user->notelp }}</td>
                <td>{{ $user->alamat }}</td>
                <td><button class="btn btn-admin-primary sk-fs text-white mb-2 preview-foto" data-foto="{{ $user->foto_profile }}"><i class="fa-solid fa-eye"></i> Lihat Foto</button></td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-admin" data-id-admin="{{ $user->id_user }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-admin="{{ $user->id_user }}"><i class="fa-solid fa-user-xmark text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Admin</td>
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
                <th>Nama Admin</th>
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
<form method="POST" enctype="multipart/form-data" id="form-tambah-admin">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-admin">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-tie me-2"></i></i>Tambah Data Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Admin" class="form-control" name="inNamaAdmin" id="inNamaAdmin" minlength="10" maxlength="35" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Admin" class="form-control" name="inUsernameAdmin" id="inUsernameAdmin" minlength="6" maxlength="15" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" placeholder="Masukan Password Admin" class="form-control" name="inPasswordAdmin" id="inPasswordAdmin" minlength="6" maxlength="12" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Admin" class="form-control" name="inTglLahirAdmin" id="inTglLahirAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="number" placeholder="Masukan No Telepon Admin" class="form-control" name="inNoTelpAdmin" id="inNoTelpAdmin" minlength="10" maxlength="15">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Admin" class="form-control" name="inAlamatAdmin" id="inAlamatAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="inFotoProfile" class="form-label">Foto Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" class="form-control" name="inFotoAdmin" id="inFotoAdmin" required>                                                
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
<form method="POST" enctype="multipart/form-data" id="form-edit-admin">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-admin">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-tie me-2"></i></i>Edit Data Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_admin" id="id_admin" hidden>
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Admin" class="form-control" name="editNamaAdmin" id="editNamaAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Admin" class="form-control" name="editUsernameAdmin" id="editUsernameAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="text" placeholder="Masukan Password Admin" class="form-control" name="editPasswordAdmin" id="editPasswordAdmin">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Admin" class="form-control" name="editTglLahirAdmin" id="editTglLahirAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="Masukan No Telepon Admin" class="form-control" name="editNoTelpAdmin" id="editNoTelpAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Admin" class="form-control" name="editAlamatAdmin" id="editAlamatAdmin" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Admin</label>
                    <button class="btn btn-admin-primary text-white mb-2 preview-foto-admin"><i class="fa-solid fa-eye"></i> Lihat Foto</button>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" class="form-control" name="editFotoAdmin" id="editFotoAdmin">                                                
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