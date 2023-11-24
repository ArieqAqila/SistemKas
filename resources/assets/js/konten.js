ClassicEditor
    .create( document.querySelector( '#editor' ), {
        toolbar: [ 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],
    } )
    .catch( error => {
        console.log( error );
    } );

//$('#form-tambah-konten').modal('show');
