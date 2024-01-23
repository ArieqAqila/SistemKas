    var id_warga;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#inNoTelpWarga, #editNoTelpWarga').on('input', function() {
        // Remove non-numeric characters using a regular expression
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
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
                $('#modal-tambah-warga').modal('hide');
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
        $("#id_warga").val(data.id_user);
        $("#editNamaWarga").val(data.nama_user);
        $("#editUsernameWarga").val(data.username);
        $("#editPasswordWarga").attr('placeholder', 'Password Tersembunyi(Hidden)!');
        $("#editNoTelpWarga").val(data.notelp);
        $("#editTglLahirWarga").val(data.tgl_lahir);
        $("#editAlamatWarga").val(data.alamat);
        $("#editKategori").val(data.id_kategori);
    
        // Single event listener for preview-foto
        $(".preview-foto-warga").click(function (e) {
            e.preventDefault();
            if (data.foto_profile !== null) {
                previewFoto("/images/Profile Warga/" + data.foto_profile);
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

    function deleteWarga(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/data-warga/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data warga berhasil dihapus!',
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
                        text: 'Data warga tidak ditemukan!',
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

        const id_warga = $(this).data("id-warga");
        const foto_profile = $(this).data("foto");

        if (isEdit) {
            $.ajax({
                url: 'data-warga/' + id_warga,
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
                    deleteWarga(id_warga);
                }
            });
        } else if (isPreview) {
            previewFoto("/images/Profile Warga/" + foto_profile);
        }
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
                $("#modal-edit-warga").modal('hide');
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