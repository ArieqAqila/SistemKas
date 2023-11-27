@extends('admin/index')

@section('title')
    eRTe 03 - Data Kas Keluar
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/kas/kas-keluar.js')
@endsection

@section('kas-keluar')
    active
@endsection

@section('title-halaman')
    Data Kas Keluar
@endsection

@section('konten')
<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary">
      <div class="ms-4 text-admin-primary"><i class="fa-solid fa-user-group me-2"></i>Data Kas Keluar</div>
      <div>
        <button class="btn btn-admin-primary sk-fs text-white me-4 sk-fw-medium" data-bs-toggle="modal" data-bs-target="#modal-tambah-kKeluar"><i class="fa-solid fa-circle-plus me-2"></i>Tambah Data</button>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nominal Kas Keluar</th>
                <th>Tanggal Kas Keluar</th>
                <th>Deskripsi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($kas_keluar as $kas)
            <tr>
                <td>{{ $no++; }}</td>
                <td>Rp{{ $kas->nominal_keluar }}</td>
                <td>{{ $kas->tgl_keluar }}</td>
                <td>{{ $kas->deskripsi_keluar }}</td>
                <td>
                    <span class="table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-kKeluar" data-id-keluar="{{ $kas->id_keluar }}"><i class="fa-solid fa-user-pen text-admin-info"></i></span>
                    <span class="table-action btn btn-hapus" data-id-keluar="{{ $kas->id_keluar }}"><i class="fa-solid fa-trash text-admin-danger"></i></span>
                </td>
            </tr>
            @empty
                <tr>
                    <td>Data</td>
                    <td>Warga</td>
                    <td>masih</td>
                    <td>kosong!</td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th class="px-2">Rp{{ $total }}</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
      </table>
    </div>
    <div class="admin-table-header sk-h-auto flex-column flex-sm-row mt-4 px-4 py-sm-5 bg-admin-info fw-normal">
        <span class="text-admin-info pt-1"><i class="fa-solid fa-file-import me-2 mt-3 mt-sm-0"></i>Unduh Laporan </span>
        <form action="{{ route('downloadLaporan-kas-keluar') }}" method="POST">
            {{ csrf_field() }}
            <div class="row align-items-center pb-2 mt-3 mt-sm-0">
                <div class="col">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="form-text ms-1 text-danger" id="start-date">{{ $error }}</div>
                        @endforeach
                    @else
                    <div class="form-text ms-1" id="start-date">Tanggal Awal</div>
                    @endif
                    <div class="input-group">
                        <input type="date" name="startDate" class="form-control" aria-describedby="start-date">
                    </div>
                </div>
                <div class="col mt-2 mt-sm-0">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="form-text ms-1 text-danger" id="start-date">{{ $error }}</div>
                        @endforeach
                    @else
                    <div class="form-text ms-1" id="end-date">Tanggal Akhir</div>
                    @endif
                    <div class="input-group">
                        <input type="date" name="endDate" class="form-control" aria-describedby="end-date">
                    </div>
                </div>
                <div class="col-12 col-sm">
                    <div class="form-text ms-1" id="start-date">&nbsp;</div>
                    <div class="d-grid gap-2 mx-auto pb-2">
                        <button type="submit" class="btn btn-admin-info sk-fs text-white"><i class="fa-solid fa-download me-2"></i>Unduh</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection

@section('modal-tambah')
<form method="POST" enctype="multipart/form-data" id="form-tambah-kKeluar">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tambah-kKeluar">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Tambah Data Kas Keluar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nominal Kas Keluar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="number" placeholder="Keluaran Nominal Kas Keluar" class="form-control" name="inNominalKeluar" id="inNominalKeluar" max="10000000" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Kas Keluar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="date" placeholder="Keluaran Tanggal Kas Keluar" class="form-control" name="inTanggalKeluar" id="inTanggalKeluar" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="text" placeholder="Masukan Deskripsi" class="form-control" name="inDeskripsi" id="inDeskripsi" required>
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
<form method="POST" enctype="multipart/form-data" id="form-edit-kKeluar">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-kKeluar">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-user-group me-2"></i>Edit Data Kas Keluar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_keluar" id="id_keluar" hidden>
                <div class="mb-3">
                    <label class="form-label">Nominal Kas Keluar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="number" placeholder="Keluaran Nominal Kas Keluar" class="form-control" name="editNominalKeluar" id="editNominalKeluar" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Kas Keluar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <input type="date" placeholder="Keluaran Tanggal Kas Keluar" class="form-control" name="editTanggalKeluar" id="editTanggalKeluar" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="text" placeholder="Masukan Deskripsi" class="form-control" name="editDeskripsi" id="editDeskripsi" required>
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