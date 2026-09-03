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
            <i class="bi bi-plus-lg me-1"></i> Nueva Cohorte
        </button>
    </div>

    <!-- PANEL DE FILTROS AVANZADOS (RECUPERADO DE TU CRUD VIEJO) -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title text-dark mb-0 fs-6 fw-bold">
                <i class="bi bi-filter-circle me-2 text-primary"></i> Filtros de Búsqueda y Localización
            </h5>
        </div>
        <div class="card-body bg-white py-3">
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
                        @foreach($cohortes as $cohorteFiltro)
                            <option value="{{ $cohorteFiltro->id_cohortes }}">Cohorte {{ $cohorteFiltro->numero_cohorte }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white py-2 d-flex justify-content-end border-0">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- ACORDEÓN PRINCIPAL: COHORTES -->
    <div class="accordion shadow-sm" id="accordionCohortes">
        @forelse($cohortes as $cohorte)
            <div class="accordion-item mb-3 border-0 rounded bg-white shadow-sm">
                <h2 class="accordion-header d-flex align-items-center bg-white rounded border" id="headingCohorte{{ $cohorte->id_cohortes }}">
                    <button class="accordion-button collapsed fw-bold fs-5 text-dark bg-transparent shadow-none flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCohorte{{ $cohorte->id_cohortes }}" aria-expanded="false" aria-controls="collapseCohorte{{ $cohorte->id_cohortes }}">
                        <i class="bi bi-folder-fill text-warning me-3 fs-4"></i>
                        {{ $cohorte->numero_cohorte }} — <span class="fw-normal text-muted">{{ $cohorte->descripcion_cohorte }}</span>
                        
                        <!-- BADGE ESTATUS -->
                        <span class="badge {{ $cohorte->estatus_cohorte == 'Activo' ? 'bg-success' : 'bg-secondary' }} ms-3 fs-6">
                            {{ $cohorte->estatus_cohorte }}
                        </span>

                        <!-- CONTADOR AVANZADO DE PERIODOS -->
                        <span class="badge bg-light text-dark border ms-2 fw-normal small">
                            <i class="bi bi-calendar-range me-1 text-primary"></i> {{ $cohorte->periodosAcademicos->count() }} Períodos
                        </span>
                    </button>

                    <!-- ACCIONES DE COHORTE -->
                    <div class="pe-3 d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-primary fw-bold" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditarCohorte"
                                data-id="{{ $cohorte->id_cohortes }}"
                                data-numero="{{ $cohorte->numero_cohorte }}"
                                data-descripcion="{{ $cohorte->descripcion_cohorte }}"
                                data-estatus="{{ $cohorte->estatus_cohorte }}"
                                title="Editar Cohorte">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form action="{{ route('cohortes.destroy', $cohorte->id_cohortes) }}" method="POST" class="d-inline form-delete-cohorte">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold btn-delete-cohorte" title="Eliminar Cohorte">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </h2>
                
                <div id="collapseCohorte{{ $cohorte->id_cohortes }}" class="accordion-collapse collapse" aria-labelledby="headingCohorte{{ $cohorte->id_cohortes }}" data-bs-parent="#accordionCohortes">
                    <div class="accordion-body bg-light p-4 border border-top-0 rounded-bottom">

                        <!-- HEADER PERÍODOS -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-secondary mb-0">
                                <i class="bi bi-calendar-range me-2"></i> Períodos Académicos de esta Cohorte
                            </h5>
                            <button class="btn btn-sm btn-primary fw-bold btn-add-periodo" data-cohorte-id="{{ $cohorte->id_cohortes }}" data-bs-toggle="modal" data-bs-target="#modalPeriodo">
                                <i class="bi bi-plus-circle me-1"></i> Añadir Período
                            </button>
                        </div>

                        @if($cohorte->periodosAcademicos->isEmpty())
                            <div class="alert alert-warning text-center border-0 shadow-sm py-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> No hay períodos académicos registrados para esta cohorte.
                            </div>
                        @else
                            <!-- ACORDEÓN SECUNDARIO: PERÍODOS -->
                            <div class="accordion" id="accordionPeriodos{{ $cohorte->id_cohortes }}">
                                @foreach($cohorte->periodosAcademicos as $periodo)
                                    <div class="accordion-item mb-2 border shadow-sm rounded bg-white">
                                        <h2 class="accordion-header d-flex align-items-center bg-white" id="headingPeriodo{{ $periodo->id_periodo }}">
                                            <button class="accordion-button collapsed bg-white text-dark py-2 shadow-none flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePeriodo{{ $periodo->id_periodo }}" aria-expanded="false" aria-controls="collapsePeriodo{{ $periodo->id_periodo }}">
                                                <i class="bi bi-calendar2-check text-info me-2 fs-5"></i>
                                                <span class="fw-bold">Período:</span> {{ $periodo->fecha_inicio ? $periodo->fecha_inicio->format('d/m/Y') : 'N/D' }} al {{ $periodo->fecha_fin ? $periodo->fecha_fin->format('d/m/Y') : 'N/D' }}
                                                
                                                <!-- ESTATUS PERÍODO -->
                                                <span class="badge {{ $periodo->estatus_periodo == 'Activo' ? 'bg-success' : 'bg-secondary' }} ms-3">
                                                    {{ $periodo->estatus_periodo }}
                                                </span>

                                                <!-- CONTADOR DE SECCIONES -->
                                                <span class="badge bg-light text-dark border ms-2 fw-normal small">
                                                    <i class="bi bi-layers me-1 text-success"></i> {{ $periodo->secciones->count() }} Secciones
                                                </span>
                                            </button>

                                            <!-- ACCIONES DE PERÍODO -->
                                            <div class="pe-3 d-flex align-items-center gap-1">
                                                <button class="btn btn-sm btn-outline-info fw-bold text-dark" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditarPeriodo"
                                                        data-id="{{ $periodo->id_periodo }}"
                                                        data-inicio="{{ $periodo->fecha_inicio ? $periodo->fecha_inicio->format('Y-m-d') : '' }}"
                                                        data-fin="{{ $periodo->fecha_fin ? $periodo->fecha_fin->format('Y-m-d') : '' }}"
                                                        data-estatus="{{ $periodo->estatus_periodo }}"
                                                        title="Editar Período">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form action="{{ route('periodos-academicos.destroy', $periodo->id_periodo) }}" method="POST" class="d-inline form-delete-periodo">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold btn-delete-periodo" title="Eliminar Período">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </h2>
                                        
                                        <div id="collapsePeriodo{{ $periodo->id_periodo }}" class="accordion-collapse collapse" aria-labelledby="headingPeriodo{{ $periodo->id_periodo }}" data-bs-parent="#accordionPeriodos{{ $cohorte->id_cohortes }}">
                                            <div class="accordion-body bg-white p-3">
                                                
                                                <!-- HEADER SECCIONES -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-layers me-2"></i> Secciones Activas</h6>
                                                    <button class="btn btn-sm btn-success fw-bold btn-add-seccion" data-periodo-id="{{ $periodo->id_periodo }}" data-bs-toggle="modal" data-bs-target="#modalSeccion">
                                                        <i class="bi bi-plus-circle me-1"></i> Añadir Sección
                                                    </button>
                                                </div>

                                                @if($periodo->secciones->isEmpty())
                                                    <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Este período no tiene secciones asignadas.</p>
                                                @else
                                                    <!-- TABLA SECCIONES ENRIQUECIDA -->
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-hover table-striped align-middle border mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 25%;">Sección</th>
                                                                    <th style="width: 40%;">PNF</th>
                                                                    <th class="text-center" style="width: 15%;">Estatus</th>
                                                                    <th class="text-center" style="width: 20%;">Acciones Avanzadas</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($periodo->secciones as $seccion)
                                                                    <tr>
                                                                        <td class="fw-semibold text-dark">{{ $seccion->nombre_seccion }}</td>
                                                                        <td class="small text-muted">{{ $seccion->pnf->nombre_pnf ?? 'N/A' }}</td>
                                                                        <td class="text-center">
                                                                            <span class="badge {{ $seccion->estatus_seccion == 'Activa' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                                                                {{ $seccion->estatus_seccion }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <!-- ENLACE DIRECTO AL SHOW PROFUNDO (MATRÍCULA Y ASISTENCIAS) -->
                                                                            <a href="{{ route('secciones.show', $seccion->id_seccion) }}" class="btn btn-sm btn-light text-primary border me-1 shadow-sm" title="Gestionar Estudiantes y Asistencias">
                                                                                <i class="bi bi-eye-fill me-1"></i> Gestionar
                                                                            </a>
                                                                            <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-inline form-delete-seccion">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-sm btn-light text-danger border btn-delete-seccion shadow-sm" title="Eliminar Sección">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
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
            <!-- EMPTY STATE -->
            <div class="text-center py-5 bg-white rounded shadow-sm border">
                <i class="bi bi-diagram-3 text-muted opacity-50 d-block mb-3" style="font-size: 4rem;"></i>
                <h5 class="fw-bold text-dark">No hay Estructura Académica</h5>
                <p class="text-muted">Comienza creando la primera cohorte para habilitar períodos y secciones.</p>
                <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalCohorte">
                    <i class="bi bi-plus-lg me-1"></i> Crear Primera Cohorte
                </button>
            </div>
        @endforelse
    </div>

    <!-- ========================================== -->
    <!-- MODALES DE CREACIÓN Y EDICIÓN              -->
    <!-- ========================================== -->

    <!-- MODAL: NUEVA COHORTE -->
    <div class="modal fade" id="modalCohorte" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('cohortes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-folder-plus me-2"></i>Nueva Cohorte</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                            <input type="text" class="form-control" name="numero_cohorte" required placeholder="Ej: III COHORTE (2025)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Descripción</label>
                            <input type="text" class="form-control" name="descripcion_cohorte" required placeholder="Período académico general 2025-2026">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Estatus</label>
                            <select class="form-select" name="estatus_cohorte" required>
                                <option value="Activo" selected>Activo</option>
                                <option value="Finalizada">Finalizada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Guardar Cohorte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDITAR COHORTE -->
    <div class="modal fade" id="modalEditarCohorte" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form id="formEditarCohorte" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Cohorte</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Número de Cohorte</label>
                            <input type="text" class="form-control" name="numero_cohorte" id="edit_numero_cohorte" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Descripción</label>
                            <input type="text" class="form-control" name="descripcion_cohorte" id="edit_descripcion_cohorte" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Estatus</label>
                            <select class="form-select" name="estatus_cohorte" id="edit_estatus_cohorte" required>
                                <option value="Activo">Activo</option>
                                <option value="Finalizada">Finalizada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Actualizar Cohorte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: NUEVO PERÍODO ACADÉMICO -->
    <div class="modal fade" id="modalPeriodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('periodos-academicos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_cohortes" id="id_cohortes_hidden" value="">
                    
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i>Nuevo Período Académico</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Estatus</label>
                            <select class="form-select" name="estatus_periodo" required>
                                <option value="Activo" selected>Activo</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn text-white bg-info fw-bold">Guardar Período</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDITAR PERÍODO ACADÉMICO -->
    <div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form id="formEditarPeriodo" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Período Académico</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="edit_fecha_inicio" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" id="edit_fecha_fin" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Estatus</label>
                            <select class="form-select" name="estatus_periodo" id="edit_estatus_periodo" required>
                                <option value="Activo">Activo</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn text-white bg-info fw-bold">Actualizar Período</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: NUEVA SECCIÓN -->
    <div class="modal fade" id="modalSeccion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('secciones.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_periodo" id="id_periodo_hidden" value="">
                    
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-layers me-2"></i>Nueva Sección Académica</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">PNF (Programa Nacional de Formación)</label>
                            <select class="form-select" name="id_pnf" required>
                                <option value="" selected disabled>Seleccione un PNF...</option>
                                @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nombre de la Sección</label>
                            <input type="text" class="form-control" name="nombre_seccion" required placeholder="Ej: Sec-QUI-01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Estatus Inicial</label>
                            <select class="form-select" name="estatus_seccion" required>
                                <option value="Activa" selected>Activa</option>
                                <option value="Inactiva">Inactiva</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold">Guardar Sección</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    
    // 0. Inicialización de Select2 con tema Bootstrap 5 para los filtros avanzados
    if ($.fn.select2) {
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }

    // 1. Limpiar Filtros
    $('#btn-limpiar-filtros').on('click', function() {
        $('#filtro_pnf, #filtro_profesor, #filtro_empresa, #filtro_cohorte').val(null).trigger('change');
    });

    // 2. Automatización de IDs para creación de Períodos
    const modalPeriodo = document.getElementById('modalPeriodo');
    if (modalPeriodo) {
        modalPeriodo.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('id_cohortes_hidden').value = button.getAttribute('data-cohorte-id');
        });
    }

    // 3. Automatización de IDs para creación de Secciones
    const modalSeccion = document.getElementById('modalSeccion');
    if (modalSeccion) {
        modalSeccion.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('id_periodo_hidden').value = button.getAttribute('data-periodo-id');
        });
    }

    // 4. Relleno dinámico del Modal de Edición de Cohorte
    const modalEditarCohorte = document.getElementById('modalEditarCohorte');
    if (modalEditarCohorte) {
        modalEditarCohorte.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            document.getElementById('edit_numero_cohorte').value = button.getAttribute('data-numero');
            document.getElementById('edit_descripcion_cohorte').value = button.getAttribute('data-descripcion');
            document.getElementById('edit_estatus_cohorte').value = button.getAttribute('data-estatus');
            
            document.getElementById('formEditarCohorte').action = `/cohortes/${id}`;
        });
    }

    // 5. Relleno dinámico del Modal de Edición de Período
    const modalEditarPeriodo = document.getElementById('modalEditarPeriodo');
    if (modalEditarPeriodo) {
        modalEditarPeriodo.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            document.getElementById('edit_fecha_inicio').value = button.getAttribute('data-inicio');
            document.getElementById('edit_fecha_fin').value = button.getAttribute('data-fin');
            document.getElementById('edit_estatus_periodo').value = button.getAttribute('data-estatus');
            
            document.getElementById('formEditarPeriodo').action = `/periodos-academicos/${id}`;
        });
    }

    // 6. Alertas SweetAlert2 para Eliminación en Cascada (Cohortes)
    document.querySelectorAll('.btn-delete-cohorte').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Eliminar Cohorte?',
                html: "<strong>¡PELIGRO EXTREMO!</strong><br><br>Esta acción borrará <b>EN CASCADA</b> la cohorte, todos sus períodos académicos y todas las secciones asignadas.<br><br><i>Esta acción es destructiva y no se puede deshacer.</i>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Sí, eliminar TODO',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // 7. Alertas SweetAlert2 para Períodos
    document.querySelectorAll('.btn-delete-periodo').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Eliminar Período?',
                text: "Se eliminará el período y todas las secciones vinculadas a él.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // 8. Alertas SweetAlert2 para Secciones
    document.querySelectorAll('.btn-delete-seccion').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
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
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endpush