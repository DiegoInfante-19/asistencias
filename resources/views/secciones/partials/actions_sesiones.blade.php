<div class="btn-group" role="group" aria-label="Acciones de sesión">
    @php
        $esEncargado = auth()->user()->isAdmin() || auth()->user()->isCoordinador();
        $dentroDeGracia = \Carbon\Carbon::parse($sesion->fecha_sesion)->addHours(48)->isFuture();
        $authProfesorId = auth()->user()->profesor?->id_profesor;
        $puedeEditar = $esEncargado || ($authProfesorId === $sesion->id_profesor && $dentroDeGracia);
    @endphp

    {{-- Botón Editar / Ver Asistencia --}}
    @if($puedeEditar)
        <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-outline-secondary" title="Tomar / Editar Asistencia">
            <i class="bi bi-pencil"></i>
        </a>
    @else
        <a href="{{ route('sesiones.show', $sesion->id_sesiones) }}" class="btn btn-outline-secondary" title="Ver Asistencia (Bloqueado)">
            <i class="bi bi-eye text-warning"></i>
        </a>
    @endif

    {{-- Botón de anulación / eliminación exclusivo para el Encargado --}}
    @if($esEncargado)
        <button type="submit" form="form-delete-sesion-{{ $sesion->id_sesiones }}" class="btn btn-outline-secondary" title="Anular Sesión">
            <i class="bi bi-trash"></i>
        </button>
    @endif
</div>

@if($esEncargado)
    <form id="form-delete-sesion-{{ $sesion->id_sesiones }}" action="{{ route('sesiones.destroy', $sesion->id_sesiones) }}" method="POST" class="d-none" onsubmit="return confirm('¿Está seguro de anular esta sesión? No se tomará en cuenta para las estadísticas.')">
        @csrf
        @method('DELETE')
    </form>
@endif