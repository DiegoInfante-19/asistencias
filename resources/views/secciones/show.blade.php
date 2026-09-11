@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- 1. ENCABEZADO PRINCIPAL Y PANEL DE ESTADÍSTICAS FIJO -->
    <div class="row g-4 mb-4">
        <!-- Tarjeta Principal de Información -->
        <div class="col-lg-6">
            <div class="card h-100 border shadow-sm rounded">
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
            <div class="card border shadow-sm h-100 rounded">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom rounded-top">
                    <h5 class="mb-0 fw-bold text-dark fs-6">
                        <i class="bi bi-pie-chart-fill text-primary me-2"></i> Estadísticas de la Sección
                    </h5>
                    <span class="badge bg-primary px-3 py-2 fs-6">Total: {{ $seccion->inscripciones->count() }}</span>
                </div>
                <div class="card-body p-3 bg-white d-flex flex-column justify-content-between rounded-bottom">
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

    <!-- 2. TARJETA CONTENEDORA DE PESTAÑAS (TABS) -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fw-bold fs-6 me-auto">
                <i class="bi bi-folder2-open text-primary me-1"></i> Gestión de la Sección Académica
            </h5>
        </div>

        <div class="card-header bg-light pt-2 pb-0 border-top border-bottom">
            <ul class="nav nav-tabs card-header-tabs nav-fill" id="seccionTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark py-3" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes-pane" type="button" role="tab" aria-controls="estudiantes-pane" aria-selected="true">
                        <i class="bi bi-people-fill me-1 text-primary"></i> Estudiantes Inscritos ({{ $seccion->inscripciones->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark py-3" id="docentes-tab" data-bs-toggle="tab" data-bs-target="#docentes-pane" type="button" role="tab" aria-controls="docentes-pane" aria-selected="false">
                        <i class="bi bi-person-video3 me-1 text-warning"></i> Docentes Asignados ({{ $seccion->profesores->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark py-3" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial-pane" type="button" role="tab" aria-controls="historial-pane" aria-selected="false">
                        <i class="bi bi-calendar-check-fill me-1 text-success"></i> Historial y Asistencias ({{ $seccion->sesiones->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body bg-white p-4">
            <div class="tab-content" id="seccionTabContent">
                <!-- PESTAÑA 1: MATRÍCULA Y NÓMINA -->
                <div class="tab-pane fade show active" id="estudiantes-pane" role="tabpanel" aria-labelledby="estudiantes-tab" tabindex="0">
                    @include('secciones.partials.tab_estudiantes')
                </div>

                <!-- PESTAÑA 2: DOCENTES ASIGNADOS -->
                <div class="tab-pane fade" id="docentes-pane" role="tabpanel" aria-labelledby="docentes-tab" tabindex="0">
                    @include('secciones.partials.tab_docentes')
                </div>

                <!-- PESTAÑA 3: HISTORIAL Y ASISTENCIAS -->
                <div class="tab-pane fade" id="historial-pane" role="tabpanel" aria-labelledby="historial-tab" tabindex="0">
                    @include('secciones.partials.tab_sesiones')
                </div>
            </div>
        </div>

        <div class="card-footer bg-light py-2 text-muted small">
            Secciones operativas del expediente del módulo de secciones.
        </div>
    </div>
</div>

<!-- INCLUIMOS EL ARCHIVO CENTRAL DE MODALES -->
@include('secciones.partials.modales_seccion')

@endsection

@section('styles')
<style>
    /* Estética unificada para las pestañas y corrección del solapamiento */
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

    /* Blindaje visual para tarjetas internas en los tabs */
    .tab-content .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        background-color: #ffffff !important;
    }

    .tab-content .card .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #dee2e6 !important;
    }

    .tab-content .card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
    }
</style>
@endsection

@push('scripts')
{{ $dataTable->html()->scripts() }}
{{ $profesorDataTable->html()->scripts() }}
<!-- IMPORTAMOS LOS SCRIPTS DE LA NUEVA TABLA DE SESIONES -->
{{ $sesionesDataTable->html()->scripts() }}

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