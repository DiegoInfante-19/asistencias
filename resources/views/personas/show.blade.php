@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- HEADER Y BOTONERA SUPERIOR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-person-vcard me-2"></i> Expediente del Estudiante
        </h3>
        <div>
            <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left-circle me-1"></i> Directorio
            </a>
            <a href="{{ route('personas.edit', $persona->id_personas) }}" class="btn btn-warning fw-bold">
                <i class="bi bi-pencil-square me-1"></i> Editar Datos
            </a>
        </div>
    </div>

    <!-- TARJETA DE DATOS BÁSICOS (PERFIL PRINCIPAL) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Columna de Avatar/Icono -->
                <div class="col-md-2 d-flex justify-content-center align-items-center border-end">
                    <i class="bi bi-person-bounding-box text-secondary" style="font-size: 5rem;"></i>
                </div>
                
                <!-- Columna de Información -->
                <div class="col-md-10">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Cédula</span>
                            <span class="fs-5">{{ $persona->cedula }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Nombres</span>
                            <span class="fs-5">{{ $persona->nombres }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Apellidos</span>
                            <span class="fs-5">{{ $persona->apellidos }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Fecha de Nacimiento</span>
                            <span>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Correo Electrónico</span>
                            <span>{{ $persona->correo_electronico ?? 'No registrado' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Lugar de Nacimiento</span>
                            <span>{{ $persona->lugar_nacimiento_personas }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block small fw-bold">Dirección Completa</span>
                            <span>{{ $persona->direccion }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MENSAJES DE ALERTA GLOBALES PARA LAS PESTAÑAS -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ESQUELETO DEL MEGA-CRUD (SISTEMA DE PESTAÑAS) -->
    <div class="card shadow-sm border-0">
        <!-- Navegación de las pestañas -->
        <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
            <ul class="nav nav-tabs border-0" id="expedienteTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-secondary" id="telefonos-tab" data-bs-toggle="tab" data-bs-target="#telefonos" type="button" role="tab">
                        <i class="bi bi-telephone-fill me-1"></i> Teléfonos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="academico-tab" data-bs-toggle="tab" data-bs-target="#academico" type="button" role="tab">
                        <i class="bi bi-mortarboard-fill me-1"></i> Exp. Académico
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="formacion-tab" data-bs-toggle="tab" data-bs-target="#formacion" type="button" role="tab">
                        <i class="bi bi-award-fill me-1"></i> Formación Previa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="inscripciones-tab" data-bs-toggle="tab" data-bs-target="#inscripciones" type="button" role="tab">
                        <i class="bi bi-calendar-check-fill me-1"></i> Control Estudio
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="laboral-tab" data-bs-toggle="tab" data-bs-target="#laboral" type="button" role="tab">
                        <i class="bi bi-building me-1"></i> Perfil Laboral
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="observaciones-tab" data-bs-toggle="tab" data-bs-target="#observaciones" type="button" role="tab">
                        <i class="bi bi-journal-text me-1"></i> Observaciones
                    </button>
                </li>
            </ul>
        </div>
        <!-- Contenido de las pestañas (Fase temporal, se dividirá en Partials después) -->
        <div class="card-body bg-light border-top">
            <div class="tab-content" id="expedienteTabsContent">
                <!-- Pestaña: Teléfonos -->
                <div class="tab-pane fade show active" id="telefonos" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_telefonos')
                    </div>
                </div>
                <!-- Pestaña: Expediente Académico -->
                <div class="tab-pane fade" id="academico" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_titulacion')
                    </div>
                </div>
                <!-- Pestaña: Formación Previa -->
                <div class="tab-pane fade" id="formacion" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_formacion')
                    </div>
                </div>
                <!-- Pestaña: Control de Estudio -->
                <div class="tab-pane fade" id="inscripciones" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_inscripciones')
                    </div>
                </div>
                <!-- Pestaña: Perfil Laboral -->
                <div class="tab-pane fade" id="laboral" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_empresas')
                    </div>
                </div>
                <!-- Pestaña: Observaciones -->
                <div class="tab-pane fade" id="observaciones" role="tabpanel">
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-tools fs-1"></i>
                        @include('personas.partials.tab_observaciones')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Pequeño script para guardar en memoria qué pestaña estaba abierta.
    // Así, si guardamos un teléfono, la página recarga y vuelve a la pestaña de Teléfonos automáticamente.
    $(document).ready(function() {
        var activeTab = localStorage.getItem('activeTabPersona');
        if (activeTab) {
            $('#expedienteTabs button[data-bs-target="' + activeTab + '"]').tab('show');
        }

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeTabPersona', $(e.target).attr('data-bs-target'));
        });
    });
</script>
<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection