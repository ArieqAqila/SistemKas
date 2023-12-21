var id_warga, nominal_tagihan;

var table = new DataTable('#table-admin', {
    scrollCollapse:true,
    scrollY:'50vh',
    scrollX:true,
});

$('#table-admin').on('click', '.tagihan-warga', function () {
    if ($(event.target).hasClass('action-button')) {
        return;
    }

    let data = table.row(this).data();

    id_warga = $(this).data('id-warga');
    nominal_tagihan = $(this).data('nominal-tagihan');

    

    $('#modal-tagihan').modal('show');
    $("#inNominalTagihan").val(nominal_tagihan);
    $("#id_user").val(id_warga);
    $("#namaWarga").val(data[1]);

    $(document).on('input', '#inTglTagihan', function() {
        // Get the value from inputX and calculate Y
        const x = parseInt($(this).val());
        const y = x * nominal_tagihan;

        // Update the value of inputY
        $('#inNominalTagihan').val(y); // Adjust the number of decimal places as needed
    });
});

$('#sidebarCollapse').on('click', function() {
    $('#sk-sidebar, #sk-konten').toggleClass('aktif');
    $('#km-icon').toggleClass('fa-bars fa-xmark');
    $(this).toggleClass('close');
});