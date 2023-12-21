/* Necessary Module */
import './bootstrap';
import '../scss/app.scss';

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