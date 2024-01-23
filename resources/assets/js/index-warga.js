document.addEventListener('DOMContentLoaded', function () {
    // Hide the spinner when all assets are loaded
    document.getElementById('loading').style.display = 'none';
});

var table = new DataTable('#table-warga', {
    scrollCollapse:true,
    scrollY:'50vh',
    scrollX:true,
});

$('#editProfilePic').change(function(event) {
    const [file] = event.target.files;
    if (file) {
        $('#txtImage').hide();
        $('#previewImage').attr('src', URL.createObjectURL(file));
        $("#previewImage").removeAttr("hidden");          
    }
});