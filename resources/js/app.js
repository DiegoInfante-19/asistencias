import './bootstrap';

// 1. Importar jQuery y exponerlo globalmente
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// 2. Importar DataTables v3 y su integración con Bootstrap 5
import DataTable from 'datatables.net-bs5';

// 3. VINCULACIÓN OBLIGATORIA ESM
DataTable.use(jQuery);

// 4. IMPORTAR EXTENSIONES DE BOTONES PARA DATA TABLES V3
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

// Dependencias para la exportación de archivos (Excel / PDF)
import jszip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// Configuración global segura compatible con Vite
window.JSZip = jszip;
window.pdfMake = pdfMake;
// Asignación condicional: Si Vite anida el objeto vfs, lo busca; si no, lo asigna directo.
window.pdfMake.vfs = pdfFonts.pdfMake ? pdfFonts.pdfMake.vfs : pdfFonts.vfs;

// 5. Importar Bootstrap y AdminLTE
import * as bootstrap from 'bootstrap';
import 'admin-lte';
window.bootstrap = bootstrap;

// 6. Importar SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// 7. Importar Select2
import 'select2';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';

// 8. Importar OverlayScrollbars
import { OverlayScrollbars } from 'overlayscrollbars';
window.OverlayScrollbars = OverlayScrollbars;

// 9. Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();