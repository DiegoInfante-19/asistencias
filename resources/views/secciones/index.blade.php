@extends('layouts.admin')

@section('styles')
<style>
    /* ESTÉTICA UNIFICADA PARA INPUTS Y SELECTS EN EL PANEL DE FILTROS */
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
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h4 class="card-title text-dark mb-0fs-6" style="font-weight: 500;">
                Filtros de Búsqueda
            </h4>
        </div>
        <div class="card-body bg-white py-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted">PNF</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach($pnfs as $pnf)
                        <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_profesor" class="form-label fw-bold small text-muted">Docente</label>
                    <select id="filtro_profesor" class="form-select select2-buscador">
                        <option value="">Cualquiera...</option>
                        @foreach($profesores as $profesor)
                        <option value="{{ $profesor->id_profesor }}">
                            {{ $profesor->user->name_users ?? '' }} {{ $profesor->user->last_name_users ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_empresa" class="form-label fw-bold small text-muted">Empresa (Alumnos)</label>
                    <select id="filtro_empresa" class="form-select select2-buscador">
                        <option value="">Cualquiera...</option>
                        @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id_empresa }}">{{ $empresa->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_cohorte" class="form-label fw-bold small text-muted">Cohorte de Origen</label>
                    <select id="filtro_cohorte" class="form-select select2-buscador">
                        <option value="">Todas...</option>
                        @foreach(\App\Models\Cohorte::orderBy('numero_cohorte')->get() as $cohorte)
                        <option value="{{ $cohorte->id_cohortes }}">Cohorte {{ $cohorte->numero_cohorte }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-end">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- TABLA PRINCIPAL -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Listado de Secciones Académicas</h4>
            <button type="button" class="btn btn-primary ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createSeccionModal">
                <i class="bi bi-plus-circle me-1"></i> Nueva Sección
            </button>
        </div>
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>
    </div>
</div>

<!-- Modal para Crear Sección -->
@include('secciones.partials.create-modal', ['periodos' => $periodos, 'pnfs' => $pnfs])
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        // 1. Inicializar Select2 con tema Bootstrap 5
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // 2. Adjuntar los filtros a la petición AJAX del DataTable
        $('#secciones-table').on('preXhr.dt', function(e, settings, data) {
            data.filtro_pnf = $('#filtro_pnf').val();
            data.filtro_profesor = $('#filtro_profesor').val();
            data.filtro_empresa = $('#filtro_empresa').val();
            data.filtro_cohorte = $('#filtro_cohorte').val();
        });

        // 3. Filtrado automático al cambiar cualquier select
        const selects = '#filtro_pnf, #filtro_profesor, #filtro_empresa, #filtro_cohorte';
        $(selects).on('change', function() {
            if (window.LaravelDataTables && window.LaravelDataTables['secciones-table']) {
                window.LaravelDataTables['secciones-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#secciones-table')) {
                $('#secciones-table').DataTable().draw();
            }
        });

        // 4. Limpiar Filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $('#filtro_pnf, #filtro_profesor, #filtro_empresa, #filtro_cohorte').val(null).trigger('change');
        });
    });
</script>
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush