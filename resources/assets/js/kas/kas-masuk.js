$(document).ready(function () {
    const reset_btn = $('.btn-reset');
    var id_masuk;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-kMasuk').submit(function (e) { 
        e.preventDefault();
        var kas_masuk = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/kas-masuk",
            data: kas_masuk,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-kMasuk').on('hidden.bs.modal');
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
        $("#id_masuk").val(data.id_masuk);
        $("#editNominalMasuk").val(data.nominal_masuk);
        $("#editTanggalMasuk").val(data.tgl_masuk);
        $("#editDeskripsi").val(data.deskripsi_masuk);
    }

    function deleteKasMasuk(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/kas-masuk/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data kas masuk berhasil dihapus!',
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
                        text: 'Data kas masuk tidak ditemukan!',
                        timer: 2100,
                        timerProgressBar: true,
                    });
                }
            }
        });
    }

    $('#table-admin').on('click', '.btn-edit, .btn-hapus', function () {
        var isEdit = $(this).hasClass('btn-edit');
        var isDelete = $(this).hasClass('btn-hapus');

        const id_masuk = $(this).data("id-masuk");

        if (isEdit) {
            $.ajax({
                url: 'kas-masuk/' + id_masuk,
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
                    deleteKasMasuk(id_masuk);
                }
            });
        }
    });

    $("#form-edit-kMasuk").submit(function (e) { 
        e.preventDefault();
        id_masuk = $("#id_masuk").val();
        var kas_masuk = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/kas-masuk/" + id_masuk,
            data: kas_masuk,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-kMasuk").on('hidden.bs.modal');
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

    reset_btn.on('click', function(){
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin mau reset data kas masuk?',
            text: 'Pastikan Semua Laporan yang Diperlukan Sudah Terunduh!',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/admin/kas-masuk/reset",
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Data kas masuk tidak ditemukan!',
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