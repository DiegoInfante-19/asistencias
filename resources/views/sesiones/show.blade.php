@extends('layouts.app')

@section('content')
<!-- Encabezado de la página -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Toma de Asistencia
    </h2>
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="hover:text-primary" href="{{ route('sesiones.index') }}">Mis Clases /</a></li>
            <li class="text-primary font-medium">Lista de Alumnos</li>
        </ol>
    </nav>
</div>

@php
$puedeEditar = auth()->user()->can('update', $sesion);
@endphp

@unless($puedeEditar)
<div class="mb-6 flex w-full rounded-md border-l-6 border-warning bg-warning bg-opacity-10 px-7 py-4 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30">
    <div class="w-full">
        <h5 class="text-lg font-bold text-[#9D5425] mb-1">Modo Solo Lectura</h5>
        <p class="text-[#D0915C] text-sm">
            El tiempo límite de 48 horas para modificar esta asistencia ha expirado o no posee privilegios de edición sobre esta sesión. Los registros se muestran únicamente con fines de auditoría.
        </p>
    </div>
</div>
@endunless

<!-- TARJETA DE INFORMACIÓN DE LA CLASE -->
<div class="mb-6 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark border-t-4 border-t-primary">
    <div class="p-6.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h4 class="text-xl font-bold text-black dark:text-white mb-1">
                {{ $sesion->grupo->pnf->nombre_pnf }}
            </h4>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                <strong class="text-black dark:text-white">Cohorte:</strong> {{ $sesion->grupo->cohorte->numero_cohorte }} |
                <strong class="text-black dark:text-white">Nivel:</strong> {{ $sesion->grupo->nivel_academico }} |
                <strong class="text-black dark:text-white">Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
            </p>
            @if($sesion->observacion_sesion)
            <div class="mt-3 rounded bg-gray-100 py-2 px-4 text-sm text-black dark:bg-meta-4 dark:text-white">
                <strong>Tema/Observación:</strong> {{ $sesion->observacion_sesion }}
            </div>
            @endif
        </div>
        <div class="rounded border border-stroke p-3 text-center dark:border-strokedark shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Inscritos</p>
            <p class="text-2xl font-bold text-primary">{{ $inscripciones->count() }}</p>
        </div>
    </div>
</div>

<!-- TABLA INTERACTIVA DE ASISTENCIA -->
<div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
    <div class="border-b border-stroke py-4 px-6.5 bg-black dark:bg-meta-4">
        <h3 class="font-bold text-white flex items-center gap-2">
            Lista de Estudiantes
        </h3>
    </div>

    @if($inscripciones->isEmpty())
    <div class="p-10 text-center">
        <p class="text-gray-500 font-medium text-lg">No hay estudiantes inscritos</p>
        <p class="text-sm text-gray-400 mt-1">Contacte a Control de Estudios para que asigne estudiantes a este grupo.</p>
    </div>
    @else
    <form id="formulario-asistencia">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th class="py-4 px-4 font-medium text-black dark:text-white w-32">Cédula</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white">Apellidos y Nombres</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center w-96">Estado de Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $inscripcion)
                    @php
                    $estadoActual = $asistenciasRegistradas[$inscripcion->id_inscripcion_cohortes] ?? 'Presente';
                    @endphp

                    <tr class="fila-estudiante border-b border-[#eee] dark:border-strokedark" data-inscripcion="{{ $inscripcion->id_inscripcion_cohortes }}">
                        <td class="py-4 px-4">
                            <p class="font-medium text-gray-500">V-{{ $inscripcion->persona->cedula_personas ?? 'S/C' }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-black dark:text-white">
                                {{ $inscripcion->persona->primer_apellido_personas ?? '' }} {{ $inscripcion->persona->segundo_apellido_personas ?? '' }} {{ $inscripcion->persona->primer_nombre_personas ?? '' }} {{ $inscripcion->persona->segundo_nombre_personas ?? '' }}
                            </p>
                        </td>
                        <td class="py-4 px-4">
                            <!-- BOTONES DE RADIO ESTILIZADOS CON TAILWIND 'PEER' -->
                            <div class="flex items-center justify-center gap-2 w-full">
                                
                                <!-- Presente -->
                                <label class="cursor-pointer w-full text-center">
                                    <input type="radio" class="peer sr-only" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}" value="Presente" 
                                        {{ $estadoActual == 'Presente' ? 'checked' : '' }} {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <div class="rounded border border-success py-2 px-3 text-sm font-medium text-success transition peer-checked:bg-success peer-checked:text-white hover:bg-success hover:bg-opacity-10 {{ !$puedeEditar ? 'opacity-60 cursor-not-allowed' : '' }}">
                                        Presente
                                    </div>
                                </label>

                                <!-- Ausente -->
                                <label class="cursor-pointer w-full text-center">
                                    <input type="radio" class="peer sr-only" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}" value="Ausente" 
                                        {{ $estadoActual == 'Ausente' ? 'checked' : '' }} {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <div class="rounded border border-danger py-2 px-3 text-sm font-medium text-danger transition peer-checked:bg-danger peer-checked:text-white hover:bg-danger hover:bg-opacity-10 {{ !$puedeEditar ? 'opacity-60 cursor-not-allowed' : '' }}">
                                        Ausente
                                    </div>
                                </label>

                                <!-- Justificado -->
                                <label class="cursor-pointer w-full text-center">
                                    <input type="radio" class="peer sr-only" name="estado_{{ $inscripcion->id_inscripcion_cohortes }}" value="Justificado" 
                                        {{ $estadoActual == 'Justificado' ? 'checked' : '' }} {{ !$puedeEditar ? 'disabled' : '' }}>
                                    <div class="rounded border border-warning py-2 px-3 text-sm font-medium text-warning transition peer-checked:bg-warning peer-checked:text-white hover:bg-warning hover:bg-opacity-10 {{ !$puedeEditar ? 'opacity-60 cursor-not-allowed' : '' }}">
                                        Justificado
                                    </div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    @endif

    @if($inscripciones->isNotEmpty())
    <div class="border-t border-stroke py-4 px-6.5 flex justify-end gap-4 dark:border-strokedark bg-gray-2 dark:bg-meta-4">
        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" id="id_sesiones" value="{{ $sesion->id_sesiones }}">

        @can('update', $sesion)
        <button type="button" id="btn-procesar-asistencia" class="inline-flex items-center justify-center gap-2.5 rounded bg-primary py-3 px-8 text-center font-medium text-white hover:bg-opacity-90 shadow-1">
            <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
            Guardar Asistencia
        </button>
        @else
        <a href="{{ route('sesiones.index') }}" class="inline-flex items-center justify-center rounded border border-stroke py-3 px-8 text-center font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white">
            Volver al Listado
        </a>
        @endcan
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Fetch y Lógica JS (Intacto) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnProcesar = document.getElementById('btn-procesar-asistencia');
        if (btnProcesar) {
            btnProcesar.addEventListener('click', function() {
                btnProcesar.disabled = true;
                btnProcesar.innerHTML = 'Procesando...';
                let idSesiones = document.getElementById('id_sesiones').value;
                let token = document.getElementById('csrf_token').value;
                let arregloAsistencias = [];
                let filas = document.querySelectorAll('.fila-estudiante');
                filas.forEach(function(fila) {
                    let idInscripcion = fila.getAttribute('data-inscripcion');
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
                        btnProcesar.innerHTML = 'Guardar Asistencia';
                    });
            });
        }
    });
</script>
@endsection