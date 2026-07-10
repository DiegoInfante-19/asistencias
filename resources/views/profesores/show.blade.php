@extends('layouts.admin')

@section('header')
<x-page-header title="Ficha de Profesor">
    <li class="breadcrumb-item"><a href="{{ route('profesores.index') }}">Profesores</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ficha</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Detalles Administrativos</h4>
        <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>

    <div class="row">

        <div class="col-md-{{ $user->isProfesor() ? '6' : '12' }} mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-person-badge me-2"></i>Datos de Identificación</h5>
                </div>
                <div class="card-body p-4">
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
                        <div class="col-sm-6">
                            <label class="form-label text-muted small fw-bold mb-0">Cédula de Identidad</label>
                            <p class="fs-5 text-dark fw-semibold mb-0">{{ $user->cedula_users }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small fw-bold mb-0">Nombre de Usuario</label>
                            <p class="fs-5 text-dark mb-0">{{ $user->username }}</p>
                        </div>
                        <div class="col-12">
                            <hr class="text-muted my-2">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small fw-bold mb-0">Correo Electrónico</label>
                            <p class="text-dark mb-0 text-break">{{ $user->email_users }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small fw-bold mb-0">Teléfono de Contacto</label>
                            <p class="text-dark mb-0">{{ $user->phone_users ?? 'No registrado' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($user->isProfesor())
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm border-top border-warning border-3 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-bookmark me-2"></i>Asignación Académica</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">

                    <div class="mb-4">
                        @if($user->profesor && $user->profesor->pnf)
                        <div class="alert alert-info border-0 bg-light text-dark py-3">
                            <span class="small text-muted fw-bold d-block mb-1">PNF VINCULADO ACTUALMENTE:</span>
                            <span class="fs-5 text-primary fw-bold">{{ $user->profesor->pnf->nombre_pnf }}</span>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-calendar-event me-1"></i> Asignado el: {{ \Carbon\Carbon::parse($user->profesor->fecha_assignacion_profesor)->format('d/m/Y') }}
                            </small>
                        </div>
                        @else
                        <div class="alert alert-warning border-0 py-3">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                            <span class="fw-semibold">Sin asignación activa.</span> Este profesor no podrá capturar asistencias en el sistema hasta que se vincule a un PNF.
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('usuarios.asignar_pnf', $user->id_users) }}" method="POST" class="mt-auto">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold small text-muted">Seleccione el PNF Asignado <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_pnf" required>
                                <option value="" disabled {{ !$user->profesor ? 'selected' : '' }}>Seleccione un programa...</option>
                                @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}"
                                    {{ ($user->profesor && $user->profesor->id_pnf == $pnf->id_pnf) ? 'selected' : '' }}>
                                    {{ $pnf->nombre_pnf }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold small text-muted">Fecha de Asignación <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_asignacion_profesor"
                                value="{{ $user->profesor ? $user->profesor->fecha_asignacion_profesor : date('Y-m-d') }}" required>
                        </div>

                        <button type="submit" class="btn btn-warning fw-bold text-dark w-100 py-2 shadow-sm">
                            <i class="bi bi-save me-1"></i> {{ $user->profesor ? 'Actualizar Asignación' : 'Registrar Asignación' }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection