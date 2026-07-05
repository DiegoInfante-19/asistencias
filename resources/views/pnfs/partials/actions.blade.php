<div class="d-flex justify-content-center">
    <div class="btn-group" role="group" aria-label="Acciones de PNF">
        
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

        <form action="{{ route('pnfs.destroy', $pnf->id_pnf) }}" method="POST" class="m-0 p-0 form-eliminar d-inline-block">
            @csrf 
            @method('DELETE')
            <button type="submit" class="btn btn-outline-secondary" title="Eliminar PNF">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</div>

<!-- <div class="d-flex justify-content-center">
    <div class="btn-group" role="group" aria-label="Acciones de PNF">
        <button type="button" class="btn btn-tabla btn-outline-secondary" title="Ver Detalles del PNF"
            data-bs-toggle="modal" 
            data-bs-target="#showPnfModal"
            data-nombre="{{ $pnf->nombre_pnf }}"
            data-descripcion="{{ $pnf->descripcion_pnf }}"
            data-vigencia="{{ $pnf->vigencia_pnf }}">
            <i class="bi bi-eye"></i>
        </button>

        <button type="button" class="btn btn-tabla btn-outline-secondary" title="Modificar PNF"
            data-bs-toggle="modal" 
            data-bs-target="#UpdatePnfModal"
            data-url="{{ route('pnfs.update', $pnf->id_pnf) }}"
            data-nombre="{{ $pnf->nombre_pnf }}"
            data-descripcion="{{ $pnf->descripcion_pnf }}"
            data-vigencia="{{ $pnf->vigencia_pnf }}">
            <i class="bi bi-pencil"></i>
        </button>

        <form action="{{ route('pnfs.destroy', $pnf->id_pnf) }}" method="POST" class="m-0 p-0 d-inline-block form-eliminar">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-tabla btn-outline-secondary" title="Eliminar PNF" style="border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px;">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</div> -->