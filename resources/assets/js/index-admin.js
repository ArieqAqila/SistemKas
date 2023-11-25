var id_warga;

var table = new DataTable('#table-admin', {
    scrollCollapse:true,
    scrollY:'50vh',
    scrollX:true,
});

$('#table-admin').on('click', '.tagihan-warga', function () {
    let data = table.row(this).data();
    id_warga = $(this).data('id-warga');
    $('#modal-tagihan').modal('show');
    $("#id_user").val(id_warga);
    $("#namaWarga").val(data[1]);
});

$('#sidebarCollapse').on('click', function() {
    $('#sk-sidebar, #sk-konten').toggleClass('aktif');
    $('#km-icon').toggleClass('fa-bars fa-xmark');
    $(this).toggleClass('close');
});