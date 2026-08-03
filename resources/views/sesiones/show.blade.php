@extends('layouts.admin')

@section('header')
<x-page-header title="Toma de Asistencia">
    <li class="breadcrumb-item"><a href="{{ route('sesiones.index') }}">Mis Clases</a></li>
    <li class="breadcrumb-item active" aria-current="page">Lista de Alumnos</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    {{-- Comprobación previa de autorización para la vista --}}
    @php
        $puedeEditar = auth()->user()->can('update', $sesion);
    @endphp

    @unless($puedeEditar)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="bi bi-lock-fill fs-4 me-3"></i>
        <div>
            <strong>Modo Solo Lectura:</strong> El tiempo límite de 48 horas para modificar esta asistencia ha expirado o no posee privilegios de edición sobre esta sesión. Los registros se muestran únicamente con fines de auditoría.
        </div>
    </div>
    @endunless

    <!-- TARJETA DE INFORMACIÓN DE LA CLASE -->
    <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-easel2-fill me-2 text-primary"></i>
                        {{ $sesion->grupo->pnf->nombre_pnf }}
                    </h4>
                    <p class="text-muted mb-0">
                        <strong>Cohorte:</strong> {{ $sesion->grupo->cohorte->numero_cohorte }} |
                        <strong>Nivel:</strong> {{ $sesion->grupo->nivel_academico }} |
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                    </p>
                    @if($sesion->observacion_sesion)
                    <div class="alert alert-light mt-3 mb-0 border-0 small text-dark">
                        <i class="bi bi-info-circle me-1 text-info"></i> <strong>Tema/Observación:</strong> {{ $sesion->observacion_sesion }}
                    </div>
                    @endif
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="badge bg-light text-dark p-3 border shadow-sm fs-6">
                        <i class="bi bi-people-fill text-secondary me-2"></i>
                        Total Inscritos: <span class="fw-bold text-primary">{{ $inscripciones->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA INTERACTIVA DE ASISTENCIA -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Lista de Estudiantes</h5>
        </div>

        <div class="card-body p-0">
            @if($inscripciones->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-person-x fs-1 d-block mb-3 opacity-50"></i>
                <h5 class="fw-bold">No hay estudiantes inscritos</h5>
                <p>Contacte a Control de Estudios para que asigne estudiantes a este grupo.</p>
            </div>
            @else
            <form id="formulario-asistencia">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Cédula</th>
                                <th>Apellidos y Nombres</th>
                                <th class="text-center" style="width: 350px;">Estado de Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inscripciones as $inscripcion)
                            @php
                                $estadoActual = $asistenciasRegistradas[$inscripcion->id_inscripcion_cohortes] ?? 'Presente';
                            @endphp

                            <tr class="fila-estudiante" data-inscripcion="{{ $inscripcion->id_inscripcion_cohortes }}">
                                <td class="ps-4 fw-semibold text-secondary">
                                    {{ $inscripcion->persona->nacionalidad ?? 'V' }}-{{ $inscripcion->persona->cedula }}
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ $inscripcion->persona->last_name_users ?? $inscripcion->persona->apellidos }} {{ $inscripcion->persona->name_users ?? $inscripcion->persona->nombres }}
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <!-- Presente -->
                                        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}"
                                               id="presente_{{ $inscripcion->id_inscripcion_cohortes }}" value="Presente"
                                               {{ $estadoActual == 'Presente' ? 'checked' : '' }} autocomplete="off"
                                               {{ !$puedeEditar ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-success fw-bold" for="presente_{{ $inscripcion->id_inscripcion_cohortes }}">Presente</label>

                                        <!-- Ausente -->
                                        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}"
                                               id="ausente_{{ $inscripcion->id_inscripcion_cohortes }}" value="Ausente"
                                               {{ $estadoActual == 'Ausente' ? 'checked' : '' }} autocomplete="off"
                                               {{ !$puedeEditar ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-danger fw-bold" for="ausente_{{ $inscripcion->id_inscripcion_cohortes }}">Ausente</label>

                                        <!-- Justificado -->
                                        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}"
                                               id="justificado_{{ $inscripcion->id_inscripcion_cohortes }}" value="Justificado"
                                               {{ $estadoActual == 'Justificado' ? 'checked' : '' }} autocomplete="off"
                                               {{ !$puedeEditar ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-warning fw-bold text-dark" for="justificado_{{ $inscripcion->id_inscripcion_cohortes }}">Justificado</label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            @endif
        </div>

        @if($inscripciones->isNotEmpty())
        <div class="card-footer bg-white py-4 border-top d-flex justify-content-end">
            <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
            <input type="hidden" id="id_sesiones" value="{{ $sesion->id_sesiones }}">

            {{-- BOTÓN DE GUARDADO CONDICIONADO POR POLICY --}}
            @can('update', $sesion)
            <button type="button" id="btn-procesar-asistencia" class="btn btn-primary btn-lg fw-bold px-5 shadow">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Asistencia
            </button>
            @else
            <a href="{{ route('sesiones.index') }}" class="btn btn-secondary btn-lg fw-bold px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Listado
            </a>
            @endcan
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const btnProcesar = document.getElementById('btn-procesar-asistencia');

        if (btnProcesar) {
            btnProcesar.addEventListener('click', function() {

                btnProcesar.disabled = true;
                btnProcesar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';

                let idSesiones = document.getElementById('id_sesiones').value;
                let token = document.getElementById('csrf_token').value;
                let arregloAsistencias = [];

                let filas = document.querySelectorAll('.fila-estudiante');

                filas.forEach(function(fila) {
                    let idInscripcion = fila.getAttribute('data-inscripcion');
                    // Obtenemos el radio seleccionado (incluso si está bloqueado, captura el valor actual)
                    let radioSeleccionado = fila.querySelector('input[type="radio"]:checked');
                    let estadoSeleccionado = radioSeleccionado ? radioSeleccionado.value : 'Presente';

                    arregloAsistencias.push({
                        id_inscripcion_cohortes: idInscripcion,
                        estado: estadoSeleccionado
                    });
                });

                let payload = {
                    id_sesiones: idSesiones,
                    asistencias: arregloAsistencias
                };

                fetch('/asistencias/guardar-lote', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Asistencia Guardada!',
                            text: 'El registro de asistencia se procesó correctamente.',
                            confirmButtonText: 'Volver a Mis Clases',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('sesiones.index') }}";
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Error desconocido del servidor');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ocurrió un error al guardar la asistencia: ' + error.message,
                    });
                    btnProcesar.disabled = false;
                    btnProcesar.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Asistencia';
                });
            });
        }
    });
</script>
@endsection