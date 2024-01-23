    var id_admin;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#inNoTelpAdmin #editNoTelpAdmin').on('input', function() {
        // Remove non-numeric characters using a regular expression
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
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
                $('#modal-tambah-admin').modal('hide');
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

    function populateForm(data)
    {
        $("#id_admin").val(data.id_user);
        $("#editNamaAdmin").val(data.nama_user);
        $("#editUsernameAdmin").val(data.username);
        $("#editPasswordAdmin").attr('placeholder', 'Password Tersembunyi(Hidden)!');
        $("#editNoTelpAdmin").val(data.notelp);
        $("#editTglLahirAdmin").val(data.tgl_lahir);
        $("#editAlamatAdmin").val(data.alamat);
        $("#editKategori").val(data.id_kategori);
    
        // Single event listener for preview-foto
        $(".preview-foto-admin").click(function (e) {
            e.preventDefault();
            if (data.foto_profile !== null) {
                previewFoto("/images/Profile Admin/" + data.foto_profile);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Foto profile tidak ditemukan',
                });
            }
        });
    }

    function previewFoto(fotoUrl) {
        if (fotoUrl !== null) {
            Swal.fire({
                imageUrl: fotoUrl,
                imageHeight: 400,
                imageAlt: 'Foto Profile'
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Foto profile tidak ditemukan',
            });
        }
    }

    function deleteAdmin(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/data-admin/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data admin berhasil dihapus!',
                        text: 'Memuat ulang halaman website...',
                        timer: 2100,
                        timerProgressBar: true,
                        didOpen: function () {
                            Swal.showLoading();
                        },
                        willClose: function () {
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

    $('#table-admin').on('click', '.btn-edit, .btn-hapus, .preview-foto', function () {
        var isEdit = $(this).hasClass('btn-edit');
        var isDelete = $(this).hasClass('btn-hapus');
        var isPreview = $(this).hasClass('preview-foto');

        const id_admin = $(this).data("id-admin");
        const foto_profile = $(this).data("foto");

        if (isEdit) {
            $.ajax({
                url: 'data-admin/' + id_admin,
                type: 'GET',
                cache: false,
                success: function (response) {
                    populateForm(response.data);
                }
            });
        } else if (isDelete) {
            Swal.fire({
                icon: 'warning',
                title: 'Apakah anda yakin mau menghapus data ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteAdmin(id_admin);
                }
            });
        } else if (isPreview) {
            previewFoto("/images/Profile Admin/" + foto_profile);
        }
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
                $("#modal-edit-admin").modal('hide');
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