<div class="btn-group" role="group">
    <a href="{{ route('secciones.show', $seccion->id_seccion) }}" class="btn btn-outline-primary btn-sm" title="Ver Detalle y Estudiantes">
        <i class="bi bi-eye"></i>
    </a>
    <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta sección?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar Sección">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>