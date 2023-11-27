$(document).ready(function () {
    const edit_btn = $('.btn-edit');
    const delete_btn = $('.btn-hapus');
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

    edit_btn.on("click", function () {
        id_kategori = $(this).data("id-kategori");

        $.ajax({
            url: 'data-kategori/' + id_kategori,
            type: 'GET',
            cache: false,
            success: function(response) {
                $("#id_kategori").val(response.data.id_kategori);
                $("#editNamaKategori").val(response.data.nama_kategori);
                $("#editNominalKategori").val(response.data.nominal_kategori);
            }
        });
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
        id_kategori = $(this).data("id_kategori");

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
                        url: "/admin/data-kategori/" + id_kategori,
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
        });
    });
});