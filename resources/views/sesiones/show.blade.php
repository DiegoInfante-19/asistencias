@extends('layouts.admin')

@section('header')
<x-page-header title="Toma de Asistencia">
    <li class="breadcrumb-item"><a href="{{ route('clases.secciones.sesiones', $sesion->id_seccion) }}">Sesiones de la Sección</a></li>
    <li class="breadcrumb-item active" aria-current="page">Lista de Alumnos</li>
</x-page-header>
@endsection

@section('styles')
<style>
    @media (max-width: 767.98px) {
        .content {
            margin: 5px !important;
            padding: 0 !important;
        }

        .card {
            border-radius: 0.5rem !important;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    @php
    $tieneAsistencia = count($asistenciasRegistradas) > 0;
    @endphp

    <!-- ENCABEZADO Y DATOS DE LA CLASE (Diseño Unificado) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-easel2-fill me-2 text-primary"></i>Sección: {{ $sesion->seccion->nombre_seccion }}
            </h4>
            <p class="text-muted small mb-0">
                <strong>PNF:</strong> {{ $sesion->seccion->pnf->nombre_pnf ?? 'N/D' }} |
                <strong>Cohorte Ref.:</strong> {{ $sesion->seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }} |
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
            </p>

            <div class="d-flex align-items-center mt-2">
                <span class="text-dark small me-2">
                    <strong><i class="bi bi-info-circle text-info"></i> Tema:</strong>
                    {{ $sesion->observacion_sesion ?: 'Sin tema registrado' }}
                </span>
                @can('update', $sesion)
                <button class="btn btn-sm btn-outline-secondary py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalObservacion" title="Editar Observación">
                    <i class="bi bi-pencil"></i>
                </button>
                @endcan
            </div>
        </div>

        <div class="d-flex flex-column align-items-md-end gap-2 text-md-end">
            <!-- Badges de Estado -->
            <div class="mb-2 mb-md-0 d-flex flex-column align-items-md-end">
                @if(!$tieneAsistencia)
                <span class="badge bg-danger shadow-sm p-2 fs-6"><i class="bi bi-exclamation-octagon me-1"></i> Asistencia Pendiente</span>
                @elseif($puedeEditar)
                <span class="badge bg-success shadow-sm p-2 fs-6 mb-1"><i class="bi bi-check-circle-fill me-1"></i> Asistencia Registrada</span>
                <span class="text-muted small fw-bold"><i class="bi bi-clock-history text-warning"></i> {{ $sesion->horasRestantesEdicion() }}h para editar</span>
                @else
                <span class="badge bg-secondary shadow-sm p-2 fs-6"><i class="bi bi-lock-fill me-1"></i> Registro Cerrado</span>
                @endif
            </div>

            <!-- Botón de Volver Integrado -->
            <div class="d-flex gap-2">
                <a href="{{ route('clases.secciones.sesiones', $sesion->id_seccion) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Directorio
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL (ESCRITORIO / MÓVIL) -->
    <div class="card border-0 shadow-sm">

        <!-- CABECERA DE LA TABLA Y CONTROLES ESCRITORIO -->
        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
            <h5 class="card-title text-dark mb-0 fs-5 fw-bold">
                Lista de Estudiantes
                <span class="badge bg-light text-primary border ms-2 fs-6">{{ $inscripciones->count() }} Inscritos</span>
            </h5>

            <div class="ms-auto d-none d-md-flex gap-2 controles-asistencia">
                @can('update', $sesion)
                @if($tieneAsistencia)
                <button type="button" class="btn btn-warning btn-editar-asistencia fw-bold text-dark shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Actualizar Asistencia
                </button>
                <button type="button" class="btn btn-secondary btn-cancelar-edicion fw-bold shadow-sm d-none">
                    Cancelar
                </button>
                <button type="button" class="btn btn-success btn-procesar-asistencia fw-bold shadow-sm d-none">
                    <i class="bi bi-check-circle-fill me-1"></i> Confirmar Actualización
                </button>
                @else
                <button type="button" class="btn btn-primary btn-procesar-asistencia fw-bold shadow-sm">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Guardar Asistencia
                </button>
                @endif
                @endcan
            </div>
        </div>

        <div class="card-body bg-white p-2 p-md-4">
            <form id="formulario-asistencia">

                <!-- MODO COMPUTADORA -->
                <div class="table-responsive d-none d-md-block">
                    {!! $dataTable->html()->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
                </div>

                <!-- MODO TELÉFONO -->
                <div class="d-block d-md-none">
                    @forelse($inscripciones as $index => $inscripcion)
                    @php
                        $estadoRaw = $asistenciasRegistradas[$inscripcion->id_inscripcion_seccion] ?? 'presente';
                        // Extraemos el string si es un Enum, de lo contrario lo pasamos a minúscula
                        $estadoNormalizado = $estadoRaw instanceof \App\Enums\EstadoAsistencia ? $estadoRaw->value : strtolower($estadoRaw);

                        $badgeClass = match($estadoNormalizado) {
                        'presente' => 'bg-success',
                        'ausente' => 'bg-danger',
                        'justificada', 'justificado' => 'bg-warning text-dark',
                        'tarde' => 'bg-info text-dark',
                        default => 'bg-secondary'
                        };
                    @endphp

                    <div class="card mb-3 border shadow-sm fila-estudiante" data-inscripcion="{{ $inscripcion->id_inscripcion_seccion }}" data-estado-original="{{ $estadoNormalizado }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-secondary mb-1">#{{ $index + 1 }}</span>
                                    <span class="fw-bold text-dark ms-1">V-{{ $inscripcion->persona->cedula_personas ?? 'S/C' }}</span>
                                </div>
                                <span class="badge bg-light text-dark border">{{ $inscripcion->persona->cohorte->numero_cohorte ?? 'N/D' }}</span>
                            </div>

                            <h6 class="fw-medium text-primary mb-3" style="font-size: 0.95rem;">
                                {{ $inscripcion->persona->nombre_completo ?? 'N/D' }}
                            </h6>

                            <!-- MODO LECTURA MÓVIL -->
                            <div class="modo-lectura w-100 text-center {{ !$puedeEditar || $tieneAsistencia ? '' : 'd-none' }}">
                                <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 w-100">{{ ucfirst($estadoNormalizado) }}</span>
                            </div>

                            <!-- MODO EDICIÓN MÓVIL -->
                            <div class="modo-edicion w-100 {{ !$puedeEditar || $tieneAsistencia ? 'd-none' : '' }}">
                                <div class="btn-group w-100 shadow-sm btn-group-asistencia" role="group">
                                    <input type="radio" class="btn-check btn-estado" name="estado_movil_{{ $inscripcion->id_inscripcion_seccion }}"
                                        id="m_presente_{{ $inscripcion->id_inscripcion_seccion }}" value="presente"
                                        {{ $estadoNormalizado == 'presente' ? 'checked' : '' }} autocomplete="off" {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <label class="btn btn-outline-secondary btn-asistencia-presente text-center" for="m_presente_{{ $inscripcion->id_inscripcion_seccion }}">
                                        Presente
                                    </label>

                                    <input type="radio" class="btn-check btn-estado" name="estado_movil_{{ $inscripcion->id_inscripcion_seccion }}"
                                        id="m_ausente_{{ $inscripcion->id_inscripcion_seccion }}" value="ausente"
                                        {{ $estadoNormalizado == 'ausente' ? 'checked' : '' }} autocomplete="off" {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <label class="btn btn-outline-secondary btn-asistencia-ausente text-center" for="m_ausente_{{ $inscripcion->id_inscripcion_seccion }}">
                                        Ausente
                                    </label>

                                    <input type="radio" class="btn-check btn-estado" name="estado_movil_{{ $inscripcion->id_inscripcion_seccion }}"
                                        id="m_justificada_{{ $inscripcion->id_inscripcion_seccion }}" value="justificada"
                                        {{ $estadoNormalizado == 'justificada' ? 'checked' : '' }} autocomplete="off" {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <label class="btn btn-outline-secondary btn-asistencia-justificado text-center" for="m_justificada_{{ $inscripcion->id_inscripcion_seccion }}">
                                        Justif.
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <p class="mb-0">No hay estudiantes inscritos en esta sección.</p>
                    </div>
                    @endforelse
                </div>
            </form>
        </div>

        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" id="id_sesiones" value="{{ $sesion->id_sesiones }}">

        <!-- FOOTER MODO TELÉFONO -->
        <div class="card-footer bg-white py-3 border-top d-block d-md-none">
            <div class="d-flex flex-column gap-2 controles-asistencia">
                @can('update', $sesion)
                @if($tieneAsistencia)
                <button type="button" class="btn btn-warning btn-editar-asistencia fw-bold text-dark shadow-sm btn-lg w-100">
                    <i class="bi bi-pencil-square me-1"></i> Actualizar Asistencia
                </button>
                <button type="button" class="btn btn-secondary btn-cancelar-edicion fw-bold shadow-sm btn-lg w-100 d-none">
                    Cancelar Edición
                </button>
                <button type="button" class="btn btn-success btn-procesar-asistencia fw-bold shadow-sm btn-lg w-100 d-none">
                    <i class="bi bi-check-circle-fill me-1"></i> Confirmar
                </button>
                @else
                <button type="button" class="btn btn-primary btn-procesar-asistencia btn-lg fw-bold shadow w-100">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Asistencia
                </button>
                @endif
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA EDITAR OBSERVACIÓN -->
<div class="modal fade" id="modalObservacion" tabindex="-1" aria-labelledby="modalObservacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-dark" id="modalObservacionLabel"><i class="bi bi-pencil text-primary me-2"></i>Editar Tema / Observación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Ajusta esta ruta a tu controlador real de actualización -->
            <form action="{{ route('sesiones.update', $sesion->id_sesiones) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label for="observacion_sesion" class="form-label text-muted small fw-bold">Descripción breve del tema impartido</label>
                        <textarea class="form-control" id="observacion_sesion" name="observacion_sesion" rows="3" placeholder="Ej: Introducción a bases de datos...">{{ $sesion->observacion_sesion }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary shadow-sm"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->html()->scripts(null, ['type' => 'module']) !!}

<script type="module">
    $(document).ready(function() {

        // 1. MANEJO DE ESTADOS LECTURA/EDICIÓN
        $('.btn-editar-asistencia').on('click', function() {
            // Mostrar los radios, ocultar los badges
            $('.modo-lectura').addClass('d-none');
            $('.modo-edicion').removeClass('d-none').hide().fadeIn(300);

            // Alternar botones
            $('.btn-editar-asistencia').addClass('d-none');
            $('.btn-cancelar-edicion').removeClass('d-none');
            $('.btn-procesar-asistencia').removeClass('d-none');
        });

        $('.btn-cancelar-edicion').on('click', function() {
            // Revertir a modo lectura
            $('.modo-edicion').addClass('d-none');
            $('.modo-lectura').removeClass('d-none').hide().fadeIn(300);

            // Alternar botones
            $('.btn-cancelar-edicion').addClass('d-none');
            $('.btn-procesar-asistencia').addClass('d-none');
            $('.btn-editar-asistencia').removeClass('d-none');

            // Resetear los radio buttons al estado original
            $('.fila-estudiante').each(function() {
                let estadoOriginal = $(this).data('estado-original');
                if (estadoOriginal) {
                    $(this).find('input[type="radio"][value="' + estadoOriginal + '"]').prop('checked', true);
                }
            });
        });

        // 2. LÓGICA DE GUARDADO DE ASISTENCIA
        $(document).on('click', '.btn-procesar-asistencia', function() {
            const botonesProcesar = document.querySelectorAll('.btn-procesar-asistencia');

            botonesProcesar.forEach(function(b) {
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';
            });

            let idSesiones = document.getElementById('id_sesiones').value;
            let token = document.getElementById('csrf_token').value;
            let asistenciasMap = {};

            // Mapeamos los datos. Al usar un objeto Map con la cédula/ID, prevenimos registros duplicados
            // entre la tabla de Desktop y las tarjetas de Mobile si ambas conviven en el DOM.
            let filas = document.querySelectorAll('.fila-estudiante');
            filas.forEach(function(fila) {
                let idInscripcion = fila.getAttribute('data-inscripcion');
                let radioSeleccionado = fila.querySelector('input[type="radio"]:checked');
                let estadoSeleccionado = radioSeleccionado ? radioSeleccionado.value : 'presente';

                asistenciasMap[idInscripcion] = {
                    id_inscripcion_seccion: idInscripcion,
                    estado: estadoSeleccionado
                };
            });

            let payload = {
                id_sesiones: idSesiones,
                asistencias: Object.values(asistenciasMap)
            };

            fetch("{{ route('asistencias.guardar_lote') }}", {
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
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Asistencia Guardada!',
                                text: 'El registro se actualizó correctamente.',
                                confirmButtonText: 'Entendido',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Recargamos para ver los Badges actualizados
                                    window.location.reload();
                                }
                            });
                        } else {
                            alert('¡Asistencia Guardada correctamente!');
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Error desconocido del servidor');
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al guardar la asistencia: ' + error.message,
                        });
                    } else {
                        alert('Ocurrió un error al guardar: ' + error.message);
                    }

                    botonesProcesar.forEach(function(b) {
                        b.disabled = false;
                        b.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Intentar de nuevo';
                    });
                });
        });
    });
</script>
@endpush