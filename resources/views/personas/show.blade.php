@extends('layouts.admin')

@push('styles')
<!-- Select2 y Tema Bootstrap 5 centralizados por Vite (Sin CDNs) -->
@endpush

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
                            <span class="fs-5">{{ $persona->cedula_personas }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Nombres</span>
                            <span class="fs-5">{{ $persona->primer_nombre_personas }} {{ $persona->segundo_nombre_personas }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Apellidos</span>
                            <span class="fs-5">{{ $persona->primer_apellido_personas }} {{ $persona->segundo_apellido_personas }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Fecha de Nacimiento</span>
                            <span>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento_personas)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Correo Electrónico</span>
                            <span>{{ $persona->email_personas ?? 'No registrado' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Lugar de Nacimiento</span>
                            <span class="fs-6 text-dark fw-semibold">
                                <i class="bi bi-geo-alt-fill text-secondary me-1"></i>
                                {{ $persona->lugarNacimiento ? $persona->lugarNacimiento->direccion_completa : 'No registrado' }}
                            </span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block small fw-bold">Detalles de Dirección</span>
                            <span class="fs-6">{{ $persona->lugarNacimiento->detalles_adicionales ?? 'Sin detalles adicionales registrados' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        <!-- Contenido de las pestañas -->
        <div class="card-body bg-light border-top">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Asegurarnos de que jQuery y Bootstrap estén listos
        if (typeof window.$ !== 'undefined') {
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
        }
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endpush