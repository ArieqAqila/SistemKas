
$(document).ready(function () {
    var id_konten;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-konten').submit(function (e) { 
        e.preventDefault();
        var data_konten = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-konten",
            data: data_konten,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-konten').modal('show');
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
        $("#id_konten").val(data.id_konten);
        $("#editPenulisKonten").val(data.user.nama_user);
        $("#editJudulKegiatan").val(data.judul_konten);
        $("#editIsiKonten").val(data.isi_konten);
        $("#editTglRilisKonten").val(data.tgl_konten);
        $(".preview-gambar-kegiatan").click(function (e) { 
            e.preventDefault();
            if (data.gambar !== null) {
                previewFoto("/images/Konten Kegiatan/" + data.gambar);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Foto profile tidak ditemukan',                            
                })
            }                                         
        });
    }

    function previewFoto(fotoUrl) {
        if (fotoUrl !== null) {
            Swal.fire({
                imageUrl: fotoUrl,
                imageHeight: 180,
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

    function deleteKonten(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/data-konten/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data konten berhasil dihapus!',
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
                        text: 'Data konten tidak ditemukan!',
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

        const id_konten = $(this).data("id-konten");
        const gambar = $(this).data("foto");

        if (isEdit) {
            $.ajax({
                url: 'data-konten/' + id_konten,
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
                    deleteKonten(id_konten);
                }
            });
        } else if (isPreview) {
            previewFoto("/images/Konten Kegiatan/" + gambar);
        }
    });

    $("#form-edit-konten").submit(function (e) { 
        e.preventDefault();
        id_konten = $("#id_konten").val();
        var data_konten = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-konten/" + id_konten,
            data: data_konten,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-konten").on('hidden.bs.modal');
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