@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- 1, 2 Y 3. ENCABEZADO PRINCIPAL Y PANEL DE ESTADÍSTICAS FIJO -->
    <div class="row g-4 mb-4">
        
        <!-- Tarjeta Principal de Información -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 bg-white rounded d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h3 class="fw-bold text-dark mb-0">Sección: {{ $seccion->nombre_seccion }}</h3>
                            <span class="badge {{ $seccion->estatus_seccion === 'Activa' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 fs-6">
                                {{ $seccion->estatus_seccion }}
                            </span>
                        </div>
                        <p class="text-muted mb-1">
                            Cohorte: <strong class="text-primary">{{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }}</strong>
                        </p>
                        <p class="text-muted mb-1">
                            PNF: <strong>{{ $seccion->pnf->nombre_pnf ?? 'N/D' }}</strong> | Período: <strong>{{ $seccion->periodoAcademico ? ($seccion->periodoAcademico->fecha_inicio?->format('Y') . '-' . $seccion->periodoAcademico->fecha_fin?->format('Y')) : 'N/D' }}</strong>
                        </p>
                        <p class="text-muted mb-0 small">
                            @if($seccion->periodoAcademico && $seccion->periodoAcademico->fecha_inicio && $seccion->periodoAcademico->fecha_fin)
                                Inicia el {{ $seccion->periodoAcademico->fecha_inicio->format('d') }} de {{ \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_inicio)->locale('es')->monthName }} del año {{ $seccion->periodoAcademico->fecha_inicio->format('Y') }}<br>
                                y finaliza el {{ $seccion->periodoAcademico->fecha_fin->format('d') }} de {{ \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_fin)->locale('es')->monthName }} del año {{ $seccion->periodoAcademico->fecha_fin->format('Y') }}.
                            @else
                                Fechas de período no disponibles.
                            @endif
                        </p>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('estructura.index') }}" class="btn btn-outline-secondary fw-semibold shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Estadísticas Fijo -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between rounded-top">
                    <h5 class="mb-0 fw-bold text-dark fs-6">
                        <i class="bi bi-pie-chart-fill text-primary me-2"></i> Estadísticas de la Sección
                    </h5>
                    <span class="badge bg-primary px-3 py-2 fs-6">Total: {{ $seccion->inscripciones->count() }}</span>
                </div>
                <div class="card-body p-3 bg-white d-flex flex-column justify-content-between">
                    <div class="accordion accordion-flush" id="subAccordionEstadisticas">
                        
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="subHeadingTitulo">
                                <button class="accordion-button collapsed py-2 small fw-semibold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseTitulo" aria-expanded="false" aria-controls="subCollapseTitulo">
                                    <i class="bi bi-mortarboard me-2 text-info"></i> Por Título a Optar
                                </button>
                            </h2>
                            <div id="subCollapseTitulo" class="accordion-collapse collapse" aria-labelledby="subHeadingTitulo" data-bs-parent="#subAccordionEstadisticas">
                                <div class="accordion-body py-2 px-3 small bg-light">
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $porTitulo = $seccion->inscripciones->groupBy(fn($i) => $i->persona->titulo_base ?? 'Sin Título Especificado');
                                        @endphp
                                        @foreach($porTitulo as $nombreTitulo => $items)
                                            <li class="d-flex justify-content-between py-1 border-bottom-subtle">
                                                <span>{{ $nombreTitulo }}</span>
                                                <span class="fw-bold text-dark">{{ $items->count() }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="subHeadingEstado">
                                <button class="accordion-button collapsed py-2 small fw-semibold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseEstado" aria-expanded="false" aria-controls="subCollapseEstado">
                                    <i class="bi bi-geo-alt me-2 text-danger"></i> Por Estado
                                </button>
                            </h2>
                            <div id="subCollapseEstado" class="accordion-collapse collapse" aria-labelledby="subHeadingEstado" data-bs-parent="#subAccordionEstadisticas">
                                <div class="accordion-body py-2 px-3 small bg-light">
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $porEstado = $seccion->inscripciones->groupBy(function($i) {
                                                return $i->persona->lugarNacimiento?->ciudad?->estado?->nombre_estado ?? 'No registrado';
                                            })->filter(fn($items, $estado) => $estado !== 'No registrado' && $items->count() > 0);
                                        @endphp
                                        @forelse($porEstado as $nombreEstado => $items)
                                            <li class="d-flex justify-content-between py-1 border-bottom-subtle">
                                                <span>{{ $nombreEstado }}</span>
                                                <span class="fw-bold text-dark">{{ $items->count() }}</span>
                                            </li>
                                        @empty
                                            <li class="text-muted text-center py-1">Sin registros geográficos > 0</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="subHeadingExpediente">
                                <button class="accordion-button collapsed py-2 small fw-semibold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseExpediente" aria-expanded="false" aria-controls="subCollapseExpediente">
                                    <i class="bi bi-folder2-open me-2 text-warning"></i> Por Estatus de Expediente
                                </button>
                            </h2>
                            <div id="subCollapseExpediente" class="accordion-collapse collapse" aria-labelledby="subHeadingExpediente" data-bs-parent="#subAccordionEstadisticas">
                                <div class="accordion-body py-2 px-3 small bg-light">
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $porExpediente = $seccion->inscripciones->groupBy(function($i) {
                                                $titulacion = $i->persona->titulacionPersona->first();
                                                if ($titulacion && $titulacion->id_estatus_expediente) {
                                                    $estExp = \App\Models\EstatusExpediente::find($titulacion->id_estatus_expediente);
                                                    return $estExp ? $estExp->nombre_estatus_expediente : 'Sin Estatus';
                                                }
                                                return 'Sin Expediente';
                                            });
                                        @endphp
                                        @foreach($porExpediente as $estatusExp => $items)
                                            <li class="d-flex justify-content-between py-1 border-bottom-subtle">
                                                <span>{{ $estatusExp }}</span>
                                                <span class="fw-bold text-dark">{{ $items->count() }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="subHeadingCohorte">
                                <button class="accordion-button collapsed py-2 small fw-semibold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseCohorte" aria-expanded="false" aria-controls="subCollapseCohorte">
                                    <i class="bi bi-diagram-3 me-2 text-success"></i> Por Cohorte
                                </button>
                            </h2>
                            <div id="subCollapseCohorte" class="accordion-collapse collapse" aria-labelledby="subHeadingCohorte" data-bs-parent="#subAccordionEstadisticas">
                                <div class="accordion-body py-2 px-3 small bg-light">
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $porCohorte = $seccion->inscripciones->groupBy(fn($i) => $i->persona->cohorte->numero_cohorte ?? 'Externa');
                                        @endphp
                                        @foreach($porCohorte as $numCohorte => $items)
                                            <li class="d-flex justify-content-between py-1 border-bottom-subtle">
                                                <span>{{ $numCohorte }}</span>
                                                <span class="fw-bold text-dark">{{ $items->count() }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="subHeadingEmpresa">
                                <button class="accordion-button collapsed py-2 small fw-semibold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseEmpresa" aria-expanded="false" aria-controls="subCollapseEmpresa">
                                    <i class="bi bi-building me-2 text-secondary"></i> Por Empresa
                                </button>
                            </h2>
                            <div id="subCollapseEmpresa" class="accordion-collapse collapse" aria-labelledby="subHeadingEmpresa" data-bs-parent="#subAccordionEstadisticas">
                                <div class="accordion-body py-2 px-3 small bg-light">
                                    <ul class="list-unstyled mb-0">
                                        @php
                                            $porEmpresa = $seccion->inscripciones->groupBy(fn($i) => $i->persona->empresaPersona->first()?->empresa->nombre_empresa ?? 'Independiente');
                                        @endphp
                                        @foreach($porEmpresa as $nombreEmpresa => $items)
                                            <li class="d-flex justify-content-between py-1 border-bottom-subtle">
                                                <span class="text-truncate me-2">{{ $nombreEmpresa }}</span>
                                                <span class="fw-bold text-dark">{{ $items->count() }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TARJETA CONTENEDORA DE PESTAÑAS (TABS) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <ul class="nav nav-tabs nav-fill card-header-tabs" id="seccionTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 text-secondary" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes-pane" type="button" role="tab" aria-controls="estudiantes-pane" aria-selected="true">
                        <i class="bi bi-people-fill fs-5 me-2 text-primary"></i> Estudiantes Inscritos ({{ $seccion->inscripciones->count() }})
                    </button>
                </li>
                <!-- PESTAÑA DOCENTES -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-secondary" id="docentes-tab" data-bs-toggle="tab" data-bs-target="#docentes-pane" type="button" role="tab" aria-controls="docentes-pane" aria-selected="false">
                        <i class="bi bi-person-video3 fs-5 me-2 text-warning"></i> Docentes Asignados ({{ $seccion->profesores->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-secondary" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial-pane" type="button" role="tab" aria-controls="historial-pane" aria-selected="false">
                        <i class="bi bi-calendar-check-fill fs-5 me-2 text-success"></i> Historial y Asistencias ({{ $seccion->sesiones->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body bg-white p-4">
            <div class="tab-content" id="seccionTabContent">
                
                <!-- PESTAÑA 1: MATRÍCULA Y NÓMINA -->
                <div class="tab-pane fade show active" id="estudiantes-pane" role="tabpanel" aria-labelledby="estudiantes-tab" tabindex="0">
                    <div class="card border border-primary-subtle shadow-sm mb-4">
                        <div class="card-body bg-light rounded d-flex flex-column flex-md-row align-items-md-end gap-3 p-3">
                            <div class="flex-grow-1">
                                <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-person-plus me-1 text-success"></i> Añadir Estudiante a la Sección</label>
                                <form id="formMatricular" action="{{ route('secciones.inscribir', $seccion->id_seccion) }}" method="POST">
                                    @csrf
                                    <select name="id_personas" class="form-select select2-buscador" required>
                                        <option value="" selected disabled>Buscar estudiante disponible (Cédula o Nombre)...</option>
                                        @foreach($estudiantesDisponibles as $estudiante)
                                            <option value="{{ $estudiante->id_personas }}">
                                                V-{{ ltrim($estudiante->cedula_personas, 'V-') }} - {{ $estudiante->nombre_corto }} - {{ $estudiante->cohorte->numero_cohorte ?? 'Externa' }} - {{ $estudiante->titulo_base }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <button type="submit" form="formMatricular" class="btn btn-success fw-bold px-4 py-2 h-100 shadow-sm w-100">
                                    <i class="bi bi-plus-circle me-1"></i> Inscribir
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark fs-6"><i class="bi bi-table me-2 text-primary"></i>Nómina Activa</h5>
                        </div>
                        <div class="card-body bg-white p-3">
                            <div class="table-responsive">
                                {{ $dataTable->html()->table(['class' => 'table table-hover table-striped align-middle border w-100']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PESTAÑA 2: DOCENTES ASIGNADOS -->
                <div class="tab-pane fade" id="docentes-pane" role="tabpanel" aria-labelledby="docentes-tab" tabindex="0">
                    <div class="card border border-warning-subtle shadow-sm mb-4">
                        <div class="card-body bg-light rounded d-flex flex-column flex-md-row align-items-md-end gap-3 p-3">
                            <div class="flex-grow-1">
                                <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-person-badge me-1 text-warning"></i> Asignar Docente a la Sección</label>
                                <form id="formDocente" action="{{ route('secciones.asignar-profesor', $seccion->id_seccion) }}" method="POST">
                                    @csrf
                                    <select name="id_profesor" class="form-select select2-profesores" required>
                                        <option value="" selected disabled>Buscar profesor disponible (Cédula o Nombre)...</option>
                                        @foreach($profesoresDisponibles as $profeDisp)
                                            <option value="{{ $profeDisp->id_profesor }}">
                                                V-{{ ltrim($profeDisp->user->cedula_users, 'V-') }} — {{ $profeDisp->user->name_users }} {{ $profeDisp->user->last_name_users }} (PNF: {{ $profeDisp->pnf->nombre_pnf ?? 'Sin PNF' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <button type="submit" form="formDocente" class="btn btn-warning text-dark fw-bold px-4 py-2 h-100 shadow-sm w-100">
                                    <i class="bi bi-plus-circle me-1"></i> Asignar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark fs-6"><i class="bi bi-briefcase me-2 text-warning"></i>Carga Docente de la Sección</h5>
                        </div>
                        <div class="card-body bg-white p-3">
                            <div class="table-responsive">
                                {{ $profesorDataTable->html()->table(['class' => 'table table-hover table-striped align-middle border w-100']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PESTAÑA 3: HISTORIAL Y ASISTENCIAS -->
                <div class="tab-pane fade" id="historial-pane" role="tabpanel" aria-labelledby="historial-tab" tabindex="0">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 fw-bold text-dark fs-6"><i class="bi bi-journal-text me-2 text-success"></i>Auditoría Histórica de Clases y Asistencias</h5>
                            <small class="text-muted">Registro de solo lectura de las sesiones impartidas.</small>
                        </div>
                        <div class="card-body p-4 bg-white">
                            @forelse($seccion->sesiones as $sesion)
                                <div class="card border mb-4 shadow-sm">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <span class="fw-bold text-primary fs-6 me-3">
                                                <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                                            </span>
                                            <span class="badge bg-secondary">Docente: {{ $sesion->profesor->user->name_users ?? 'N/D' }} {{ $sesion->profesor->user->last_name_users ?? '' }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-success me-1">Presentes: {{ $sesion->asistencias->where('estado_asistencia', App\Enums\EstadoAsistencia::Presente)->count() }}</span>
                                            <span class="badge bg-danger me-1">Ausentes: {{ $sesion->asistencias->where('estado_asistencia', App\Enums\EstadoAsistencia::Ausente)->count() }}</span>
                                            <span class="badge bg-warning text-dark">Justificados: {{ $sesion->asistencias->where('estado_asistencia', App\Enums\EstadoAsistencia::Justificado)->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 bg-white">
                                        @if($sesion->observacion_sesion)
                                            <p class="small text-muted mb-3"><strong>Tema / Observación:</strong> {{ $sesion->observacion_sesion }}</p>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Cédula</th>
                                                        <th>Estudiante</th>
                                                        <th class="text-center" style="width: 150px;">Asistencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sesion->asistencias as $asistencia)
                                                        <tr>
                                                            <td class="fw-bold">{{ $asistencia->inscripcionSeccion->persona->cedula_personas ?? 'N/D' }}</td>
                                                            <td>{{ $asistencia->inscripcionSeccion->persona->nombre_completo ?? 'Estudiante Eliminado' }}</td>
                                                            <td class="text-center">
                                                                @if($asistencia->estado_asistencia === App\Enums\EstadoAsistencia::Presente)
                                                                    <span class="badge bg-success w-100 py-1">Presente</span>
                                                                @elseif($asistencia->estado_asistencia === App\Enums\EstadoAsistencia::Ausente)
                                                                    <span class="badge bg-danger w-100 py-1">Ausente</span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark w-100 py-1">Justificado</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5 border rounded bg-light">
                                    <i class="bi bi-clock-history fs-1 d-block mb-2 opacity-50"></i>
                                    <h5>No se han dictado clases para esta sección todavía.</h5>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer bg-light py-2 text-muted small">
            Secciones operativas del expediente del módulo de secciones.
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header-tabs {
        margin-right: 0 !important;
        margin-left: 0 !important;
        margin-bottom: -1px !important;
    }
    .card-header-tabs .nav-link {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.75rem 1rem;
    }
    .card-header-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        background-color: rgba(255, 255, 255, 0.5);
    }
    .card-header-tabs .nav-link.active {
        color: #0d6efd !important;
        background-color: #ffffff !important;
        border-color: #dee2e6 #dee2e6 #ffffff !important;
    }
</style>
@endsection

@push('scripts')
{{ $dataTable->html()->scripts() }}
{{ $profesorDataTable->html()->scripts() }}

<script type="module">
    $(document).ready(function() {
        // Buscador de Estudiantes
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Buscar estudiante disponible (Cédula o Nombre)...',
            allowClear: true
        });

        // Buscador de Profesores
        $('.select2-profesores').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Buscar profesor disponible (Cédula o Nombre)...',
            allowClear: true
        });
    });
</script>
@endpush