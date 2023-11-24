const edit_btn = $('.btn-edit');
    const delete_btn = $('.btn-hapus');
    const preview_foto_btn = $('.preview-foto');
    var id_admin;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-admin').submit(function (e) { 
        e.preventDefault();
        var data_admin = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-admin",
            data: data_admin,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-admin').on('hidden.bs.modal');
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
        id_admin = $(this).data("id-admin");

        $.ajax({
            url: 'data-admin/' + id_admin,
            type: 'GET',
            cache: false,
            success: function(response) {
                $("#id_admin").val(response.data.id_user);
                $("#editNamaAdmin").val(response.data.nama_user);
                $("#editUsernameAdmin").val(response.data.username);
                $("#editPasswordAdmin").attr('placeholder', 'Password Tersembunyi(Hidden)!');
                $("#editNoTelpAdmin").val(response.data.notelp);
                $("#editTglLahirAdmin").val(response.data.tgl_lahir);
                $("#editAlamatAdmin").val(response.data.alamat);
                $(".preview-foto-admin").click(function (e) { 
                    e.preventDefault();
                    if (response.data.foto_profile !== null) {
                        Swal.fire({
                            imageUrl: "/images/Profile Admin/" + response.data.foto_profile,
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

    $("#form-edit-admin").submit(function (e) { 
        e.preventDefault();
        id_admin = $("#id_admin").val();
        var data_admin = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-admin/" + id_admin,
            data: data_admin,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-admin").on('hidden.bs.modal');
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
        id_admin = $(this).data("id-admin");

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
                        url: "/admin/data-admin/" + id_admin,
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data admin berhasil dihapus!',
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
                                    text: 'Data admin tidak ditemukan!',
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
                imageUrl: "/images/Profile Admin/" + foto_profile,
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