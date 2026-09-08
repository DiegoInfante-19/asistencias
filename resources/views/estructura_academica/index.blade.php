@extends('layouts.admin')

@section('styles')
<style>
    /* ESTÉTICA PARA SELECT2 CON FONDO BLANCO */
    .select2-container--bootstrap-5 .select2-selection {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
    }

    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* BOTÓN LIMPIAR CON FONDO BLANCO Y HOVER NORMAL */
    .btn-limpiar-filtros-dt {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #6c757d;
    }

    .btn-limpiar-filtros-dt:hover {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
    }

    /* ========================================================================== */
    /* BOTONES DE ACCIÓN: Fondo blanco en reposo, hover 'secondary' nativo        */
    /* ========================================================================== */
    .btn-group .btn-outline-secondary:not(:hover):not(:active):not(.active) {
        background-color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-dark fw-bold">
                <i class="bi bi-diagram-3-fill me-2 text-primary"></i> Estructura Académica General
            </h4>
            <p class="text-muted small mb-0">Administración jerárquica unificada: Cohortes, Períodos y Secciones</p>
        </div>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCohorte">
            <i class="bi bi-plus-lg me-1"></i> Nueva Cohorte y Período
        </button>
    </div>

    <!-- ACORDEÓN PRINCIPAL: COHORTES -->
    <div class="accordion shadow-sm" id="accordionCohortes">
        @forelse($cohortes as $cohorte)
        <div class="accordion-item mb-3 border-0 rounded bg-white shadow-sm">
            <h2 class="accordion-header d-flex align-items-center bg-white rounded border" id="headingCohorte{{ $cohorte->id_cohortes }}">
                <button class="accordion-button collapsed fw-bold fs-5 text-dark bg-transparent shadow-none flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCohorte{{ $cohorte->id_cohortes }}" aria-expanded="false" aria-controls="collapseCohorte{{ $cohorte->id_cohortes }}">
                    {{ $cohorte->numero_cohorte }}
                    @if($cohorte->periodosAcademicos->isNotEmpty())
                    &nbsp; <span class="fw-normal text-muted">Período {{ $cohorte->periodosAcademicos->first()->fecha_inicio?->format('Y') }}-{{ $cohorte->periodosAcademicos->first()->fecha_fin?->format('Y') }}</span>
                    @endif

                    <!-- BADGE ESTATUS -->
                    <span class="badge {{ $cohorte->estatus_cohorte == 'Activo' ? 'bg-success' : 'bg-secondary' }} ms-3 fs-6">
                        {{ $cohorte->estatus_cohorte }}
                    </span>

                    <!-- TOTAL DE SECCIONES -->
                    <span class="badge bg-light text-dark border ms-2 fw-normal small" title="Total de Secciones">
                        <i class="bi bi-layers-fill text-success me-1"></i>
                        {{ $cohorte->periodosAcademicos->first() ? $cohorte->periodosAcademicos->first()->secciones->count() : 0 }} Secciones
                    </span>

                    <!-- TOTAL DE ESTUDIANTES EN LA COHORTE -->
                    <span class="badge bg-light text-dark border ms-2 fw-normal small" title="Total de Estudiantes">
                        <i class="bi bi-people-fill text-info me-1"></i>
                        {{ $cohorte->personas->count() ?? 0 }} Estudiantes
                    </span>
                </button>

                <!-- ACCIONES DE COHORTE -->
                <div class="pe-3">
                    <div class="btn-group shadow-sm" role="group" aria-label="Acciones de cohorte">
                        <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditarCohorte"
                            data-id="{{ $cohorte->id_cohortes }}"
                            data-numero="{{ $cohorte->numero_cohorte }}"
                            data-descripcion="{{ $cohorte->descripcion_cohorte }}"
                            data-estatus="{{ $cohorte->estatus_cohorte }}"
                            data-fecha-inicio="{{ optional($cohorte->periodosAcademicos->first())->fecha_inicio?->format('Y-m-d') }}"
                            data-fecha-fin="{{ optional($cohorte->periodosAcademicos->first())->fecha_fin?->format('Y-m-d') }}"
                            title="Editar Cohorte">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button type="submit"
                            form="form-delete-cohorte-{{ $cohorte->id_cohortes }}"
                            class="btn btn-outline-secondary btn-delete-cohorte"
                            title="Eliminar Cohorte">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <form id="form-delete-cohorte-{{ $cohorte->id_cohortes }}" action="{{ route('cohortes.destroy', $cohorte->id_cohortes) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </h2>

            <div id="collapseCohorte{{ $cohorte->id_cohortes }}" class="accordion-collapse collapse" aria-labelledby="headingCohorte{{ $cohorte->id_cohortes }}" data-bs-parent="#accordionCohortes">
                <div class="accordion-body bg-light p-4 border border-top-0 rounded-bottom">

                    @if($cohorte->periodosAcademicos->isEmpty())
                    <div class="alert alert-warning text-center border-0 shadow-sm py-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> No hay un período académico vinculado a esta cohorte.
                    </div>
                    @else

                    <div class="accordion" id="accordionPeriodos{{ $cohorte->id_cohortes }}">
                        @foreach($cohorte->periodosAcademicos as $periodo)
                        <div class="accordion-item mb-2 border shadow-sm rounded bg-white">
                            <h2 class="accordion-header d-flex align-items-center bg-white" id="headingPeriodo{{ $periodo->id_periodo }}">
                                <button class="accordion-button bg-white text-dark py-3 shadow-none flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePeriodo{{ $periodo->id_periodo }}" aria-expanded="true" aria-controls="collapsePeriodo{{ $periodo->id_periodo }}">
                                    <i class="bi bi-calendar2-check text-info me-3 fs-4"></i>
                                    <div class="d-flex flex-column text-start">
                                        <!-- Fechas en tamaño normal -->
                                        <span class="text-dark">
                                            Comienza el <strong>{{ $periodo->fecha_inicio ? $periodo->fecha_inicio->format('d') : '00' }}</strong> de <strong>{{ $periodo->fecha_inicio ? \Carbon\Carbon::parse($periodo->fecha_inicio)->locale('es')->monthName : 'mes' }}</strong> de <strong>{{ $periodo->fecha_inicio ? $periodo->fecha_inicio->format('Y') : '0000' }}</strong>
                                        </span>
                                        <span class="text-muted mt-1">
                                            Finaliza el <strong>{{ $periodo->fecha_fin ? $periodo->fecha_fin->format('d') : '00' }}</strong> de <strong>{{ $periodo->fecha_fin ? \Carbon\Carbon::parse($periodo->fecha_fin)->locale('es')->monthName : 'mes' }}</strong> del año <strong>{{ $periodo->fecha_fin ? $periodo->fecha_fin->format('Y') : '0000' }}</strong>
                                        </span>
                                        <!-- Texto descriptivo del estatus -->
                                        <span class="mt-2 text-dark">
                                            El período está <strong class="{{ $periodo->estatus_periodo == 'Activo' ? 'text-success' : 'text-secondary' }}">{{ $periodo->estatus_periodo }}</strong>
                                        </span>
                                    </div>
                                </button>
                            </h2>

                            <div id="collapsePeriodo{{ $periodo->id_periodo }}" class="accordion-collapse collapse show" aria-labelledby="headingPeriodo{{ $periodo->id_periodo }}" data-bs-parent="#accordionPeriodos{{ $cohorte->id_cohortes }}">
                                <div class="accordion-body bg-white p-3">

                                    <!-- CONTROLES DEL PERÍODO Y MINI FILTROS -->
                                    <div class="d-flex justify-content-between align-items-end mb-3 bg-light p-3 rounded border">
                                        <div class="row g-2 flex-grow-1 me-3">
                                            
                                            <!-- Filtro Docente (col-md-5) -->
                                            <div class="col-md-5">
                                                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-person-badge"></i> Docente</label>
                                                <select id="filtro_profesor_{{ $periodo->id_periodo }}" class="form-select form-select-sm select2-filtro">
                                                    <option value="">Todos los docentes...</option>
                                                    @foreach($profesores as $profesor)
                                                    <option value="{{ $profesor->user->name_users ?? '' }} {{ $profesor->user->last_name_users ?? '' }}">
                                                        {{ $profesor->user->name_users ?? '' }} {{ $profesor->user->last_name_users ?? '' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Filtro PNF (col-md-5) -->
                                            <div class="col-md-5">
                                                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-journal-bookmark"></i> PNF</label>
                                                <select id="filtro_pnf_{{ $periodo->id_periodo }}" class="form-select form-select-sm select2-filtro">
                                                    <option value="">Todos los PNF...</option>
                                                    @php
                                                        // Extraemos dinámicamente los PNFs de las secciones existentes en este período
                                                        $pnfsPeriodo = $periodo->secciones->map(fn($s) => $s->pnf)->filter()->unique('id_pnf');
                                                    @endphp
                                                    @foreach($pnfsPeriodo as $pnfItem)
                                                    <option value="{{ $pnfItem->nombre_pnf }}">{{ $pnfItem->nombre_pnf }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm w-100 btn-limpiar-filtros-dt shadow-sm" data-periodo-id="{{ $periodo->id_periodo }}">
                                                    <i class="bi bi-x-circle me-1"></i> Limpiar
                                                </button>
                                            </div>
                                        </div>
                                        <button class="btn btn-success fw-bold btn-add-seccion shadow-sm" data-periodo-id="{{ $periodo->id_periodo }}" data-bs-toggle="modal" data-bs-target="#modalSeccion">
                                            <i class="bi bi-plus-circle me-1"></i> Añadir Sección
                                        </button>
                                    </div>

                                    @if($periodo->secciones->isEmpty())
                                    <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Este período no tiene secciones asignadas.</p>
                                    @else
                                    <div class="table-responsive">
                                        <table id="tabla-secciones-{{ $periodo->id_periodo }}" class="table table-hover table-striped align-middle border mb-0 tabla-secciones-dt w-100" data-periodo-id="{{ $periodo->id_periodo }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40%;">Sección</th>
                                                    <th class="text-center" style="width: 35%;">Docentes Asignados</th>
                                                    <th class="text-center" style="width: 25%;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($periodo->secciones as $seccion)
                                                <tr>
                                                    <td class="fw-bold text-dark text-primary">
                                                        {{ $seccion->nombre_seccion }}
                                                        <!-- SPAN OCULTO: Contiene el PNF para que DataTables lo encuentre al buscar -->
                                                        <span class="d-none pnf-search-data">{{ $seccion->pnf->nombre_pnf ?? '' }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- CONTADOR DE DOCENTES ESTÁTICO -->
                                                        @if($seccion->profesores->isEmpty())
                                                        <span class="text-muted small">Sin asignar</span>
                                                        @else
                                                        <span class="badge bg-info text-dark">{{ $seccion->profesores->count() }} Asignado(s)</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- ACCIONES DE SECCIÓN -->
                                                        <div class="btn-group shadow-sm" role="group">
                                                            <a href="{{ route('secciones.show', $seccion->id_seccion) }}" class="btn btn-outline-secondary" title="Gestionar Estudiantes">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <!-- BOTON DE EDITAR SECCION -->
                                                            <button type="button" 
                                                                class="btn btn-outline-secondary"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEditarSeccion"
                                                                data-id="{{ $seccion->id_seccion }}"
                                                                data-nombre="{{ $seccion->nombre_seccion }}"
                                                                data-pnf="{{ $seccion->id_pnf }}"
                                                                data-pnf-nombre="{{ $seccion->pnf->nombre_pnf ?? 'N/A' }}"
                                                                data-estatus="{{ $seccion->estatus_seccion }}"
                                                                title="Editar Sección">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button type="submit" form="form-delete-seccion-{{ $seccion->id_seccion }}" class="btn btn-outline-secondary btn-delete-seccion" title="Eliminar Sección">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                        <form id="form-delete-seccion-{{ $seccion->id_seccion }}" action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 bg-white rounded shadow-sm border">
            <i class="bi bi-diagram-3 text-muted opacity-50 d-block mb-3" style="font-size: 4rem;"></i>
            <h5 class="fw-bold text-dark">No hay Estructura Académica</h5>
            <p class="text-muted">Comienza creando la primera cohorte con su período asociado.</p>
            <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalCohorte">
                <i class="bi bi-plus-lg me-1"></i> Crear Primera Cohorte
            </button>
        </div>
        @endforelse
    </div>

    <!-- INCLUIMOS LOS MODALES DESDE PARTIALS -->
    @include('estructura_academica.partials.modals')

</div>
@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Inicialización de Select2 para los filtros
        if ($.fn.select2) {
            $('.select2-filtro').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        // 2. MAGIA DE DATATABLES: Inicializamos cada tabla que exista dentro de un período
        if ($.fn.DataTable) {
            $('.tabla-secciones-dt').each(function() {
                let $table = $(this);
                let periodoId = $table.data('periodo-id');

                let dt = $table.DataTable({
                    responsive: true,
                    autoWidth: false,
                    dom: '<"row mb-3 align-items-center"<"col-md-6"l><"col-md-6 text-md-end text-start mt-2 mt-md-0"f>>' +
                        '<"row mb-3"<"col-12"B>>' +
                        'rt' +
                        '<"row mt-3 align-items-center"<"col-md-5 text-muted small"i><"col-md-7 d-flex justify-content-md-end justify-content-center"p>>',
                    buttons: [{
                            extend: 'pdf',
                            text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                            className: 'btn btn-danger shadow-sm btn-sm'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                            className: 'btn btn-success shadow-sm btn-sm'
                        },
                        {
                            extend: 'print',
                            text: '<i class="bi bi-printer me-1"></i> Imprimir',
                            className: 'btn btn-secondary shadow-sm btn-sm'
                        }
                    ],
                    language: {
                        processing: 'Procesando...',
                        search: 'Mini Buscador:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando del _START_ al _END_ de _TOTAL_ registros',
                        infoEmpty: '0 registros',
                        infoFiltered: '(filtrado de _MAX_ registros)',
                        zeroRecords: 'No se encontraron resultados',
                        emptyTable: 'Ningún dato disponible',
                        paginate: {
                            first: '<i class="bi bi-chevron-double-left"></i>',
                            previous: '<i class="bi bi-chevron-left"></i>',
                            next: '<i class="bi bi-chevron-right"></i>',
                            last: '<i class="bi bi-chevron-double-right"></i>'
                        }
                    }
                });

                // VINCULACIÓN DE FILTROS A LA TABLA
                // Filtrar por Docente en la Columna 1
                $(`#filtro_profesor_${periodoId}`).on('change', function() {
                    dt.column(1).search($(this).val()).draw();
                });

                // Filtrar por PNF buscando el texto en la Columna 0 (que tiene el texto oculto)
                $(`#filtro_pnf_${periodoId}`).on('change', function() {
                    dt.column(0).search($(this).val()).draw();
                });

                // Botón limpiar ambos
                $(`.btn-limpiar-filtros-dt[data-periodo-id="${periodoId}"]`).on('click', function() {
                    $(`#filtro_profesor_${periodoId}`).val('').trigger('change');
                    $(`#filtro_pnf_${periodoId}`).val('').trigger('change');
                    dt.search('').columns().search('').draw();
                });
            });
        }

        // Automatización de IDs para creación de Secciones
        const modalSeccion = document.getElementById('modalSeccion');
        if (modalSeccion) {
            modalSeccion.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('id_periodo_hidden').value = button.getAttribute('data-periodo-id');
            });
        }

        // Relleno dinámico del Modal de Edición de Cohorte y Período
        const modalEditarCohorte = document.getElementById('modalEditarCohorte');
        if (modalEditarCohorte) {
            modalEditarCohorte.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                document.getElementById('edit_numero_cohorte').value = button.getAttribute('data-numero');
                document.getElementById('edit_descripcion_cohorte').value = button.getAttribute('data-descripcion');
                document.getElementById('edit_estatus_cohorte').value = button.getAttribute('data-estatus');

                document.getElementById('edit_fecha_inicio').value = button.getAttribute('data-fecha-inicio') || '';
                document.getElementById('edit_fecha_fin').value = button.getAttribute('data-fecha-fin') || '';

                document.getElementById('formEditarCohorte').action = `/cohortes/${button.getAttribute('data-id')}`;
            });
        }

        // Relleno dinámico del Modal de Edición de Sección (Protegiendo el PNF)
        const modalEditarSeccion = document.getElementById('modalEditarSeccion');
        if (modalEditarSeccion) {
            modalEditarSeccion.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                document.getElementById('edit_id_pnf').value = button.getAttribute('data-pnf');
                document.getElementById('edit_pnf_nombre_display').value = button.getAttribute('data-pnf-nombre');
                document.getElementById('edit_nombre_seccion').value = button.getAttribute('data-nombre');
                document.getElementById('edit_estatus_seccion').value = button.getAttribute('data-estatus');

                document.getElementById('formEditarSeccion').action = `/secciones/${button.getAttribute('data-id')}`;
            });
        }

        // Alertas de Eliminación
        document.querySelectorAll('.btn-delete-cohorte').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar Cohorte y Período?',
                    html: "Esta acción borrará <b>EN CASCADA</b> la cohorte, su período y secciones.<br><br><i>No se puede deshacer.</i>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar TODO',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(this.getAttribute('form')).submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-delete-seccion').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar Sección?',
                    text: "Esta sección será retirada del sistema.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(this.getAttribute('form')).submit();
                    }
                });
            });
        });

        // ==========================================
        // AUTOMATIZACIÓN NÚMEROS ROMANOS Y FECHAS
        // ==========================================
        const inputArabe = document.getElementById('input_numero_arabe');
        if (inputArabe) {
            const previewRomano = document.getElementById('preview_romano');
            const inputRomanoFinal = document.getElementById('numero_cohorte_final');
            const inputFechaInicio = document.getElementById('input_fecha_inicio');
            const inputFechaFin = document.getElementById('input_fecha_fin');

            function convertirAResultadoRomano(num) {
                const romanos = [{
                        val: 1000,
                        numeral: 'M'
                    }, {
                        val: 900,
                        numeral: 'CM'
                    },
                    {
                        val: 500,
                        numeral: 'D'
                    }, {
                        val: 400,
                        numeral: 'CD'
                    },
                    {
                        val: 100,
                        numeral: 'C'
                    }, {
                        val: 90,
                        numeral: 'XC'
                    },
                    {
                        val: 50,
                        numeral: 'L'
                    }, {
                        val: 40,
                        numeral: 'XL'
                    },
                    {
                        val: 10,
                        numeral: 'X'
                    }, {
                        val: 9,
                        numeral: 'IX'
                    },
                    {
                        val: 5,
                        numeral: 'V'
                    }, {
                        val: 4,
                        numeral: 'IV'
                    },
                    {
                        val: 1,
                        numeral: 'I'
                    }
                ];
                let resultado = '';
                let n = parseInt(num);
                if (isNaN(n) || n <= 0) return '---';
                for (let i = 0; i < romanos.length; i++) {
                    while (n >= romanos[i].val) {
                        resultado += romanos[i].numeral;
                        n -= romanos[i].val;
                    }
                }
                return resultado;
            }

            function actualizarValoresCohorte() {
                const val = inputArabe.value;
                const num = parseInt(val);

                if (num && num > 0) {
                    const romano = convertirAResultadoRomano(num);
                    const textoFinal = romano + ' COHORTE';
                    previewRomano.textContent = textoFinal;
                    inputRomanoFinal.value = textoFinal;

                    const anioInicio = 2023 + (num - 1);
                    const anioFin = anioInicio + 1;

                    if (num === 3) {
                        if (inputFechaInicio) inputFechaInicio.value = '2025-11-05';
                        if (inputFechaFin) inputFechaFin.value = '2026-07-29';
                    } else {
                        if (inputFechaInicio) {
                            inputFechaInicio.value = `${anioInicio}-08-01`;
                        }
                        if (inputFechaFin) {
                            inputFechaFin.value = `${anioFin}-07-27`;
                        }
                    }
                } else {
                    previewRomano.textContent = '--- COHORTE';
                    inputRomanoFinal.value = '';
                    if (inputFechaInicio) inputFechaInicio.value = '';
                    if (inputFechaFin) inputFechaFin.value = '';
                }
            }

            inputArabe.addEventListener('input', actualizarValoresCohorte);
            inputArabe.addEventListener('change', actualizarValoresCohorte);
        }

        // ==========================================
        // AUTOMATIZACIÓN DEL NOMBRE DE LA SECCIÓN
        // ==========================================
        const selectPnfSeccion = document.getElementById('select_pnf_seccion');
        const inputNumeroSeccion = document.getElementById('numero_correlativo_seccion');
        const inputNombreFinalSeccion = document.getElementById('nombre_seccion_final');

        function actualizarNombreSeccion() {
            if (!selectPnfSeccion || !inputNumeroSeccion || !inputNombreFinalSeccion) return;

            const opcionSeleccionada = selectPnfSeccion.options[selectPnfSeccion.selectedIndex];
            const nombrePnf = opcionSeleccionada ? opcionSeleccionada.getAttribute('data-nombre') : '';

            let numero = inputNumeroSeccion.value;

            if (nombrePnf && numero) {
                const numeroFormateado = numero.toString().padStart(2, '0');
                inputNombreFinalSeccion.value = `${nombrePnf} - ${numeroFormateado}`;
            } else {
                inputNombreFinalSeccion.value = '';
            }
        }

        if (selectPnfSeccion) selectPnfSeccion.addEventListener('change', actualizarNombreSeccion);
        if (inputNumeroSeccion) inputNumeroSeccion.addEventListener('input', actualizarNombreSeccion);

        // Limpiar el modal cuando se cierre para que no queden datos viejos
        const modalSeccionObj = document.getElementById('modalSeccion');
        if (modalSeccionObj) {
            modalSeccionObj.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createSeccionForm').reset();
                inputNombreFinalSeccion.value = '';
            });
        }

    });
</script>
@endpush