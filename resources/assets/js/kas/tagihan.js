$(document).ready(function () {
    const edit_btn = $('.btn-edit');
    
    $('#form-tagihan').submit(function (e) { 
        e.preventDefault();
        var data_warga = new FormData($(this)[0])
        $.ajax({
            type: "POST",
            url: "/admin/data-tagihan",
            data: data_warga,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-tagihan').on('hidden.bs.modal');
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
        id_tagihan = $(this).data("id-tagihan");

        $.ajax({
            url: 'data-tagihan/' + id_tagihan,
            type: 'GET',
            cache: false,
            success: function(response) {
                $("#id_tagihan").val(response.data.id_tagihan);
                $("#editNamaWarga").val(response.data.user.username);
                $("#editNominalTagihan").val(response.data.nominal_tagihan);
                $("#editNominalSumbangan").val(response.data.nominal_sumbangan);
                $("#editTglTagihan").val(response.data.tgl_tagihan);
            }
            
        });
    });

    $("#form-edit-tagihan").submit(function (e) { 
        e.preventDefault();
        id_tagihan = $("#id_tagihan").val();
        var data_tagihan = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: "/admin/data-tagihan/" + id_tagihan,
            data: data_tagihan,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT"
            },
            success: function (response) {
                $("#modal-edit-tagihan").on('hidden.bs.modal');
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
            error: function (response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.responseJSON.message,
                });
            }
        });
    });
});

