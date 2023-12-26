@extends('warga/index')

@section('title')
    eRTe 03 - Kegiatan {{ $konten->judul_konten }}
@endsection

@section('konten')
<div class="row row-cols-1 justify-content-center mt-4">
    <div class="col col-lg-9 ms-5">
        <span class="px-4 py-1 fs-7 text-white bg-admin-secondary fw-normal rounded-5">{{$konten->user->nama_user}}</span>
        <div class="fs-5 mt-1 ms-1 fw-semibold">{{ $konten->judul_konten }}</div>
        <span class="text-admin-primary ms-1">{{DateHelper::formatDateIndonesia($konten->tgl_konten)}}</span>
    </div>
    
    <div class="col col-lg-9">
        <div class="row row-cols-1 bg-white rounded-2 px-4 mt-3">
            <div class="col-lg-12 admin-ff-asap mt-4 mt-lg-0">
                <img src="{{ asset('/images/Konten Kegiatan/'.$konten->gambar) }}" alt="{{$konten->judul_konten}}" class="card-img-top rounded-3">
                <div class="admin-primary border-bottom border-admin-primary ps-2 fw-semibold text-admin-primary fs-5 py-2 mt-4">Tentang Kegiatan Ini</div>
                <div class="vol-desc mt-2 fs-6 mb-5">
                    {!! nl2br(e($konten->isi_konten)) !!}
                </div>
            </div>                
        </div>
    </div>
</div>
@endsection