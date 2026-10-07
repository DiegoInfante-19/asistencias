@extends('layouts.admin')

@section('styles')
<style>
    /* =========================================
       DISEÑO BASE (COMPUTADORAS / ESCRITORIO)
       ========================================= */
    .btn-group-asistencia .btn {
        font-size: 0.9rem !important;
        padding: 0.4rem 0.75rem !important;
        line-height: 1.5;
        border-color: #ced4da !important;
        transition: all 0.2s ease-in-out;
        color: #6c757d !important;
        background-color: #f8f9fa !important;
    }

    /* Atenuar un poco los botones que NO están marcados */
    .btn-group-asistencia:hover .btn:not(:hover) {
        opacity: 0.7;
    }

    /* =========================================
       EFECTOS HOVER (COLOR ANTES DE HACER CLIC)
       ========================================= */
    .btn-group-asistencia .btn-asistencia-presente:hover:not(:disabled) {
        background-color: #d1e7dd !important;
        border-color: #198754 !important;
        color: #146c43 !important;
    }

    .btn-group-asistencia .btn-asistencia-ausente:hover:not(:disabled) {
        background-color: #f8d7da !important;
        border-color: #dc3545 !important;
        color: #b02a37 !important;
    }

    .btn-group-asistencia .btn-asistencia-justificado:hover:not(:disabled) {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
        color: #997404 !important;
    }

    /* =========================================
       ESTILOS DE ESTADOS ACTIVOS (¡MUY RESALTADOS!)
       ========================================= */

    .btn-check:checked+.btn-asistencia-presente {
        background-color: #198754 !important;
        border-color: #146c43 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        transform: scale(1.02);
        z-index: 2;
    }

    .btn-check:checked+.btn-asistencia-ausente {
        background-color: #dc3545 !important;
        border-color: #b02a37 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        transform: scale(1.02);
        z-index: 2;
    }

    .btn-check:checked+.btn-asistencia-justificado {
        background-color: #ffc107 !important;
        border-color: #cc9a06 !important;
        color: #000000 !important;
        font-weight: 700 !important;
        transform: scale(1.02);
        z-index: 2;
    }

    /* =========================================
       DISEÑO MÓVIL (TELÉFONOS)
       ========================================= */
    @media (max-width: 767.98px) {
        .btn-group-asistencia {
            display: flex !important;
            width: 100% !important;
        }

        .btn-group-asistencia .btn {
            flex: 1 !important;
            font-size: 0.9rem !important;
            padding: 0.5rem 0.1rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
    }

    /* ===================================================
       ESTÉTICA UNIFICADA PARA INPUTS Y SELECT2 "SOFT"
       =================================================== */
    
    /* 1. Entradas de texto y selects normales */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    /* 2. Forzar a Select2 para que adopte el fondo gris, borde y sombra */
    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
    }

    /* 3. Efecto Focus */
    .card-body.bg-white .form-control:focus,
    .card-body.bg-white .form-select:focus,
    .card-body.bg-white .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
    }

    /* 4. Estilos para el botón ColVis de DataTables */
    .dt-buttons .btn.buttons-colvis {
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        font-weight: 500;
    }
    .dt-buttons .btn.buttons-colvis:hover {
        background-color: #f8f9fa !important;
    }
    .dt-button-collection {
        padding: 0.5rem !important;
        border-radius: 0.5rem !important;
    }
    .dt-button-collection .dt-button {
        display: block;
        width: 100%;
        text-align: left;
        border: none !important;
        background: transparent !important;
        padding: 0.375rem 1rem;
        margin-bottom: 2px;
        border-radius: 0.25rem;
    }
    .dt-button-collection .dt-button:hover {
        background-color: #e9ecef !important;
    }
    .dt-button-collection .dt-button.active {
        background-color: #e0f0ff !important;
        color: #0d6efd !important;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- PANEL DE FILTROS AVANZADOS -->
    <div class="card shadow-sm mb-4">
        <!-- Header estático y limpio -->
        <div class="card-header bg-white py-3 border-bottom">
            <h4 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Filtros de Búsqueda
            </h4>
        </div>
        
        <!-- Body blanco -->
        <div class="card-body bg-white py-4">
            <div class="row g-3">

                <!-- Filtro por PNF -->
                <div class="col-md-6">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted text-uppercase">Programa (PNF)</label>
                    <select id="filtro_pnf" class="form-select select2-buscador bg-light border-secondary-subtle">
                        <option value="">Todos...</option>
                        @foreach($pnfs as $pnf)
                        <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Profesor (Solo si es Administrador o Coordinador) -->
                @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
                <div class="col-md-6">
                    <label for="filtro_profesor" class="form-label fw-bold small text-muted text-uppercase">Docente Asignado</label>
                    <select id="filtro_profesor" class="form-select select2-buscador bg-light border-secondary-subtle">
                        <option value="">Cualquiera...</option>
                        @foreach($profesores as $profesor)
                        @php
                        $nombreProf = trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? ''));
                        @endphp
                        <option value="{{ $profesor->id_profesor }}">{{ $nombreProf }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

            </div>
        </div>

        <!-- Footer BLANCO -->
        <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON LA TABLA -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">Directorio de Secciones</h5>
        </div>
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        // 0. Inicializar Select2 local con tema Bootstrap 5
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // 1. Adjuntar los filtros a la petición AJAX del DataTable mediante preXhr
        $('#secciones-clases-table').on('preXhr.dt', function(e, settings, data) {
            data.id_pnf = $('#filtro_pnf').val();
            data.id_profesor = $('#filtro_profesor').length ? $('#filtro_profesor').val() : null;
        });

        function triggerDatatableDraw() {
            if (window.LaravelDataTables && window.LaravelDataTables['secciones-clases-table']) {
                window.LaravelDataTables['secciones-clases-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#secciones-clases-table')) {
                $('#secciones-clases-table').DataTable().draw();
            }
        }

        // 2. Disparar redibujado automático al cambiar cualquier select de filtro
        const selectsFiltros = '#filtro_pnf, #filtro_profesor';
        $(selectsFiltros).on('change', function() {
            triggerDatatableDraw();
        });

        // 3. Botón para limpiar filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $(selectsFiltros).val(null).trigger('change.select2');
            triggerDatatableDraw();
        });

        // 4. Reajuste Responsive de la tabla
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#secciones-clases-table')) {
                let dt = $('#secciones-clases-table').DataTable();
                dt.columns.adjust();
                if (typeof dt.responsive !== 'undefined' && dt.responsive !== null) {
                    dt.responsive.recalc();
                }
            }
        });
    });
</script>
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush