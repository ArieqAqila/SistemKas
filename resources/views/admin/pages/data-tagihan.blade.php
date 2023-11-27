@extends('admin/index')

@section('title')
    eRTe 03 - Data Tagihan
@endsection

@section('csrf')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('anotherJs')
@vite('resources/assets/js/kas/tagihan.js')
@endsection

@section('tagihan-kas')
    active
@endsection

@section('title-halaman')
    Data Tagihan
@endsection

@section('konten')

<div class="sk-admin-container mt-5">
  <div class="sk-admin-table-container border-admin-primary">
    <div class="admin-table-header bg-admin-primary d-flex px-4">
      <div class="text-admin-primary"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Tagihan Warga</div>
      <div>
        <form action="{{ route('downloadLaporan-tagihan') }}" method="POST">
            {{ csrf_field() }}
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="form-text ms-1 text-danger" id="start-date">{{ $error }}</div>
                @endforeach
            @endif
            @admin
            <div class="input-group">
                <button type="submit" class="btn btn-admin-info sk-fs text-white"><i class="fa-solid fa-download me-2"></i>Unduh Laporan</button>
            </div>
            @endadmin
        </form>
      </div>
    </div>
    <div class="table-body">
      <table id="table-admin" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Warga</th>
                <th>Tanggal Tagihan</th>
                <th>Nominal Tagihan</th>
                <th>Nominal Sumbangan</th>
                <th>Status</th>
                @admin
                <th>Aksi</th>
                @endadmin
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $nominal_tagihan = 0;
                //dd(date('Y-m'));
            @endphp
            @foreach ($warga as $tagihan_warga)
                <tr class="tagihan-warga" data-id-warga="{{ $tagihan_warga->id }}" data-nominal-tagihan="{{ $tagihan_warga->kategori->nominal_kategori }}">
                    
                    @if ($tagihan_warga->tgl_tagihan === null || date_format(date_create($tagihan_warga->tgl_tagihan), 'Y-m') <= date('Y-m'))
                        <td>{{ $no++ }}</td>
                        <td>{{ $tagihan_warga->nama_user }}</td>
                        <td>
                            @if ($tagihan_warga->tgl_tagihan === null)
                                #
                            @else
                                {!! date_format(date_create($tagihan_warga->tgl_tagihan), 'F Y') !!}
                            @endif
                        </td>
                        <td>Rp{{ $tagihan_warga->kategori->nominal_kategori }}</td>
                        <td>#</td>
                        <td>Belum Lunas</td>
                        @admin
                        <td class="action-button">
                        @if ($tagihan_warga->id_tagihan === null)
                        <span class="action-button table-action btn btn-edit mb-1 disabled" data-bs-toggle="modal" data-bs-target="#modal-edit-tagihan" data-id-tagihan="{{ $tagihan_warga->id_tagihan }}"><i class="action-button fa-solid fa-user-pen text-admin-info"></i></span>
                        @else
                        <span class="action-button table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-tagihan" data-id-tagihan="{{ $tagihan_warga->id_tagihan }}"><i class="action-button fa-solid fa-user-pen text-admin-info"></i></span>
                        @endif
                        </td>
                        @endadmin
                    @else
                        <td class="action-button">{{ $no++ }}</td>
                        <td class="action-button">{{ $tagihan_warga->nama_user }}</td>
                        <td class="action-button">{!! date_format(date_create($tagihan_warga->tgl_tagihan), 'F Y') !!}</td>
                        <td class="action-button">Rp{{ $tagihan_warga->kategori->nominal_kategori }}</td>
                        <td class="action-button">Rp{{ $tagihan_warga->nominal_sumbangan }}</td>
                        <td class="action-button">Lunas</td>
                        @admin
                        <td class="action-button"><span class="action-button table-action btn btn-edit mb-1" data-bs-toggle="modal" data-bs-target="#modal-edit-tagihan" data-id-tagihan="{{ $tagihan_warga->id_tagihan }}"><i class="action-button fa-solid fa-user-pen text-admin-info"></i></span></td>
                        @endadmin
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Nama Warga</th>
                <th>Tanggal Tagihan</th>
                <th>Nominal Tagihan</th>
                <th>Nominal Sumbangan</th>
                <th>Status</th>
                @admin
                <th>Aksi</th>
                @endadmin
            </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

{{-- Modal Tagihan --}}
<form method="POST" id="form-tagihan">
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-tagihan">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Tagihan Warga</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="id_user" id="id_user" hidden>
                        <input type="text" placeholder="Nama Warga" class="form-control" name="namaWarga" id="namaWarga" disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Tagihan Kas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </span>
                        <input type="number" placeholder="-" class="form-control" name="inNominalTagihan" id="inNominalTagihan" required readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal yang Dibayar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-coins"></i>
                        </span>
                        <input type="number" placeholder="Masukan Nominal yang Dibayar" class="form-control" name="inNominalDibayar" id="inNominalDibayar" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bayar Selama</label>
                    <div class="input-group">
                        <input min="1" type="number" placeholder="Masukan Lamanya Tagihan" class="form-control" name="inTglTagihan" id="inTglTagihan" required>
                        <span class="input-group-text">
                            Bulan
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-admin-danger text-white" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-admin-primary text-white">Konfirmasi</button>
            </div>
        </div>
        </div>
    </div>
</form>
@endsection

@section('modal-edit')
<form method="POST" enctype="multipart/form-data" id="form-edit-tagihan">
@method('PUT')
    {{ csrf_field() }}
    <div class="modal" tabindex="-1" id="modal-edit-tagihan">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Edit Data Tagihan Warga</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="id_tagihan" id="id_tagihan" hidden>
                <div class="mb-3">
                    <label class="form-label">Nama Warga</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" placeholder="Nama Warga" class="form-control" name="editNamaWarga" id="editNamaWarga" required disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Tagihan Kas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </span>
                        <input type="number" placeholder="Masukan Nominal Tagihan" class="form-control" name="editNominalTagihan" id="editNominalTagihan" required disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Sumbangan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-coins"></i>
                        </span>
                        <input type="number" placeholder="Masukan Nominal yang Dibayar" class="form-control" name="editNominalSumbangan" id="editNominalSumbangan" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Tagihan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-calendar"></i>
                        </span>
                        <input type="date" placeholder="Masukan Nominal Sumbangan" class="form-control" name="editTglTagihan" id="editTglTagihan" required>
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