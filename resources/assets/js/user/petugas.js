$(document).ready(function () {
    const edit_btn = $('.btn-edit');
    const delete_btn = $('.btn-hapus');
    const preview_foto_btn = $('.preview-foto');
    var id_petugas;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-petugas').submit(function (e) { 
        e.preventDefault();
        var data_petugas = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-petugas",
            data: data_petugas,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-petugas').on('hidden.bs.modal');
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    text: 'Memuat ulang halaman website...',
                    timer: 800,
                    timerProgressBar: true,
                    didOpen: function() {
                        Swal.showLoading();
                    },
                    willClose: function() {
                        location.reload();
                    }
                });
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan!'
                });
                $.each(errors, function (key, value) { 
                    var errorHtml = '<span class="invalid-feedback" role="alert"><strong>' + value[0] + '</strong></span>';
                    $('#' + key).addClass('is-invalid').parent().append(errorHtml);
                });
            }
        });
    });

    edit_btn.on("click", function () {
        id_petugas = $(this).data("id-petugas");

        $.ajax({
            url: 'data-petugas/' + id_petugas,
            type: 'GET',
            cache: false,
            success: function(response) {
                $("#id_petugas").val(response.data.id_user);
                $("#editNamaPetugas").val(response.data.nama_user);
                $("#editUsernamePetugas").val(response.data.username);
                $("#editPasswordPetugas").attr('placeholder', 'Password Tersembunyi(Hidden)!');
                $("#editNoTelpPetugas").val(response.data.notelp);
                $("#editTglLahirPetugas").val(response.data.tgl_lahir);
                $("#editAlamatPetugas").val(response.data.alamat);
                $(".preview-foto-petugas").click(function (e) { 
                    e.preventDefault();
                    if (response.data.foto_profile !== null) {
                        Swal.fire({
                            imageUrl: "/images/Profile Petugas/" + response.data.foto_profile,
                            imageHeight: 400,
                            imageAlt: 'Foto Profile'
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Oops...',
                            text: 'Foto profile tidak ditemukan',                            
                        })
                    }                                         
                });
            }
        });
    });

    $("#form-edit-petugas").submit(function (e) { 
        e.preventDefault();
        id_petugas = $("#id_petugas").val();
        var data_petugas = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-petugas/" + id_petugas,
            data: data_petugas,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-petugas").on('hidden.bs.modal');
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    text: 'Memuat ulang halaman website...',
                    timer: 2100,
                    timerProgressBar: true,
                    didOpen: function() {
                        Swal.showLoading();
                    },
                    willClose: function() {
                        location.reload();
                    }
                });
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan!'
                });
            }
        });
    });

    delete_btn.on('click', function(){
        id_petugas = $(this).data("id-petugas");

        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin mau menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "/admin/data-petugas/" + id_petugas,
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data petugas berhasil dihapus!',
                                    text: 'Memuat ulang halaman website...',
                                    timer: 2100,
                                    timerProgressBar: true,
                                    didOpen: function() {
                                        Swal.showLoading();
                                    },
                                    willClose: function() {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Data petugas tidak ditemukan!',
                                    timer: 2100,
                                    timerProgressBar: true,
                                });
                            }
                        }
                    });
                }
        });
    });

    preview_foto_btn.on('click', function () {
        var foto_profile = $(this).data("foto");

        if (foto_profile !== null) {
            Swal.fire({
                imageUrl: "/images/Profile Petugas/" + foto_profile,
                imageHeight: 400,
                imageAlt: 'Foto Profile'
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Foto profile tidak ditemukan',                            
            })
        }
    });
});