<div class="btn-group" role="group" aria-label="Acciones de sesión">
    @php
        use Carbon\Carbon;

        $esEncargado = auth()->user()->isAdmin() || auth()->user()->isCoordinador();
        $authProfesorId = auth()->user()->profesor?->id_profesor;
        $puedeEditar = $esEncargado || ($authProfesorId === $sesion->id_profesor);
        
        // Verificamos si la sesión ya cuenta con registros de asistencia
        $tieneAsistencia = $sesion->asistencias()->count() > 0;

        // Limpiamos y formateamos la fecha estricta Y-m-d para Flatpickr
        $fechaLimpia = $sesion->fecha_sesion ? Carbon::parse($sesion->fecha_sesion)->format('Y-m-d') : '';

        // Preparamos el objeto JSON seguro
        $sesionJson = json_encode([
            'id_sesiones' => $sesion->id_sesiones,
            'id_profesor' => $sesion->id_profesor,
            'fecha_sesion' => $fechaLimpia,
            'observacion_sesion' => $sesion->observacion_sesion,
            'tiene_asistencia' => $tieneAsistencia
        ]);
    @endphp

    {{-- Botón Registrar / Tomar Asistencia --}}
    <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-outline-secondary" title="Tomar / Ver Asistencia">
        <i class="bi bi-eye"></i>
    </a>

    {{-- Botón Editar Sesión (Pasamos el JSON codificado con htmlspecialchars para evitar errores de comillas) --}}
    @if($puedeEditar)
        <button type="button" class="btn btn-outline-secondary" title="Editar Sesión" onclick='abrirModalEditar({!! json_encode($sesionJson) !!})'>
            <i class="bi bi-pencil"></i>
        </button>
    @else
        <button type="button" class="btn btn-outline-secondary" title="Editar Bloqueado" disabled>
            <i class="bi bi-pencil text-muted"></i>
        </button>
    @endif

    {{-- Botón de anulación / eliminación --}}
    @if($esEncargado)
        <button type="submit" form="form-delete-sesion-{{ $sesion->id_sesiones }}" class="btn btn-outline-secondary" title="Anular Sesión">
            <i class="bi bi-trash"></i>
        </button>
    @endif
</div>

@if($esEncargado)
    <form id="form-delete-sesion-{{ $sesion->id_sesiones }}" action="{{ route('sesiones.destroy', $sesion->id_sesiones) }}" method="POST" class="d-none" onsubmit="return confirm('¿Está seguro de anular esta sesión?')">
        @csrf
        @method('DELETE')
    </form>
@endif