<div class="btn-group" role="group" aria-label="Acciones de PNF">
    
    <!-- Botón con el icono de porcentaje (Reemplazo del botón de expansión anterior) -->
    <button type="button" class="btn btn-outline-secondary toggle-details" title="Ver Desglose de Porcentajes" data-id="{{ $pnf->id_pnf }}">
        <i class="bi bi-percent"></i>
    </button>

    <a href="{{ route('pnfs.show', $pnf->id_pnf) }}" class="btn btn-outline-secondary" title="Ver Panel del PNF">
        <i class="bi bi-eye"></i>
    </a>

    <button type="button" class="btn btn-outline-secondary" title="Modificar Datos Básicos"
        data-bs-toggle="modal" 
        data-bs-target="#UpdatePnfModal"
        data-url="{{ route('pnfs.update', $pnf->id_pnf) }}"
        data-nombre="{{ $pnf->nombre_pnf }}"
        data-vigencia="{{ $pnf->vigencia_pnf ? 1 : 0 }}"
        data-descripcion="{{ $pnf->descripcion_pnf }}">
        <i class="bi bi-pencil"></i>
    </button>

    <button type="submit" form="form-delete-{{ $pnf->id_pnf }}" class="btn btn-outline-secondary" title="Eliminar PNF">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-{{ $pnf->id_pnf }}" action="{{ route('pnfs.destroy', $pnf->id_pnf) }}" method="POST" class="d-none">
    @csrf 
    @method('DELETE')
</form>