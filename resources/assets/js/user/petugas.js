$(document).ready(function () {
    var id_petugas;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#inNoTelpPetugas, #editNoTelpPetugas').on('input', function() {
        // Remove non-numeric characters using a regular expression
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
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
                $('#modal-tambah-petugas').modal('hide');
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
        $("#id_petugas").val(data.id_user);
        $("#editNamaPetugas").val(data.nama_user);
        $("#editUsernamePetugas").val(data.username);
        $("#editPasswordPetugas").attr('placeholder', 'Password Tersembunyi(Hidden)!');
        $("#editNoTelpPetugas").val(data.notelp);
        $("#editTglLahirPetugas").val(data.tgl_lahir);
        $("#editAlamatPetugas").val(data.alamat);
        $("#editKategori").val(data.id_kategori);
    
        // Single event listener for preview-foto
        $(".preview-foto-petugas").click(function (e) {
            e.preventDefault();
            if (data.foto_profile !== null) {
                previewFoto("/images/Profile Petugas/" + data.foto_profile);
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

    function deletePetugas(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/data-petugas/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data petugas berhasil dihapus!',
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
                        text: 'Data petugas tidak ditemukan!',
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

        const id_petugas = $(this).data("id-petugas");
        const foto_profile = $(this).data("foto");

        if (isEdit) {
            $.ajax({
                url: 'data-petugas/' + id_petugas,
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
                    deletePetugas(id_petugas);
                }
            });
        } else if (isPreview) {
            previewFoto("/images/Profile Petugas/" + foto_profile);
        }
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
                $("#modal-edit-petugas").modal('hide');
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
});