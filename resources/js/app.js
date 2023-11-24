/* Necessary Module */
import './bootstrap';
import '../scss/app.scss';

/* Jquery Module */
import '../../node_modules/jquery/dist/jquery';

/* Bootstrap Module */
import '../../node_modules/bootstrap/dist/js/bootstrap';

/* dataTable Module */
import DataTable from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
window.DataTable = DataTable;

/* Sweetalert2 Module */
import Swal from 'sweetalert2';
import 'sweetalert2/src/sweetalert2.scss';
window.Swal = Swal;

/* FontAwesome Module */
import '@fortawesome/fontawesome-free/css/all.css';

/* CKEditor 5 Module */
//import '@ckeditor/ckeditor5-build-classic/build/ckeditor.js';
