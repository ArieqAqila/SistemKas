$(document).ready(function () {
    var id_kategori;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-kategori').submit(function (e) { 
        e.preventDefault();
        var kategori = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-kategori",
            data: kategori,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-kategori').modal('hide');
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
        $("#id_kategori").val(data.id_kategori);
        $("#editNamaKategori").val(data.nama_kategori);
        $("#editNominalKategori").val(data.nominal_kategori);
    }

    function deleteKategori(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/data-kategori/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data kas kategori berhasil dihapus!',
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
                        text: 'Data kas kategori tidak ditemukan!',
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

        const id_kategori = $(this).data("id-kategori");

        if (isEdit) {
            $.ajax({
                url: 'data-kategori/' + id_kategori,
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
                    deleteKategori(id_kategori);
                }
            });
        }
    });

    $("#form-edit-kategori").submit(function (e) { 
        e.preventDefault();
        id_kategori = $("#id_kategori").val();
        var kategori = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-kategori/" + id_kategori,
            data: kategori,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-kategori").modal('hide');
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