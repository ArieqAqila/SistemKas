$('#form-reset-password').submit(function (e) { 
    e.preventDefault();
    var data_password = new FormData($(this)[0])
    $.ajax({
        type: "POST",
        url: "/warga/reset-password",
        data: data_password,
        contentType: false,
        processData: false,
        success: function (response) {
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
                    location.href('/warga');
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
                $('.form-text').css('display', 'none');
                $('#' + key).addClass('is-invalid').parent().append(errorHtml);
            });
        }
    });
});