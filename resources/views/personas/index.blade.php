@extends('layouts.admin')

@section('styles')
<style>
    /* 1. Azul pastel puro (sólido) para Masculino */
    table.dataTable tbody tr.bg-masculino > td {
        background-color: #e6f0ff !important;
    }

    /* 2. Rosa pastel suave (sólido) para Femenino */
    table.dataTable tbody tr.bg-femenino > td {
        background-color: #fce8f3 !important;
    }
    
    table.dataTable tbody tr.bg-masculino:hover > td {
        background-color: #d6e6ff !important;
    }
    table.dataTable tbody tr.bg-femenino:hover > td {
        background-color: #fad1e6 !important;
    }

    /* 3. ESTÉTICA UNIFICADA PARA INPUTS Y SELECTS EN EL PANEL DE FILTROS */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important; /* Fondo gris muy suave y elegante */
        border-color: #dee2e6 !important;
    }

    /* 4. Integración específica para el buscador de Select2 para que no sea blanco puro */
    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    /* 5. Efecto al hacer Focus: Vuelven a blanco al interactuar con ellos */
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
            <h5 class="card-title text-dark mb-0fs-6" style="font-weight: 500;">
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body bg-white py-4">
            
            <!-- Fila 1: Filtros Académicos -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label for="filtro_cohorte" class="form-label fw-bold small text-muted">Cohorte de Ingreso</label>
                    <select id="filtro_cohorte" class="form-select select2-buscador">
                        <option value="">Todas...</option>
                        @foreach(\App\Models\Cohorte::orderBy('numero_cohorte')->get() as $cohorte)
                            <option value="{{ $cohorte->id_cohortes }}">{{ $cohorte->numero_cohorte }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted">PNF</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach(\App\Models\Pnf::orderBy('nombre_pnf')->get() as $pnf)
                            <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SELECT DINÁMICO DE TÍTULOS -->
                <div class="col-md-3">
                    <label for="filtro_titulo" class="form-label fw-bold small text-muted">Título a Optar</label>
                    <select id="filtro_titulo" class="form-select select2-buscador" disabled>
                        <option value="">Seleccione un PNF primero...</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filtro_estatus" class="form-label fw-bold small text-muted">Estatus del Expediente</label>
                    <select id="filtro_estatus" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach(\App\Models\EstatusExpediente::orderBy('nombre_estatus_expediente')->get() as $estatus)
                            <option value="{{ $estatus->id_estatus_expediente }}">{{ $estatus->nombre_estatus_expediente }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Fila 2: Filtros Laborales y Demográficos -->
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="filtro_empresa" class="form-label fw-bold small text-muted">Empresa Aliada</label>
                    <select id="filtro_empresa" class="form-select select2-buscador">
                        <option value="">Todas...</option>
                        @foreach(\App\Models\Empresa::orderBy('nombre_empresa')->get() as $empresa)
                            <option value="{{ $empresa->id_empresa }}">{{ $empresa->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filtro_cargo" class="form-label fw-bold small text-muted">Cargo Laboral</label>
                    <select id="filtro_cargo" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach(\App\Models\Cargo::orderBy('descripcion_cargo')->get() as $cargo)
                            <option value="{{ $cargo->id_cargo }}">{{ $cargo->descripcion_cargo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filtro_estado" class="form-label fw-bold small text-muted">Estado Origen (Nacimiento)</label>
                    <select id="filtro_estado" class="form-select select2-buscador">
                        <option value="">Todos...</option>
                        @foreach(\App\Models\Estado::orderBy('nombre_estado')->get() as $estado)
                            <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <!-- FOOTER DE LA TARJETA DE FILTROS -->
        <div class="card-footer bg-white py-3 d-flex justify-content-end">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tarjeta Principal con la Tabla -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Registro de Estudiantes</h4>
            <a href="{{ route('personas.create') }}" class="btn btn-primary ms-auto">
                <i class="bi bi-person-plus-fill me-1" style="font-weight: 500;"></i> Añadir Estudiante
            </a>
        </div>
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        // 0. INICIALIZAR SELECT2 EXPLÍCITAMENTE (Activa los buscadores)
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // 1. Adjuntar los 7 filtros a la petición AJAX usando el ID directo
        $('#personas-table').on('preXhr.dt', function(e, settings, data) {
            data.filtro_cohorte = $('#filtro_cohorte').val();
            data.filtro_pnf     = $('#filtro_pnf').val();
            data.filtro_titulo  = $('#filtro_titulo').val();
            data.filtro_estatus = $('#filtro_estatus').val();
            data.filtro_empresa = $('#filtro_empresa').val();
            data.filtro_cargo   = $('#filtro_cargo').val();
            data.filtro_estado  = $('#filtro_estado').val();
        });

        // 2. LÓGICA EN CASCADA AJAX (PNF -> TÍTULO)
        let $pnfSelect = $('#filtro_pnf');
        let $tituloSelect = $('#filtro_titulo');

        $pnfSelect.on('change', function() {
            let pnfId = $(this).val();

            if (!pnfId) {
                $tituloSelect.empty()
                             .append('<option value="">Seleccione un PNF primero...</option>')
                             .prop('disabled', true);
                $tituloSelect.trigger('change.select2');
                return;
            }

            $tituloSelect.prop('disabled', true).html('<option value="">Cargando títulos...</option>');
            $tituloSelect.trigger('change.select2');

            $.ajax({
                url: "{{ url('/titulos-por-pnf') }}/" + pnfId,
                type: 'GET',
                success: function(data) {
                    $tituloSelect.empty().append('<option value="">Todos los títulos del PNF...</option>');
                    $.each(data, function(key, item) {
                        $tituloSelect.append(`<option value="${item.id_titulo}">${item.nombre_titulo_pnf}</option>`);
                    });
                    
                    $tituloSelect.prop('disabled', false);
                    $tituloSelect.trigger('change.select2');
                },
                error: function() {
                    $tituloSelect.empty().append('<option value="">Error al cargar</option>').prop('disabled', true);
                    $tituloSelect.trigger('change.select2');
                }
            });
        });

        // 3. Disparar el redibujado automático al cambiar cualquier Select2
        const selects = '#filtro_cohorte, #filtro_pnf, #filtro_titulo, #filtro_estatus, #filtro_empresa, #filtro_cargo, #filtro_estado';
        $(selects).on('change', function() {
            // Buscamos la instancia oficial creada por Yajra DataTables
            if (window.LaravelDataTables && window.LaravelDataTables['personas-table']) {
                window.LaravelDataTables['personas-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#personas-table')) {
                $('#personas-table').DataTable().draw();
            }
        });

        // 4. Resetear todos los filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $('#filtro_cohorte, #filtro_pnf, #filtro_estatus, #filtro_empresa, #filtro_cargo, #filtro_estado').val(null).trigger('change');
        });

        // 5. Reajuste Responsive BLINDADO (Corrige el error en consola)
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#personas-table')) {
                let dt = $('#personas-table').DataTable();
                dt.columns.adjust();
                
                // Verificamos estrictamente que la extensión responsive exista antes de recalcular
                if (typeof dt.responsive !== 'undefined' && dt.responsive !== null) {
                    dt.responsive.recalc();
                }
            }
        });
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush