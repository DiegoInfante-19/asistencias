<div class="btn-group" role="group" aria-label="Acciones de gestión">
    <!-- Botón Ver Detalle -->
    <a href="{{ route('secciones.show', $seccion->id_seccion) }}" class="btn btn-outline-secondary" title="Ver Detalle y Estudiantes">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Botón Eliminar Vinculado al Formulario Externo -->
    <button type="submit" form="form-delete-{{ $seccion->id_seccion }}" class="btn btn-outline-secondary" title="Eliminar Sección">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $seccion->id_seccion }}" action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" class="d-none" onsubmit="return confirm('¿Está seguro de eliminar esta sección?');">
    @csrf
    @method('DELETE')
</form>