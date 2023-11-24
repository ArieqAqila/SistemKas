$(document).ready(function () {
    const edit_btn = $('.btn-edit');
    const delete_btn = $('.btn-hapus');
    const preview_foto_btn = $('.preview-foto');
    var id_warga;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-warga').submit(function (e) { 
        e.preventDefault();
        var data_warga = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-warga",
            data: data_warga,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-warga').modal('show');
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
        id_warga = $(this).data("id-warga");

        $.ajax({
            url: 'data-warga/' + id_warga,
            type: 'GET',
            cache: false,
            success: function(response) {
                $("#id_warga").val(response.data.id_user);
                $("#editNamaWarga").val(response.data.nama_user);
                $("#editUsernameWarga").val(response.data.username);
                $("#editPasswordWarga").attr('placeholder', 'Password Tersembunyi(Hidden)!');
                $("#editNoTelpWarga").val(response.data.notelp);
                $("#editTglLahirWarga").val(response.data.tgl_lahir);
                $("#editAlamatWarga").val(response.data.alamat);
                $(".preview-foto-warga").click(function (e) { 
                    e.preventDefault();
                    if (response.data.foto_profile !== null) {
                        Swal.fire({
                            imageUrl: "/images/Profile Warga/" + response.data.foto_profile,
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

    $("#form-edit-warga").submit(function (e) { 
        e.preventDefault();
        id_warga = $("#id_warga").val();
        var data_warga = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-warga/" + id_warga,
            data: data_warga,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-warga").on('hidden.bs.modal');
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
        id_warga = $(this).data("id-warga");

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
                        url: "/admin/data-warga/" + id_warga,
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data warga berhasil dihapus!',
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
                                    text: 'Data warga tidak ditemukan!',
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
                imageUrl: "/images/Profile Warga/" + foto_profile,
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