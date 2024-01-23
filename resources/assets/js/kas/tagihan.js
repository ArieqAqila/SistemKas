$(document).ready(function () {
    var id_tagihan;
    
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
            error: function (response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.responseJSON.message
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
        $("#id_tagihan").val(data.id_tagihan);
        $("#editNamaWarga").val(data.user.nama_user);
        $("#editNominalTagihan").val(data.user.kategori.nominal_kategori);
        $("#editNominalTertagih").val(data.nominal_tertagih);
        $("#editNominalSumbangan").val(data.nominal_sumbangan);
        $("#editTglTagihan").val(data.tgl_tagihan);
    }

    $('#table-admin').on('click', '.btn-edit', function () {
        const id_tagihan = $(this).data("id-tagihan");

        $.ajax({
            url: 'data-tagihan/' + id_tagihan,
            type: 'GET',
            cache: false,
            success: function (response) {
                populateForm(response.data);
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

