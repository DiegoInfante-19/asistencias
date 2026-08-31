@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- ENCABEZADO DE LA SECCIÓN -->
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">Sección: {{ $seccion->nombre_seccion }}</h3>
                <p class="mb-0 text-white-50">
                    PNF: <strong>{{ $seccion->pnf->nombre_pnf ?? 'N/D' }}</strong> | 
                    Período: <strong>Cohorte Ref. {{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }}</strong>
                </p>
            </div>
            <a href="{{ route('secciones.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- SISTEMA DE PESTAÑAS (TABS) -->
    <ul class="nav nav-tabs mb-4" id="seccionTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-dark" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes-pane" type="button" role="tab">
                <i class="bi bi-people-fill me-1 text-primary"></i> Estudiantes Inscritos ({{ $seccion->inscripciones->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-dark" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial-pane" type="button" role="tab">
                <i class="bi bi-calendar-check-fill me-1 text-success"></i> Historial y Asistencias Dictadas ({{ $seccion->sesiones->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="seccionTabContent">
        
        <!-- PESTAÑA 1: CRUD INTERNO DE ESTUDIANTES -->
        <div class="tab-pane fade show active" id="estudiantes-pane" role="tabpanel">
            <div class="row g-4">
                <!-- Columna Izquierda: Matrícula -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-plus me-2 text-success"></i>Matricular Estudiante</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('secciones.inscribir', $seccion->id_seccion) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Seleccionar Estudiante (Mismo PNF)</label>
                                    <select name="id_personas" class="form-select select2" required>
                                        <option value="" selected disabled>Busque por cédula o nombre...</option>
                                        @foreach($estudiantesDisponibles as $estudiante)
                                            <option value="{{ $estudiante->id_personas }}">
                                                {{ $estudiante->cedula_personas }} - {{ $estudiante->nombre_completo }} (Cohorte {{ $estudiante->cohorte->numero_cohorte ?? 'Externa' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small text-muted mt-1">El sistema filtra únicamente estudiantes del PNF correspondiente.</div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Inscribir en Sección
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Nómina Activa -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people me-2 text-primary"></i>Nómina Activa del Salón</h5>
                            <span class="badge bg-primary px-3 py-2">Total: {{ $seccion->inscripciones->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Cohorte (Origen)</th>
                                            <th>Empresa</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($seccion->inscripciones as $inscripcion)
                                            <tr>
                                                <td class="fw-bold">{{ $inscripcion->persona->cedula_personas }}</td>
                                                <td>{{ $inscripcion->persona->nombre_completo }}</td>
                                                <td><span class="badge bg-info text-dark">Cohorte {{ $inscripcion->persona->cohorte->numero_cohorte ?? 'N/D' }}</span></td>
                                                <td>{{ $inscripcion->persona->empresaPersona->empresa->nombre_empresa ?? 'Independiente' }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('secciones.retirar', [$seccion->id_seccion, $inscripcion->id_inscripcion_seccion]) }}" method="POST" onsubmit="return confirm('¿Retirar a este estudiante de la sección?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Retirar de sección">
                                                            <i class="bi bi-person-dash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">No hay estudiantes matriculados en esta sección todavía.</td>
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

        <!-- PESTAÑA 2: HISTORIAL Y MATRIZ DE ASISTENCIAS -->
        <div class="tab-pane fade" id="historial-pane" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-text me-2 text-success"></i>Auditoría Histórica de Clases y Asistencias</h5>
                    <small class="text-muted">Registro de solo lectura de todas las sesiones de los días miércoles impartidas en esta sección.</small>
                </div>
                <div class="card-body p-4">
                    @forelse($seccion->sesiones as $sesion)
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="fw-bold text-primary fs-5 me-3">
                                        <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }} (Miércoles)
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
                                                <th class="text-center" style="width: 150px;">Estado de Asistencia</th>
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
                                                        @elseif($asistencia->estado_asistencia === App\Enosp\EstadoAsistencia::Ausente)
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
                            <p class="small text-muted mb-0">A medida que los profesores pasen lista los miércoles, el historial aparecerá reflejado aquí automáticamente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection