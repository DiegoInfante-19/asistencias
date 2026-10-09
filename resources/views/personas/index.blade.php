@extends('layouts.admin')

@section('styles')
<style>
    /* 1. Azul Claro (#ADD8E6) para Masculino */
    table.dataTable tbody tr.bg-masculino>td {
        background-color: #ADD8E6 !important;
    }

    /* 2. Rosa Claro (#FFB6C1) para Femenino */
    table.dataTable tbody tr.bg-femenino>td {
        background-color: #FFB6C1 !important;
    }

    /* 3. Tonos ligeramente más profundos y cómodos para el efecto Hover */
    table.dataTable tbody tr.bg-masculino:hover>td {
        background-color: #9ac2d9 !important;
    }

    table.dataTable tbody tr.bg-femenino:hover>td {
        background-color: #f29da9 !important;
    }

    /* 4. ESTÉTICA UNIFICADA PARA INPUTS Y SELECTS EN EL PANEL DE FILTROS */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    /* 5. Integración específica para el buscador de Select2 para que no sea blanco puro */
    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    /* 6. Efecto al hacer Focus */
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

    <!-- PANEL DE FILTROS AVANZADOS (COLAPSIBLE) -->
    <div class="card shadow-sm mb-4" id="cardFiltros">
        <!-- Cabecera limpia con flexbox y botón perfectamente anclado a la derecha -->
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h4 class="card-title text-dark mb-0 fs-6" style="font-weight: 500;">
                <i class="bi bi-funnel-fill text-primary me-2"></i> Filtros de Búsqueda
            </h4>
            <button class="btn btn-secondary btn-sm shadow-sm ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="true" aria-controls="collapseFiltros" id="btnToggleFiltros">
                <i class="bi bi-sliders me-1"></i> Ocultar / Mostrar Filtros
            </button>
        </div>

        <!-- Contenedor colapsable -->
        <div class="collapse show" id="collapseFiltros">
            <div class="card-body bg-white py-4 border-top">
                <!-- Fila 1: Filtros Académicos -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="filtro_cohorte" class="form-label fw-bold small text-muted">Cohorte de Ingreso</label>
                        <select id="filtro_cohorte" class="form-select select2-buscador">
                            <option value="">Todas...</option>
                            @foreach(\App\Models\Cohorte::orderBy('numero_cohorte')->get() as $cohorte)
                            <option value="{{ $cohorte->id_cohortes }}">{{ $cohorte->numero_cohorte }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filtro_pnf" class="form-label fw-bold small text-muted">PNF</label>
                        <select id="filtro_pnf" class="form-select select2-buscador">
                            <option value="">Todos...</option>
                            @foreach(\App\Models\Pnf::orderBy('nombre_pnf')->get() as $pnf)
                            <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SELECT DINÁMICO DE TÍTULOS -->
                    <div class="col-md-4">
                        <label for="filtro_titulo" class="form-label fw-bold small text-muted">Título a Optar</label>
                        <select id="filtro_titulo" class="form-select select2-buscador" disabled>
                            <option value="">Seleccione un PNF primero...</option>
                        </select>
                    </div>
                </div>

                <!-- Fila 2: Filtros Laborales y Demográficos -->
                <div class="row g-3 mb-3">
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

                <!-- Fila 3: Nuevos Filtros Excluyentes (Profesor y Sección) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="filtro_profesor" class="form-label fw-bold small text-primary">
                            <i class="bi bi-person-badge me-1"></i> Filtrar por Profesor (Excluyente con Sección)
                        </label>
                        <select id="filtro_profesor" class="form-select select2-buscador">
                            <option value="">Cualquier profesor...</option>
                            @foreach(\App\Models\Profesor::with('user')->get() as $profesor)
                            @if($profesor->user)
                            <option value="{{ $profesor->id_profesor }}">
                                {{ $profesor->user->name_users }} {{ $profesor->user->last_name_users }} (Cédula: {{ $profesor->user->cedula_users }})
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filtro_seccion" class="form-label fw-bold small text-success">
                            <i class="bi bi-grid-3x3 me-1"></i> Filtrar por Sección (Excluyente con Profesor)
                        </label>
                        <select id="filtro_seccion" class="form-select select2-buscador">
                            <option value="">Cualquier sección...</option>
                            <!-- CORRECCIÓN: Agregamos ->activasParaAsignacion() antes del orderBy -->
                            @foreach(\App\Models\Seccion::with('pnf')->activasParaAsignacion()->orderBy('nombre_seccion')->get() as $seccion)
                            <option value="{{ $seccion->id_seccion }}">
                                Sección: {{ $seccion->nombre_seccion }} ({{$seccion->pnf->nombre_pnf ?? 'Sin PNF' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filtro_estatus" class="form-label fw-bold small text-muted">Estatus del Expediente</label>
                        <select id="filtro_estatus" class="form-select select2-buscador">
                            <option value="">Todos...</option>
                            @foreach(\App\Models\EstatusExpediente::orderBy('nombre_estatus_expediente')->get() as $estatus)
                            <option value="{{ $estatus->id_estatus_expediente }}">{{ $estatus->nombre_estatus_expediente }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- FOOTER DE LA TARJETA DE FILTROS -->
            <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
                <button type="button" id="btn-limpiar-filtros" class="btn btn-success shadow-sm">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tarjeta Principal con la Tabla -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0 me-3" style="font-weight: 500;">Registro de Estudiantes</h4>

            @php
            $totalFemenino = \App\Models\Persona::where('sexo_personas', 'F')->count();$totalMasculino = \App\Models\Persona::where('sexo_personas', 'M')->count();
            @endphp

            <!-- Badges con diseño Soft mejorado y tamaño visible (fs-6, px-3 py-2) -->
            <div class="d-flex align-items-center gap-2">
                <span class="badge px-3 py-2 fs-6 shadow-sm" style="background-color: #fce8e6; color: #d93829; border: 1px solid #d93829;" title="Total Femenino">
                    <i class="bi bi-gender-female me-1"></i> F: {{ $totalFemenino }}
                </span>
                <span class="badge px-3 py-2 fs-6 shadow-sm" style="background-color: #e3f2fd; color: #0d6efd; border: 1px solid #0d6efd;" title="Total Masculino">
                    <i class="bi bi-gender-male me-1"></i> M: {{ $totalMasculino }}
                </span>
            </div>

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
        // 0. INICIALIZAR SELECT2 EXPLÍCITAMENTE CON TEMA BOOTSTRAP 5
        $('#cardFiltros .select2-buscador').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: $(this).find('option:first').text() || 'Seleccione una opción...'
                });
            }
        });

        // 0.1 CORRECCIÓN PARA EL PANEL COLLAPSE (Reajusta Select2 al desplegarse)
        $('#collapseFiltros').on('shown.bs.collapse', function() {
            $('#cardFiltros .select2-buscador').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: true,
                        placeholder: $(this).find('option:first').text() || 'Seleccione una opción...'
                    });
                }
            });
        });

        // 1. PATRÓN DEBOUNCE: Redibujado controlado de la tabla (Evita tormentas de peticiones AJAX)
        let drawTimeout;

        function triggerDatatableDraw() {
            clearTimeout(drawTimeout);
            drawTimeout = setTimeout(function() {
                if (window.LaravelDataTables && window.LaravelDataTables['personas-table']) {
                    window.LaravelDataTables['personas-table'].draw();
                } else if ($.fn.DataTable.isDataTable('#personas-table')) {
                    $('#personas-table').DataTable().draw();
                }
            }, 200); // Espera 200ms de respiro antes de enviar la petición al servidor
        }

        // 2. Adjuntar los filtros a la petición AJAX
        $('#personas-table').on('preXhr.dt', function(e, settings, data) {
            data.filtro_cohorte = $('#filtro_cohorte').val();
            data.filtro_pnf = $('#filtro_pnf').val();
            data.filtro_titulo = $('#filtro_titulo').val();
            data.filtro_empresa = $('#filtro_empresa').val();
            data.filtro_cargo = $('#filtro_cargo').val();
            data.filtro_estado = $('#filtro_estado').val();
            data.filtro_profesor = $('#filtro_profesor').val();
            data.filtro_seccion = $('#filtro_seccion').val();
            data.filtro_estatus = $('#filtro_estatus').val();
        });

        // 3. LÓGICA DE EXCLUSIÓN MUTUA (Profesor vs Sección)
        $('#filtro_profesor').on('change', function() {
            if ($(this).val()) {
                $('#filtro_seccion').val(null).trigger('change.select2').prop('disabled', true);
            } else {
                $('#filtro_seccion').prop('disabled', false);
            }
            triggerDatatableDraw();
        });

        $('#filtro_seccion').on('change', function() {
            if ($(this).val()) {
                $('#filtro_profesor').val(null).trigger('change.select2').prop('disabled', true);
            } else {
                $('#filtro_profesor').prop('disabled', false);
            }
            triggerDatatableDraw();
        });

        // 4. LÓGICA EN CASCADA AJAX (PNF -> TÍTULO)
        let $pnfSelect = $('#filtro_pnf');
        let $tituloSelect = $('#filtro_titulo');

        $pnfSelect.on('change', function() {
            let pnfId = $(this).val();

            if (!pnfId) {
                $tituloSelect.empty()
                    .append('<option value="">Seleccione un PNF primero...</option>')
                    .prop('disabled', true);
                $tituloSelect.trigger('change.select2');
                triggerDatatableDraw();
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
                        // Se usa item.id_titulo para que coincida exactamente con el id_titulacion del backend
                        $tituloSelect.append(`<option value="${item.id_titulo}">${item.nombre_titulo_pnf}</option>`);
                    });

                    $tituloSelect.prop('disabled', false);
                    $tituloSelect.trigger('change.select2');
                    triggerDatatableDraw();
                },
                error: function() {
                    $tituloSelect.empty().append('<option value="">Error al cargar</option>').prop('disabled', true);
                    $tituloSelect.trigger('change.select2');
                }
            });
        });

        // 5. Redibujado automático para los demás selects comunes
        const selectsComunes = '#filtro_cohorte, #filtro_pnf, #filtro_titulo, #filtro_estatus, #filtro_empresa, #filtro_cargo, #filtro_estado';
        $(selectsComunes).on('change', function(e) {
            // ELIMINADO: if (e.originalEvent) { ... }
            // Ahora cualquier cambio de Select2 disparará el redibujado de la tabla sin ser bloqueado.
            triggerDatatableDraw();
        });

        // 6. LIMPIAR FILTROS OPTIMIZADO
        $('#btn-limpiar-filtros').on('click', function() {
            $('#filtro_cohorte, #filtro_pnf, #filtro_titulo, #filtro_estatus, #filtro_empresa, #filtro_cargo, #filtro_estado, #filtro_profesor, #filtro_seccion')
                .val(null)
                .trigger('change.select2');

            $('#filtro_profesor, #filtro_seccion').prop('disabled', false);
            triggerDatatableDraw();
        });

        // 7. Reajuste Responsive
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#personas-table')) {
                let dt = $('#personas-table').DataTable();
                dt.columns.adjust();
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