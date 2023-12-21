/* ClassicEditor
    .create( document.querySelector( '#editor' ), {
        toolbar: [ 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],
    } )
    .catch( error => {
        console.log( error );
    } ); */

    $(document).ready(function () {
        const edit_btn = $('.btn-edit');
        const delete_btn = $('.btn-hapus');
        const preview_foto_btn = $('.preview-foto');
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
    
        edit_btn.on("click", function () {
            id_konten = $(this).data("id-konten");
    
            $.ajax({
                url: 'data-konten/' + id_konten,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#id_konten").val(response.data.id_konten);
                    $("#editPenulisKonten").val(response.data.user.nama_user);
                    $("#editJudulKegiatan").val(response.data.judul_konten);
                    $("#editIsiKonten").val(response.data.isi_konten);
                    $("#editTglRilisKonten").val(response.data.tgl_konten);
                    $(".preview-gambar-kegiatan").click(function (e) { 
                        e.preventDefault();
                        if (response.data.gambar !== null) {
                            Swal.fire({
                                imageUrl: "/images/Konten Kegiatan/" + response.data.gambar,
                                imageHeight: 150,
                                imageAlt: 'Foto Profile'
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Oops...',
                                text: 'Foto profile tidak ditemukan',                            
                            })
                        }                                         
                    });
                }
            });
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
    
        delete_btn.on('click', function(){
            id_konten = $(this).data("id-konten");
    
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
                            url: "/admin/data-konten/" + id_konten,
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Data konten berhasil dihapus!',
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
                                        text: 'Data konten tidak ditemukan!',
                                        timer: 2100,
                                        timerProgressBar: true,
                                    });
                                }
                            }
                        });
                    }
            });
        });
    
        preview_foto_btn.on('click', function () {
            var foto_profile = $(this).data("foto");
    
            if (foto_profile !== null) {
                Swal.fire({
                    imageUrl: "/images/Konten Kegiatan/" + foto_profile,
                    imageHeight: 150,
                    imageAlt: 'Foto Profile'
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: 'Foto profile tidak ditemukan',                            
                })
            }
        });
    });