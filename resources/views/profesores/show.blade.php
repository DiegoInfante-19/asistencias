@extends('layouts.admin')

@section('header')
<x-page-header title="Ficha de Profesor">
    <li class="breadcrumb-item"><a href="{{ route('profesores.index') }}">Profesores</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ficha</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- HEADER Y BOTONERA -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Detalles Administrativos</h4>
        <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>

    <div class="row">

        <!-- COLUMNA IZQUIERDA: DATOS BÁSICOS DEL USUARIO -->
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

        <!-- COLUMNA DERECHA: CONFIGURACIÓN DE PNF Y NIVEL -->
        @if($user->isProfesor())
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm border-top border-warning border-3 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-bookmark me-2"></i>Perfil Académico</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">

                    <div class="mb-4">
                        @if($user->profesor && $user->profesor->pnf && $user->profesor->nivel_asignado)
                        <div class="alert alert-info border-0 bg-light text-dark py-3">
                            <span class="small text-muted fw-bold d-block mb-1">ASIGNACIÓN ACTUAL:</span>
                            <span class="fs-5 text-primary fw-bold">{{ $user->profesor->pnf->nombre_pnf }}</span>
                            <span class="badge bg-secondary ms-2 fs-6">{{ $user->profesor->nivel_asignado }}</span>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-calendar-event me-1"></i> Asignado el: {{ \Carbon\Carbon::parse($user->profesor->fecha_asignacion_profesor)->format('d/m/Y') }}
                            </small>
                        </div>
                        @else
                        <div class="alert alert-warning border-0 py-3">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                            <span class="fw-semibold">Perfil incompleto.</span> Defina el PNF y el Nivel Académico para habilitar la asignación de grupos.
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('usuarios.asignar_pnf', $user->id_users) }}" method="POST" class="mt-auto">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-muted">PNF Asignado <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_pnf" required>
                                    <option value="" disabled {{ !$user->profesor ? 'selected' : '' }}>Seleccione...</option>
                                    @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnf }}" {{ ($user->profesor && $user->profesor->id_pnf == $pnf->id_pnf) ? 'selected' : '' }}>
                                        {{ $pnf->nombre_pnf }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-muted">Nivel <span class="text-danger">*</span></label>
                                <select class="form-select" name="nivel_asignado" required>
                                    <option value="" disabled {{ !$user->profesor || !$user->profesor->nivel_asignado ? 'selected' : '' }}>Seleccione...</option>
                                    <option value="TSU" {{ ($user->profesor && $user->profesor->nivel_asignado == 'TSU') ? 'selected' : '' }}>TSU</option>
                                    <option value="Ingeniería" {{ ($user->profesor && $user->profesor->nivel_asignado == 'Ingeniería') ? 'selected' : '' }}>Ingeniería</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold small text-muted">Fecha de Asignación <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_asignacion_profesor"
                                value="{{ $user->profesor ? $user->profesor->fecha_asignacion_profesor : date('Y-m-d') }}" required>
                        </div>

                        <button type="submit" class="btn btn-warning fw-bold text-dark w-100 py-2 shadow-sm">
                            <i class="bi bi-save me-1"></i> {{ ($user->profesor && $user->profesor->id_pnf && $user->profesor->nivel_asignado) ? 'Actualizar Perfil' : 'Registrar Perfil' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN INFERIOR: CARGA ACADÉMICA (EL ESLABÓN PERDIDO) -->
        @if($user->profesor && $user->profesor->id_pnf && $user->profesor->nivel_asignado)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-video3 me-2"></i>Carga Académica (Grupos Asignados)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Formulario para agregar un grupo -->
                        <div class="col-md-4 border-end pe-4">
                            <h6 class="fw-bold mb-3 text-secondary">Asignar Nuevo Grupo</h6>
                            <form action="{{ route('usuarios.asignar_grupo', $user->id_users) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Seleccione el Grupo disponible</label>
                                    <select class="form-select" name="id_grupo" required>
                                        <option value="" selected disabled>Seleccione un grupo...</option>
                                        @forelse($gruposDisponibles as $grupo)
                                            <!-- Validamos visualmente que no asigne el mismo grupo dos veces -->
                                            @if(!$user->profesor->grupos->contains('id_grupo', $grupo->id_grupo))
                                                <option value="{{ $grupo->id_grupo }}">
                                                    Cohorte: {{ $grupo->cohorte->numero_cohorte }}
                                                </option>
                                            @endif
                                        @empty
                                            <option value="" disabled>No hay grupos disponibles para este PNF/Nivel</option>
                                        @endforelse
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Añadir a la Carga
                                </button>
                            </form>
                        </div>

                        <!-- Lista de grupos asignados -->
                        <div class="col-md-8 ps-4">
                            <h6 class="fw-bold mb-3 text-secondary">Grupos Actuales del Docente</h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Cohorte</th>
                                            <th>PNF</th>
                                            <th>Nivel Académico</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->profesor->grupos as $grupoAsignado)
                                        <tr>
                                            <td class="fw-bold">{{ $grupoAsignado->cohorte->numero_cohorte }}</td>
                                            <td>{{ $user->profesor->pnf->nombre_pnf }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $grupoAsignado->nivel_academico }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('usuarios.remover_grupo', ['id_usuario' => $user->id_users, 'id_grupo' => $grupoAsignado->id_grupo]) }}" method="POST" class="d-inline form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Quitar este grupo" onclick="return confirm('¿Está seguro de remover este grupo de la carga académica del profesor?')">
                                                        <i class="bi bi-trash3-fill"></i> Quitar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-50"></i>
                                                El profesor no tiene grupos asignados aún.<br>
                                                <small>No podrá registrar asistencias hasta que le asigne al menos un grupo.</small>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

    </div>
</div>
@endsection