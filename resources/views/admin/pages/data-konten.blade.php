@extends('admin/index')

@section('title')
    eRTe 03 - Data Konten Kegiatan
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/konten.js')
@endsection

@section('konten-kegiatan')
    active
@endsection

@section('title-halaman')
    Data Konten Kegiatan
@endsection

@section('konten')

<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-house-user me-2"></i>Data Konten</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-konten"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Judul Kegiatan</th>
                <th>Penulis Kegiatan</th>
                <th>Tanggal Rilis Konten</th>
                <th>Gambar Konten</th>
                <th>Isi Konten Kegiatan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($data_konten as $item)
            <tr>
                <td class="tagihan-konten">{{ $no++; }}</td>
                <td class="tagihan-konten">{{ $item->judul_konten }}</td>
                <td class="tagihan-konten">{{ $item->user->nama_user}}</td>
                <td class="tagihan-konten">{{ DateHelper::formatDateIndonesia($item->tgl_konten) }}</td>
                <td><button class="btn btn-admin-primary sk-fs text-white preview-foto" data-foto="{{ $item->gambar }}"><i class="fa-solid fa-eye me-1"></i> Lihat</button></td>
                <td>
                    <a href="{{ route('view-kegiatan', $item->id_konten) }}" class="btn btn-admin-primary sk-fs text-white"><i class="fa-solid fa-eye me-1"></i> Lihat</button></a>
                </td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-konten" data-id-konten="{{ $item->id_konten }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-konten="{{ $item->id_konten }}"><i class="fa-solid fa-trash text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Konten</td>
                    <td>masih</td>
                    <td>kosong!</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Judul Kegiatan</th>
                <th>Penulis Kegiatan</th>
                <th>Tanggal Rilis Konten</th>
                <th>Gambar Konten</th>
                <th>Isi Konten Kegiatan</th>
                <th>Action</th>
            </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modal-tambah')
<form method="POST" enctype="multipart/form-data" id="form-tambah-konten">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-konten">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-house-user me-2"></i>Tambah Data Kegiatan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Penulis Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Masukan Penulis Kegiatan" value="{{ Auth::user()->nama_user }}" class="form-control" name="inPenulisKonten" id="inPenulisKonten" required disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Judul Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-font"></i>
                        </span>
                        <input type="text" placeholder="Masukkan Judul Kegiatan" class="form-control" name="inJudulKegiatan" id="inJudulKegiatan" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Gambar Kegiatan(Thumbnail)</label>
                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" class="form-control" name="inGambarKegiatan" id="inGambarKegiatan" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Isi Konten</label>
                    <div class="input-group">
                        <textarea class="form-control" placeholder="Masukkan Isi dari Konten Kegiatan" name="inIsiKonten" id="inIsiKonten" cols="30" rows="10" required></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Tanggal Konten Kegiatan Ditulis</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Tanggal Lahir Konten" class="form-control" name="inTglRilisKonten" id="inTglRilisKonten" required>
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
<form method="POST" enctype="multipart/form-data" id="form-edit-konten">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-konten">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Konten</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_konten" id="id_konten" hidden>
                <div class="mb-3">

                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Penulis Kegiatan</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" placeholder="Masukkan Penulis Kegiatan" class="form-control" name="editPenulisKonten" id="editPenulisKonten" required disabled>
                    </div>
                </div>
                <div class="mb-3">

                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Judul Kegiatan</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-font"></i>
                        </span>

                        <input type="text" placeholder="Masukkan Judul Kegiatan" class="form-control" name="editJudulKegiatan" id="editJudulKegiatan" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Gambar kegiatan (Thumbnail)</label>
                    <button class="btn btn-admin-primary text-white mb-2 preview-gambar-kegiatan"><i class="fa-solid fa-eye"></i> Lihat Foto</button>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-regular fa-image"></i>
                        </span>
                        <input type="file" accept="image/*" placeholder="Masukkan Gambar dari Kegiatan" class="form-control" name="editGambarKegiatan" id="editGambarKegiatan">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Isi konten</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            #
                        </span>
                         <textarea placeholder="Masukkan Isi dari Konten Kegiatan" class="form-control" name="editIsiKonten" id="editIsiKonten" cols="30" rows="10" required></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fa-solid fa-asterisk me-1 text-danger"></i>Tanggal konten kegiatan ditulis</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukkan Tanggal Kegiatan" class="form-control" name="editTglRilisKonten" id="editTglRilisKonten" required>
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