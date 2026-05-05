@extends('layouts.admin')
@section('content')

<div class="content" style="margin-left: 20px;">
    <h1>Cohorte 2025 - 2026</h1>

    @if($message = Session::get('mensaje'))
    <script>
        Swal.fire({
            title: "{{$message}}",
            icon: "success"
        });
    </script>

    @endif

    <div class="row">
        <div class="col-md-12">
            <div id='calendar'></div>
        </div>
    </div>
</div>
<script>
    $(function() {
        // Solo UNA inicialización para #example2
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Ministerios",
                "infoEmpty": "Mostrando 0 a 0 de 0 ministerios",
                "infoFiltered": "(Filtrado de _MAX_ ministerios totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ ministerios",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad",
                    "print": "Imprimir",
                    "copyTitle": "Copiado al portapapeles",
                    "copySuccess": {
                        "_": "%d filas copiadas",
                        "1": "1 fila copiada"
                    }
                }
            }
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    var calendarEl = document.getElementById('calendar');

    let inicioCohorte = '2025-10-15';
    let finCohorte = '2026-06-09';

    // 🔹 Generar miércoles SIN Date raros
    function generarMiercoles(inicio, fin) {
        let eventos = [];

        let fecha = new Date(inicio + 'T00:00:00');
        let fechaFin = new Date(fin + 'T00:00:00');

        // buscar primer miércoles
        while (fecha.getDay() !== 3) {
            fecha.setDate(fecha.getDate() + 1);
        }

        while (fecha <= fechaFin) {

            let year = fecha.getFullYear();
            let month = String(fecha.getMonth() + 1).padStart(2, '0');
            let day = String(fecha.getDate()).padStart(2, '0');

            eventos.push({
                title: 'Sesión',
                start: `${year}-${month}-${day}`,
                color: '#198754' // verde bootstrap (mejor que oscuro puro)
            });

            fecha.setDate(fecha.getDate() + 7);
        }

        return eventos;
    }

    // 🔵 Cronograma
    let cronograma = [
        { title: 'Reintegro', start: '2026-01-15', color: '#0d6efd' },
        { title: 'Inicio Trayecto', start: '2026-01-29', color: '#0d6efd' },
        { title: 'Formación Docente', start: '2026-02-05', color: '#0d6efd' },
        { title: 'Culminación', start: '2026-05-29', color: '#0d6efd' },
        { title: 'Inicio PER', start: '2026-06-08', color: '#0d6efd' }
    ];

    // 🟣 Festivos
    let festivos = [
        { title: 'Año Nuevo', start: '2026-01-01', color: '#d63384' },
        { title: 'Juventud', start: '2026-02-12', color: '#d63384' },
        { title: 'Carnaval', start: '2026-02-16', end: '2026-02-18', color: '#d63384' },
        { title: 'Día del Trabajador', start: '2026-05-01', color: '#d63384' }
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'multiMonthYear,dayGridMonth'
        },

        initialView: 'multiMonthYear',
        initialDate: inicioCohorte,

        multiMonthMaxColumns: 3,

        editable: false,
        selectable: false,

        // 🔥 SOLO EVENTOS (sin lógica rara)
        events: [
            ...generarMiercoles(inicioCohorte, finCohorte),
            ...cronograma,
            ...festivos
        ]

    });

    calendar.render();
});
</script>
@endsection