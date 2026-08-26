import './bootstrap';

// 1. Importar jQuery y exponerlo globalmente de forma estricta
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// 1.1 Configurar AJAX con CSRF para todas las peticiones jQuery
window.$.ajaxSetup({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
});

// 2. Importar DataTables y sus estilos CSS
import DataTable from 'datatables.net-bs5';
DataTable.use(jQuery);
window.DataTable = DataTable;
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

// 3. Importar Extensiones de Botones para DataTables y sus estilos
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

// 4. Dependencias para exportación corregidas para Vite
import jszip from 'jszip';
import * as pdfMake from 'pdfmake/build/pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';

window.JSZip = jszip;

if (pdfFonts && pdfFonts.pdfMake && pdfFonts.pdfMake.vfs) {
    pdfMake.default.vfs = pdfFonts.pdfMake.vfs;
} else if (pdfFonts && pdfFonts.vfs) {
    pdfMake.default.vfs = pdfFonts.vfs;
}
window.pdfMake = pdfMake.default || pdfMake;

// 5. Importar Bootstrap y AdminLTE
import * as bootstrap from 'bootstrap';
import '@popperjs/core';
import 'admin-lte';
window.bootstrap = bootstrap;

// 6. Importar SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// 7. Importar Select2 y enlazarlo explícitamente a jQuery (PASO CRÍTICO DE LA AUDITORÍA)
import select2 from 'select2';
select2(); 

// 8. Importar OverlayScrollbars y sus estilos CSS
import { OverlayScrollbars } from 'overlayscrollbars';
import 'overlayscrollbars/styles/overlayscrollbars.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

// 9. Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// 10. Auto-inicialización global segura para Select2 (.select2-buscador)
document.addEventListener('DOMContentLoaded', () => {
    // Definir $ localmente para evitar ReferenceError en este módulo
    const $ = window.$;

    if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        
        // A. Inicializar Select2 fuera de los modales
        $('.select2-buscador:not(.modal .select2-buscador)').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Escriba para buscar...',
            language: {
                noResults: function() { 
                    return "No se encontraron resultados"; 
                }
            }
        });

        // B. Inicialización dinámica para Select2 dentro de Modales (Evita problemas de z-index y focus)
        $(document).on('shown.bs.modal', '.modal', function () {
            // El dropdownParent DEBE ser el modal contenedor para que Select2 se superponga bien
            let $modal = $(this);
            
            $modal.find('.select2-buscador').each(function() {
                // Validar que no se haya inicializado ya para evitar duplicados visuales
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Escriba para buscar...',
                        dropdownParent: $modal, // SOLUCIÓN RAÍZ: Atamos el menú flotante al modal
                        language: {
                            noResults: function() { 
                                return "No se encontraron resultados"; 
                            }
                        }
                    });
                }
            });
        });
        
    }
});