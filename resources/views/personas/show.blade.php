@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- TARJETA DE DATOS BÁSICOS (PERFIL PRINCIPAL) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0 d-flex align-items-center" style="font-weight: 500;">
                Información General del Estudiante
            </h4>
            <a href="{{ route('personas.index') }}" class="btn btn-secondary me-2 ms-auto">
                <i class="bi bi-arrow-left-circle me-1"></i> Directorio
            </a>
            <a href="{{ route('personas.edit', $persona->id_personas) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Editar Datos
            </a>
        </div>
        <div class="card-body bg-white py-4">

            <!-- GRILLA PRINCIPAL DE INFORMACIÓN -->
            <div class="row g-3">
                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Cédula</span>
                    <span class="fs-5 text-dark">{{ $persona->cedula_personas }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Nombres</span>
                    <span class="fs-5 text-dark">{{ $persona->primer_nombre_personas }} {{ $persona->segundo_nombre_personas }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Apellidos</span>
                    <span class="fs-5 text-dark">{{ $persona->primer_apellido_personas }} {{ $persona->segundo_apellido_personas }}</span>
                </div>

                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Fecha de Nacimiento</span>
                    <span class="{{ $persona->fecha_nacimiento_personas ? 'fs-5 text-dark' : ' ' }}">
                        {{ $persona->fecha_nacimiento_formateada }}
                    </span>
                    @if($persona->fecha_nacimiento_personas)
                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle ms-1" style="font-size: 0.75rem; vertical-align: middle;">
                        {{ $persona->edad }}
                    </span>
                    @endif
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Correo Electrónico</span>
                    <span class="text-dark text-break">{{ $persona->email_personas ?? 'No registrado' }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold text-primary"><i class="bi bi-bookmark-star-fill me-1"></i>Sello de Ingreso (Cohorte)</span>
                    <span class="fs-6 fw-bold text-dark">{{ $persona->cohorte->numero_cohorte ?? 'No asignada' }}</span>
                </div>

                <div class="col-md-4">
                    <span class="text-muted d-block small fw-bold">Lugar de Nacimiento</span>
                    <span class="fs-6 text-dark fw-semibold">
                        <i class="bi bi-geo-alt-fill text-secondary me-1"></i>
                        {{ $persona->lugarNacimiento ? $persona->lugarNacimiento->direccion_completa : 'No registrado' }}
                    </span>
                </div>
                <div class="col-md-8">
                    <span class="text-muted d-block small fw-bold">Detalles de Dirección</span>
                    <span class="fs-6 text-dark">{{ $persona->lugarNacimiento->detalles_adicionales ?? 'Sin detalles adicionales registrados' }}</span>
                </div>

                <!-- TELÉFONOS DE CONTACTO ADAPTABLES -->
                <div class="col-12 mt-3">
                    <span class="text-muted d-block small fw-bold mb-2"><i class="bi bi-telephone-fill text-primary me-1"></i> Teléfonos de Contacto</span>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($persona->telefonos as $tel)
                        <div class="badge bg-light text-dark border p-2 d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-phone text-secondary"></i>
                            <span class="fw-semibold">{{ $tel->numero_telefono_personas ?? $tel->numero_telefono ?? 'S/N' }}</span>
                            @if(isset($tel->tipo_telefono))
                            <span class="badge text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle font-monospace px-2 py-1" style="font-size: 0.7rem;">{{ $tel->tipo_telefono }}</span>
                            @endif
                        </div>
                        @empty
                        <span class="text-muted small fst-italic">No hay números telefónicos registrados.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- RESUMEN RÁPIDO DEL EXPEDIENTE -->
            <hr class="my-4 text-muted" style="opacity: 0.15;">
            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-clipboard-data me-1"></i> Resumen Rápido del Expediente</h6>

            <div class="row g-3 justify-content-center">

                <!-- 0. Continuidad del Estudiante -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-person-gear me-1"></i> Continuidad</span>

                        @if(is_null($persona->continua_estudios))
                        <span class="fs-6 text-primary fw-bold d-block mt-1 text-truncate" title="Campo vacío. El sistema asume por defecto que sí continúa.">
                            Sin definir...
                        </span>
                        @elseif($persona->continua_estudios)
                        <span class="fs-6 text-success fw-bold d-block mt-1 text-truncate">
                            Sí, continúa estudiando
                        </span>
                        @else
                        <span class="fs-6 text-danger fw-bold d-block mt-1 text-truncate">
                            No continúa (Inactivo)
                        </span>
                        @endif
                    </div>
                </div>

                <!-- 1. Título a Optar -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-award-fill me-1"></i> Título a Optar</span>
                        <span class="fs-6 text-dark fw-bold d-block mt-1 text-truncate" title="{{ $persona->titulacionPersona?->tituloPnf?->nombre_titulo_pnf ?? $persona->titulacionPersona?->nombre_titulo_especifico }}">
                            {{ $persona->titulacionPersona?->tituloPnf?->nombre_titulo_pnf ?? $persona->titulacionPersona?->nombre_titulo_especifico ?? 'No especificado' }}
                        </span>
                    </div>
                </div>

                <!-- 2. Nombre del PNF -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-mortarboard-fill me-1"></i> Nombre del PNF</span>
                        <span class="fs-6 text-dark fw-bold d-block mt-1 text-truncate" title="{{ $persona->titulacionPersona->pnf->nombre_pnf ?? '' }}">
                            {{ $persona->titulacionPersona->pnf->nombre_pnf ?? 'Sin PNF asignado' }}
                        </span>
                    </div>
                </div>

                <!-- 3. Estatus del Expediente -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate">
                            <i class="bi bi-person-vcard me-1"></i> Estatus del Expediente
                        </span>
                        @if($persona->titulacionPersona && $persona->titulacionPersona->estatus)
                        <span class="badge text-primary-emphasis bg-primary-subtle border border-primary-subtle mt-1 text-truncate d-inline-block mw-100 fs-6 px-2 py-1" title="{{ $persona->titulacionPersona->estatus->nombre_estatus_expediente ?? $persona->titulacionPersona->estatus->nombre_estatus ?? '' }}">
                            {{ $persona->titulacionPersona->estatus->nombre_estatus_expediente ?? $persona->titulacionPersona->estatus->nombre_estatus ?? 'Sin asignar todavía' }}
                        </span>
                        @else
                        <span class="fs-6 text-muted fw-semibold d-block mt-1 text-truncate">Sin asignar todavía</span>
                        @endif
                    </div>
                </div>

                <!-- 4. Sección -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-calendar-check-fill me-1"></i> Sección</span>
                        @if($persona->inscripcionActiva && $persona->inscripcionActiva->seccion)
                        <span class="fs-6 text-success fw-bold d-block mt-1 text-truncate" title="{{ $persona->inscripcionActiva->seccion->nombre_seccion }}">
                            {{ $persona->inscripcionActiva->seccion->nombre_seccion }}
                        </span>
                        @else
                        <span class="fs-6 text-muted fw-semibold d-block mt-1 text-truncate">No matriculado</span>
                        @endif
                    </div>
                </div>

                <!-- 5. Empresa -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-briefcase-fill me-1"></i> Empresa</span>
                        @if($persona->empresaPersona && $persona->empresaPersona->empresa)
                        <span class="fs-6 text-dark fw-bold d-block mt-1 text-truncate" title="{{ $persona->empresaPersona->empresa->nombre_empresa }}">
                            {{ $persona->empresaPersona->empresa->nombre_empresa }}
                        </span>
                        <span class="small text-muted text-truncate d-block" title="{{ $persona->empresaPersona->cargo->descripcion_cargo ?? '' }}">
                            {{ $persona->empresaPersona->cargo->descripcion_cargo ?? '' }}
                        </span>
                        @else
                        <span class="fs-6 text-muted fw-semibold d-block mt-1 text-truncate">Sin registro</span>
                        @endif
                    </div>
                </div>

                <!-- 6. Títulos Previos -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded border border-2 border-secondary-subtle h-100 overflow-hidden">
                        <span class="text-muted d-block small fw-bold text-truncate"><i class="bi bi-journals me-1"></i> Títulos Previos</span>
                        <span class="fs-6 text-dark fw-bold d-block mt-1 text-truncate">
                            {{ $persona->formacionAcademica->count() }} Registrado(s)
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer bg-light py-2 text-muted small d-flex justify-content-between">
            <span>Perfil registrado en el sistema.</span>
        </div>
    </div>

    <!-- ESQUELETO DEL MEGA-CRUD (SISTEMA DE PESTAÑAS) -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">
                Secciones del Expediente
            </h4>
        </div>

        <div class="card-header bg-light pt-2 pb-0 border-top border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="expedienteTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="telefonos-tab" data-bs-toggle="tab" data-bs-target="#telefonos" type="button" role="tab">
                        Teléfonos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="academico-tab" data-bs-toggle="tab" data-bs-target="#academico" type="button" role="tab">
                        Titulo a Optar
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="formacion-tab" data-bs-toggle="tab" data-bs-target="#formacion" type="button" role="tab">
                        Titulaciones Previas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="inscripciones-tab" data-bs-toggle="tab" data-bs-target="#inscripciones" type="button" role="tab">
                        Sección
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="laboral-tab" data-bs-toggle="tab" data-bs-target="#laboral" type="button" role="tab">
                        Empresa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="observaciones-tab" data-bs-toggle="tab" data-bs-target="#observaciones" type="button" role="tab">
                        Observación
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body bg-white py-4">
            <div class="tab-content" id="expedienteTabsContent">
                <div class="tab-pane fade show active" id="telefonos" role="tabpanel">
                    @include('personas.partials.tab_telefonos')
                </div>
                <div class="tab-pane fade" id="academico" role="tabpanel">
                    @include('personas.partials.tab_titulacion')
                </div>
                <div class="tab-pane fade" id="formacion" role="tabpanel">
                    @include('personas.partials.tab_formacion')
                </div>
                <div class="tab-pane fade" id="inscripciones" role="tabpanel">
                    @include('personas.partials.tab_inscripciones')
                </div>
                <div class="tab-pane fade" id="laboral" role="tabpanel">
                    @include('personas.partials.tab_empresas')
                </div>
                <div class="tab-pane fade" id="observaciones" role="tabpanel">
                    @include('personas.partials.tab_observaciones')
                </div>
            </div>
        </div>

        <div class="card-footer bg-light py-2 text-muted small">
            Gestión de módulos del expediente en tiempo real.
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
        padding: 0.5rem 1rem;
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

    .tab-content .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        background-color: #ffffff !important;
    }

    .tab-content .card .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }

    .tab-content .card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var activeTab = localStorage.getItem('activeTabPersona');
        if (activeTab) {
            var triggerEl = document.querySelector('#expedienteTabs button[data-bs-target="' + activeTab + '"]');
            if (triggerEl) {
                var tabInstance = new bootstrap.Tab(triggerEl);
                tabInstance.show();
            }
        }

        document.querySelectorAll('#expedienteTabs button[data-bs-toggle="tab"]').forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                localStorage.setItem('activeTabPersona', e.target.getAttribute('data-bs-target'));
            });
        });
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endpush