@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- ========================================== -->
    <!-- FILA SUPERIOR: INFORMACIÓN Y FILTROS (2 COLUMNAS) -->
    <!-- ========================================== -->
    <div class="row g-4 mb-4">

        <!-- COLUMNA 1: HEADER DE NAVEGACIÓN Y CONTEXTO -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm ecosystem-card">
                <div class="card-body p-4 bg-white d-flex flex-column justify-content-between">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 small">
                                <li class="breadcrumb-item"><a href="{{ route('estructura.index') }}" class="text-decoration-none text-primary fw-semibold"><i class="bi bi-diagram-3-fill me-1"></i> Estructura Académica</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('estructura.index') }}" class="text-decoration-none text-muted">{{ $periodo->cohorte->numero_cohorte ?? 'Cohorte' }}</a></li>
                                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Gestión</li>
                            </ol>
                        </nav>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <h4 class="mb-0 text-dark fw-bold fs-5">
                                <i class="bi bi-calendar-range text-primary me-2"></i> Período: {{ $periodo->fecha_inicio?->format('Y') }} - {{$periodo->fecha_fin?->format('Y') }}
                            </h4>
                            <span class="badge {{ $periodo->estatus_periodo == 'Activo' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 fs-6" style="font-weight: 500;">
                                {{ $periodo->estatus_periodo }}
                            </span>
                        </div>

                        <p class="text-muted mb-0 small">
                            @if($periodo->fecha_inicio && $periodo->fecha_fin)
                            Inicia el {{ $periodo->fecha_inicio->format('d') }} de {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->locale('es')->monthName }} del año {{ $periodo->fecha_inicio->format('Y') }}<br>
                            y finaliza el {{ $periodo->fecha_fin->format('d') }} de {{ \Carbon\Carbon::parse($periodo->fecha_fin)->locale('es')->monthName }} del año {{ $periodo->fecha_fin->format('Y') }}.
                            @else
                            Fechas de período no disponibles.
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <a href="{{ route('estructura.index') }}" class="btn btn-secondary shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Volver a Cohortes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA 2: PANEL DE FILTROS RÁPIDOS -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm ecosystem-card overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <h4 class="card-title text-dark mb-0" style="font-weight: 500;">
                        Filtros de Búsqueda para Secciones
                    </h4>
                </div>

                <div class="card-body p-4 bg-white d-flex flex-column justify-content-between">
                    <div class="row g-3">

                        <!-- FILTRO POR PNF -->
                        <div class="col-12">
                            <label for="filtro_pnf" class="form-label small fw-bold text-muted mb-1">
                                <i class="bi bi-journal-bookmark me-1"></i> Programa Nacional de Formación (PNF)
                            </label>
                            <select id="filtro_pnf" class="form-select select2-filtro">
                                <option value="">Todos los PNF...</option>
                                @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- FILTRO POR DOCENTE -->
                        <div class="col-12">
                            <label for="filtro_profesor" class="form-label small fw-bold text-muted mb-1">
                                <i class="bi bi-person-badge me-1"></i> Docente Asignado
                            </label>
                            <select id="filtro_profesor" class="form-select select2-filtro">
                                <option value="">Cualquier docente...</option>
                                @foreach($profesores as $profesor)
                                @if($profesor->user)
                                <option value="{{ $profesor->id_profesor }}">
                                    {{ $profesor->user->name_users }} {{$profesor->user->last_name_users }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- BOTÓN LIMPIAR FILTROS -->
                    <div class="mt-4 pt-2 border-top">
                        <button type="button" id="btn-limpiar-filtros" class="btn btn-success shadow-sm">
                            <i class="bi bi-x-circle me-1"></i> Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TARJETA PRINCIPAL CON LA TABLA YAJRA        -->
    <!-- ========================================== -->
    <div class="card shadow-sm ecosystem-card">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">
                Registro de Secciones Académicas
            </h4>

            @if($periodo->estatus_periodo === 'Activo')
            <button type="button" class="btn btn-primary fw-bold shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#modalSeccion" data-periodo-id="{{ $periodo->id_periodo }}">
                <i class="bi bi-plus-circle me-1"></i> Añadir Sección
            </button>
            @else
            <span class="badge bg-secondary ms-auto px-3 py-2 fs-6">
                <i class="bi bi-lock-fill me-1"></i> Período Inactivo - Creación Bloqueada
            </span>
            @endif
        </div>

        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover table-striped align-middle w-100 border', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-light py-2 text-muted small border-top">
            Procesamiento jerárquico activo para el período seleccionado.
        </div>
    </div>

</div>

<!-- INCLUIMOS LOS MODALES DE SECCIÓN -->
@include('estructura_academica.partials.modals_seccion')

@endsection

@section('styles')
<style>
    .ecosystem-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05) !important;
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }

    .ecosystem-card .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }

    .ecosystem-card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom-left-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #cbd5e1 !important;
    }

    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-limpiar-filtros-dt {
        background-color: #f8f9fa !important;
        border-color: #cbd5e1 !important;
    }

    .btn-limpiar-filtros-dt:hover {
        background-color: #e9ecef !important;
        border-color: #94a3b8 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: var(--bs-dark-bg-subtle) !important;
        color: var(--bs-dark) !important;
        border: 1px solid var(--bs-dark) !important;
        border-radius: 0.5rem !important;
        padding: 0.15rem 0.5rem !important;
        margin-top: 0.35rem !important;
        margin-bottom: 0.35rem !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: var(--bs-dark) !important;
        opacity: 0.7;
        margin-right: 0.5rem !important;
        font-weight: bold !important;
        border-right: 1px solid rgba(0, 0, 0, 0.1) !important;
        padding-right: 0.4rem !important;
        transition: all 0.2s ease;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: transparent !important;
        color: #dc3545 !important;
        opacity: 1;
    }

    /* ESTILOS DE CORRECCIÓN HOVER PARA ACCIONES */
    .btn-action-view:hover {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #ffffff !important;
    }

    .btn-action-edit:hover {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000000 !important;
    }

    .btn-action-delete:hover {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
    }
</style>
@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inicializar Select2 en los filtros
        $('.select2-filtro').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('#select_pnfs_seccion').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modalSeccion'),
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });

        $('#edit_select_pnfs_seccion').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modalEditarSeccion'),
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });

        // 2. Conectar filtros personalizados al DataTable
        const tableName = 'secciones-table';

        $('#' + tableName).on('preXhr.dt', function(e, settings, data) {
            data.filtro_pnf = $('#filtro_pnf').val();
            data.filtro_profesor = $('#filtro_profesor').val();
        });

        function triggerDatatableDraw() {
            if (window.LaravelDataTables && window.LaravelDataTables[tableName]) {
                window.LaravelDataTables[tableName].draw();
            } else if ($.fn.DataTable.isDataTable('#' + tableName)) {
                $('#' + tableName).DataTable().draw();
            }
        }

        $('#filtro_pnf, #filtro_profesor').on('change', function() {
            triggerDatatableDraw();
        });

        // 3. Botón para limpiar filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $('#filtro_pnf, #filtro_profesor').val(null).trigger('change');
            triggerDatatableDraw();
        });

        // 4. Generador dinámico de nombres
        const selectPnfsSeccion = document.getElementById('select_pnfs_seccion');
        const inputNumeroSeccion = document.getElementById('numero_correlativo_seccion');
        const inputNombreFinalSeccion = document.getElementById('nombre_seccion_final');

        function actualizarNombreSeccion() {
            if (!selectPnfsSeccion || !inputNumeroSeccion || !inputNombreFinalSeccion) return;

            const opcionesSeleccionadas = Array.from(selectPnfsSeccion.selectedOptions);
            const nombresPnfs = opcionesSeleccionadas.map(op => op.getAttribute('data-nombre'));
            let numero = inputNumeroSeccion.value;

            if (nombresPnfs.length > 0 && numero) {
                const numeroFormateado = numero.toString().padStart(2, '0');
                inputNombreFinalSeccion.value = `${nombresPnfs.join(' / ')} - ${numeroFormateado}`;
            } else {
                inputNombreFinalSeccion.value = '';
            }
        }

        $('#select_pnfs_seccion').on('change', actualizarNombreSeccion);
        if (inputNumeroSeccion) inputNumeroSeccion.addEventListener('input', actualizarNombreSeccion);

        // 5. Capturar id_periodo para la creación
        const modalSeccionObj = document.getElementById('modalSeccion');
        if (modalSeccionObj) {
            modalSeccionObj.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const periodoId = button ? button.getAttribute('data-periodo-id') : null;
                if (periodoId) {
                    document.getElementById('id_periodo_hidden').value = periodoId;
                } else {
                    document.getElementById('id_periodo_hidden').value = "{{ $periodo->id_periodo }}";
                }
            });

            modalSeccionObj.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createSeccionForm').reset();
                if (inputNombreFinalSeccion) inputNombreFinalSeccion.value = '';
                $('#select_pnfs_seccion').val(null).trigger('change');
            });
        }

        // 6. Carga dinámica del modal de edición
        const modalEditarSeccion = document.getElementById('modalEditarSeccion');
        if (modalEditarSeccion) {
            modalEditarSeccion.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                document.getElementById('edit_nombre_seccion').value = button.getAttribute('data-nombre');
                document.getElementById('edit_estatus_seccion').value = button.getAttribute('data-estatus');

                try {
                    const pnfsSeleccionados = JSON.parse(button.getAttribute('data-pnfs-json') || '[]');
                    $('#edit_select_pnfs_seccion').val(pnfsSeleccionados).trigger('change');
                } catch(e) {
                    console.error("Error al parsear el JSON de PNFs", e);
                    $('#edit_select_pnfs_seccion').val(null).trigger('change');
                }

                document.getElementById('formEditarSeccion').action = `/secciones/${button.getAttribute('data-id')}`;
            });
        }

        // =======================================================
        // 7. DELEGACIÓN ROBUSTA CON DOBLE CONFIRMACIÓN (SWEETALERT2)
        // =======================================================
        $(document).on('click', '.btn-delete-record', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const formId = $btn.data('form-id');
            
            let formToSubmit = document.getElementById(formId);
            if (!formToSubmit) {
                formToSubmit = $btn.closest('td, div, tr').find('form#' + formId)[0] || $btn.siblings('form')[0];
            }

            if (!formToSubmit) {
                console.error("No se encontró el formulario de eliminación con ID: " + formId);
                return;
            }

            // NIVEL 1: Primera Advertencia
            Swal.fire({
                title: "¿Está seguro que quiere eliminar esta sección?",
                text: "Esta acción la removerá del período actual.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Sí, quiero eliminarla",
                cancelButtonText: "Cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // NIVEL 2: Confirmación Estricta
                    Swal.fire({
                        title: "Confirmación Final",
                        html: "Si elimina esta sección, <b>se perderá el registro de forma permanente</b>.<br><br>¿Confirmar eliminación?",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#000000",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Confirmar y Borrar',
                        cancelButtonText: "Cancelar",
                        reverseButtons: true
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            Swal.fire({
                                title: "Eliminando...",
                                text: "Por favor espere.",
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            formToSubmit.submit();
                        }
                    });
                }
            });
        });
    });
</script>

{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush