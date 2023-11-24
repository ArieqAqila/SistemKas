@extends('admin/index')

@section('title')
    eRTe 03 - Data Warga
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/user/warga.js')
@endsection

@section('data-warga')
    active
@endsection

@section('title-halaman')
    Data Warga
@endsection

@section('konten')
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
@endphp

<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-user-group me-2"></i>Data Warga</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-warga"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Warga</th>
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
            @forelse ($warga as $user)
            <tr>
                <td class="tagihan-warga">{{ $no++; }}</td>
                <td class="tagihan-warga">{{ $user->nama_user }}</td>
                <td class="tagihan-warga">{{ $user->username }}</td>
                <td class="tagihan-warga"><b>HIDDEN</b></td>
                <td class="tagihan-warga">{!! tgl_indonesia($user->tgl_lahir) !!}</td>
                <td class="tagihan-warga">{{ $user->notelp }}</td>
                <td class="tagihan-warga">{{ $user->alamat }}</td>
                <td><button class="btn btn-admin-primary sk-fs text-white mb-2 preview-foto" data-foto="{{ $user->foto_profile }}"><i class="fa-solid fa-eye me-1"></i> Lihat</button></td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-warga" data-id-warga="{{ $user->id_user }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-warga="{{ $user->id_user }}"><i class="fa-solid fa-trash text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Warga</td>
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
                <th>Nama Warga</th>
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
<form method="POST" enctype="multipart/form-data" id="form-tambah-warga">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-warga">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Tambah Data Warga</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Warga" class="form-control" name="inNamaWarga" id="inNamaWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Warga" class="form-control" name="inUsernameWarga" id="inUsernameWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" placeholder="Masukan Password Warga" class="form-control" name="inPasswordWarga" id="inPasswordWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Warga" class="form-control" name="inTglLahirWarga" id="inTglLahirWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="Masukan No Telepon Warga" class="form-control" name="inNoTelpWarga" id="inNoTelpWarga">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Warga" class="form-control" name="inAlamatWarga" id="inAlamatWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="inFotoProfile" class="form-label">Foto Warga</label>
                    <input type="file" accept="image/*" class="form-control" name="inFotoWarga" id="inFotoWarga" required>                                                
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
<form method="POST" enctype="multipart/form-data" id="form-edit-warga">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-warga">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Edit Data Warga</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_warga" id="id_warga" hidden>
                <div class="mb-3">
                    <label class="form-label">Nama Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" placeholder="Masukan Nama Warga" class="form-control" name="editNamaWarga" id="editNamaWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="text" placeholder="Masukan Username Warga" class="form-control" name="editUsernameWarga" id="editUsernameWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="text" placeholder="Masukan Password Warga" class="form-control" name="editPasswordWarga" id="editPasswordWarga">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Warga" class="form-control" name="editTglLahirWarga" id="editTglLahirWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="Masukan No Telepon Warga" class="form-control" name="editNoTelpWarga" id="editNoTelpWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <input type="text" placeholder="Masukan Alamat Warga" class="form-control" name="editAlamatWarga" id="editAlamatWarga" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Warga</label>
                    <button class="btn btn-admin-primary text-white mb-2 preview-foto-warga"><i class="fa-solid fa-eye"></i> Lihat Foto</button>
                    <input type="file" accept="image/*" class="form-control" name="editFotoWarga" id="editFotoWarga">                                                
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