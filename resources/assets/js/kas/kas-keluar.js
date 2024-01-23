$(document).ready(function () {
    const reset_btn = $('.btn-reset');
    var id_keluar;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-tambah-kKeluar').submit(function (e) { 
        e.preventDefault();
        var kas_keluar = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/kas-keluar",
            data: kas_keluar,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tambah-kKeluar').on('hidden.bs.modal');
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
        $("#id_keluar").val(data.id_keluar);
        $("#editNominalKeluar").val(data.nominal_keluar);
        $("#editTanggalKeluar").val(data.tgl_keluar);
        $("#editDeskripsi").val(data.deskripsi_keluar);
    }

    function deleteKasKeluar(id) {
        $.ajax({
            type: "DELETE",
            url: "/admin/kas-keluar/" + id,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data kas keluar berhasil dihapus!',
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
                        text: 'Data kas keluar tidak ditemukan!',
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

        const id_keluar = $(this).data("id-keluar");

        if (isEdit) {
            $.ajax({
                url: 'kas-keluar/' + id_keluar,
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
                    deleteKasKeluar(id_keluar);
                }
            });
        }
    });

    $("#form-edit-kKeluar").submit(function (e) { 
        e.preventDefault();
        id_keluar = $("#id_keluar").val();
        var kas_keluar = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/kas-keluar/" + id_keluar,
            data: kas_keluar,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-kKeluar").on('hidden.bs.modal');
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
            title: 'Apakah anda yakin mau reset data kas keluar?',
            text: 'Pastikan Semua Laporan yang Diperlukan Sudah Terunduh!',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/admin/kas-keluar/reset",
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