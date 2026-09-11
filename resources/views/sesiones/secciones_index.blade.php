@extends('layouts.admin')

@section('styles')
<style>
    /* Estética unificada para inputs y selects en el panel de filtros */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .card-body.bg-white .form-control:focus,
    .card-body.bg-white .form-select:focus,
    .card-body.bg-white .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- PANEL DE FILTROS AVANZADOS -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body bg-white py-4">
            <div class="row g-3">
                
                <!-- Filtro por PNF -->
                <div class="col-md-3">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted">Programa (PNF)</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach($pnfs as $pnf)
                            <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Profesor (Solo si es Administrador o Coordinador) -->
                @if(auth()->user()->isAdmin() || auth()->user()->isCoordinador())
                <div class="col-md-3">
                    <label for="filtro_profesor" class="form-label fw-bold small text-muted">Docente Asignado</label>
                    <select id="filtro_profesor" class="form-select select2-buscador">
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

                <!-- Filtro por Empresa de los Alumnos -->
                <div class="col-md-3">
                    <label for="filtro_empresa" class="form-label fw-bold small text-muted">Empresa de Alumnos</label>
                    <select id="filtro_empresa" class="form-select select2-buscador">
                        <option value="">Todas...</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id_empresa }}">{{ $empresa->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Título al que Opta -->
                <div class="col-md-3">
                    <label for="filtro_titulo" class="form-label fw-bold small text-muted">Título al que Opta</label>
                    <select id="filtro_titulo" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach($titulos as $titulo)
                            <option value="{{ $titulo->id_titulo }}">{{ $titulo->nombre_titulo }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="card-footer bg-white py-3 d-flex justify-content-end">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-success fw-bold">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON LA TABLA -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">Directorio Maestro de Secciones</h5>
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
            data.id_pnf       = $('#filtro_pnf').val();
            data.id_profesor  = $('#filtro_profesor').length ? $('#filtro_profesor').val() : null;
            data.id_empresa   = $('#filtro_empresa').val();
            data.id_titulo    = $('#filtro_titulo').val();
        });

        function triggerDatatableDraw() {
            if (window.LaravelDataTables && window.LaravelDataTables['secciones-clases-table']) {
                window.LaravelDataTables['secciones-clases-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#secciones-clases-table')) {
                $('#secciones-clases-table').DataTable().draw();
            }
        }

        // 2. Disparar redibujado automático al cambiar cualquier select de filtro
        const selectsFiltros = '#filtro_pnf, #filtro_profesor, #filtro_empresa, #filtro_titulo';
        $(selectsFiltros).on('change', function() {
            triggerDatatableDraw();
        });

        // 3. Botón para limpiar filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $(selectsFiltros).val(null).trigger('change');
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