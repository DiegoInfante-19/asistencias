@extends('layouts.admin')

@section('header')
<x-page-header title="Ficha de Profesor">
    <li class="breadcrumb-item"><a href="{{ route('profesores.index') }}">Profesores</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ficha</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- HEADER Y BOTONERA SUPERIOR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-person-badge me-2"></i> Detalles Administrativos del Docente
        </h3>
        <div>
            <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- COLUMNA IZQUIERDA: INFORMACIÓN GENERAL DEL DOCENTE -->
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title text-dark mb-0 fw-bold fs-6">
                        <i class="bi bi-person-vcard text-primary me-2"></i> Información General del Docente
                    </h5>
                </div>
                <div class="card-body bg-white py-4">
                    <div class="mb-3 d-flex gap-2">
                        <span class="badge {{ $user->status_users === 'Activo' ? 'bg-success' : 'bg-danger' }} px-3 py-2 fs-6">
                            {{ $user->status_users }}
                        </span>
                        <span class="badge bg-primary px-3 py-2 fs-6">
                            Rol: {{ $user->rol->nombre_rol ?? 'Sin Rol Asignado' }}
                        </span>
                    </div>

                    <h3 class="fw-bold text-dark mb-4">
                        {{ $user->name_users }} {{ $user->last_name_users }}
                    </h3>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-bold">Cédula de Identidad</span>
                            <span class="fs-5 text-dark fw-semibold">{{ $user->cedula_users }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-bold">Nombre de Usuario</span>
                            <span class="fs-5 text-dark">{{ $user->username }}</span>
                        </div>
                        
                        <div class="col-12">
                            <hr class="text-muted my-2" style="opacity: 0.15;">
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-bold">Correo Electrónico</span>
                            <span class="text-dark text-break">{{ $user->email_users }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-bold">Teléfono de Contacto</span>
                            <span class="text-dark">{{ $user->phone_users ?? 'No registrado' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 text-muted small">
                    <span>Datos de identificación del sistema de usuarios.</span>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: CONFIGURACIÓN DE PNF Y NIVEL -->
        @if($user->isProfesor())
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 fw-bold text-dark">
                    <i class="bi bi-journal-bookmark me-1 text-primary"></i> Configuración de Perfil Académico
                </div>
                <div class="card-body bg-white py-4 d-flex flex-column justify-content-between">
                    <div>
                        @if($user->profesor && $user->profesor->pnf && $user->profesor->nivel_asignado)
                        <div class="alert alert-info border-0 bg-light text-dark py-3 mb-4 shadow-sm">
                            <span class="small text-muted fw-bold d-block mb-1">ASIGNACIÓN ACTUAL:</span>
                            <span class="fs-5 text-primary fw-bold">{{ $user->profesor->pnf->nombre_pnf }}</span>
                            @php
                                $nivelActual = is_object($user->profesor->nivel_asignado) ? $user->profesor->nivel_asignado->value : $user->profesor->nivel_asignado;
                            @endphp
                            <span class="badge bg-secondary ms-2 fs-6">{{ $nivelActual }}</span>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-calendar-event me-1"></i> Asignado el: {{ \Carbon\Carbon::parse($user->profesor->fecha_asignacion_profesor)->format('d/m/Y') }}
                            </small>
                        </div>
                        @else
                        <div class="alert alert-warning border-0 py-3 mb-4 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                            <span class="fw-semibold">Perfil incompleto.</span> Defina el PNF y el Nivel Académico del docente.
                        </div>
                        @endif

                        <form action="{{ route('usuarios.asignar_pnf', $user->id_users) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">PNF Asignado <span class="text-danger">*</span></label>
                                <select class="form-select select2-buscador" name="id_pnf" required>
                                    <option value="" disabled {{ !$user->profesor ? 'selected' : '' }}>Seleccione...</option>
                                    @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnf }}" {{ ($user->profesor && $user->profesor->id_pnf == $pnf->id_pnf) ? 'selected' : '' }}>
                                        {{ $pnf->nombre_pnf }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nivel Académico <span class="text-danger">*</span></label>
                                <select class="form-select select2-buscador" name="nivel_asignado" required>
                                    <option value="" disabled {{ !isset($nivelActual) || !$nivelActual ? 'selected' : '' }}>Seleccione...</option>
                                    <option value="TSU" {{ (isset($nivelActual) && $nivelActual === 'TSU') ? 'selected' : '' }}>TSU</option>
                                    <option value="Ingeniería" {{ (isset($nivelActual) && $nivelActual === 'Ingeniería') ? 'selected' : '' }}>Ingeniería</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Fecha de Asignación <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_asignacion_profesor"
                                    value="{{ $user->profesor ? $user->profesor->fecha_asignacion_profesor : date('Y-m-d') }}" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning fw-bold text-dark py-2 shadow-sm">
                                    <i class="bi bi-save me-1"></i> {{ ($user->profesor && $user->profesor->id_pnf && isset($nivelActual) && $nivelActual) ? 'Actualizar Perfil' : 'Registrar Perfil' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 text-muted small">
                    Control de asignación académica del docente.
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estética unificada para las tarjetas y consistencia visual con el resto del sistema */
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        background-color: #ffffff !important;
    }
    .card .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #dee2e6 !important;
    }
    .card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
    }
</style>
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            placeholder: 'Seleccione una opción...'
        });
    });
</script>
@endpush