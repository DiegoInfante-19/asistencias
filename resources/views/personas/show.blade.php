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
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title text-dark mb-0 fw-bold fs-6">
                <i class="bi bi-info-circle-fill text-primary me-1"></i> Información General del Estudiante
            </h5>
        </div>
        <div class="card-body bg-white py-4">
            <div class="row align-items-center">
                <!-- Columna de Avatar/Icono -->
                <div class="col-md-2 d-flex justify-content-center align-items-center border-end">
                    <i class="bi bi-person-bounding-box text-secondary" style="font-size: 5rem;"></i>
                </div>

                <!-- Columna de Información -->
                <div class="col-md-10">
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

                        <!-- BLOQUE: Fecha, Correo y Cohorte en 3 columnas -->
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Fecha de Nacimiento</span>
                            <span class="text-dark">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento_personas)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Correo Electrónico</span>
                            <span class="text-dark">{{ $persona->email_personas ?? 'No registrado' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold text-primary"><i class="bi bi-bookmark-star-fill me-1"></i>Sello de Ingreso (Cohorte)</span>
                            <span class="fs-6 fw-bold text-dark">{{ $persona->cohorte->numero_cohorte ?? 'No asignada' }}</span>
                        </div>

                        <!-- LUGAR NACIMIENTO -->
                        <div class="col-md-4">
                            <span class="text-muted d-block small fw-bold">Lugar de Nacimiento</span>
                            <span class="fs-6 text-dark fw-semibold">
                                <i class="bi bi-geo-alt-fill text-secondary me-1"></i>
                                {{ $persona->lugarNacimiento ? $persona->lugarNacimiento->direccion_completa : 'No registrado' }}
                            </span>
                        </div>
                        <div class="col-8">
                            <span class="text-muted d-block small fw-bold">Detalles de Dirección</span>
                            <span class="fs-6 text-dark">{{ $persona->lugarNacimiento->detalles_adicionales ?? 'Sin detalles adicionales registrados' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer gris para la tarjeta de perfil -->
        <div class="card-footer bg-light py-2 text-muted small">
            Perfil registrado en el sistema.
        </div>
    </div>

    <!-- ESQUELETO DEL MEGA-CRUD (SISTEMA DE PESTAÑAS) -->
    <div class="card shadow-sm">
        <!-- Header de la tarjeta contenedora de pestañas -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fw-bold fs-6 me-auto">
                <i class="bi bi-folder2-open text-primary me-1"></i> Secciones del Expediente
            </h5>
        </div>

        <!-- Navegación de las pestañas corregida -->
        <div class="card-header bg-light pt-2 pb-0 border-top border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="expedienteTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="telefonos-tab" data-bs-toggle="tab" data-bs-target="#telefonos" type="button" role="tab">
                        <i class="bi bi-telephone-fill me-1 text-primary"></i> Teléfonos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="academico-tab" data-bs-toggle="tab" data-bs-target="#academico" type="button" role="tab">
                        <i class="bi bi-mortarboard-fill me-1 text-primary"></i> Exp. Académico
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="formacion-tab" data-bs-toggle="tab" data-bs-target="#formacion" type="button" role="tab">
                        <i class="bi bi-award-fill me-1 text-primary"></i> Formación Previa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="inscripciones-tab" data-bs-toggle="tab" data-bs-target="#inscripciones" type="button" role="tab">
                        <i class="bi bi-calendar-check-fill me-1 text-primary"></i> Control Estudio
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="laboral-tab" data-bs-toggle="tab" data-bs-target="#laboral" type="button" role="tab">
                        <i class="bi bi-building me-1 text-primary"></i> Perfil Laboral
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="observaciones-tab" data-bs-toggle="tab" data-bs-target="#observaciones" type="button" role="tab">
                        <i class="bi bi-journal-text me-1 text-primary"></i> Observaciones
                    </button>
                </li>
            </ul>
        </div>

        <!-- Contenido de las pestañas -->
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

        <!-- Footer gris para la tarjeta contenedora de pestañas -->
        <div class="card-footer bg-light py-2 text-muted small">
            Gestión de módulos del expediente en tiempo real.
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estética limpia para las pestañas y corrección del solapamiento */
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

    /* Estilo para blindar y diferenciar visualmente las tarjetas anidadas dentro de los partials/tabs */
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