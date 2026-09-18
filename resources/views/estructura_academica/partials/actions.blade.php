<div class="btn-group" role="group" aria-label="Acciones de cohorte">
    @php
        $periodo = $cohorte->periodosAcademicos->first();
    @endphp

    {{-- 1. Botón Ver Secciones (Icono de Ojo) --}}
    @if($periodo)
        <a href="{{ route('estructura.periodos.secciones', $periodo->id_periodo) }}" 
           class="btn btn-outline-secondary" 
           title="Ver Secciones Registradas">
            <i class="bi bi-eye"></i>
        </a>
    @else
        <button type="button" class="btn btn-outline-secondary opacity-50" disabled title="Sin período enlazado">
            <i class="bi bi-eye"></i>
        </button>
    @endif

    {{-- 2. Botón Editar (Icono de Lápiz - Abre Modal) --}}
    {{-- AQUÍ ESTÁ EL CAMBIO: Se pasó $cohorte->numero_cohorte intacto --}}
    <button type="button"
        class="btn btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#modalEditarCohorte"
        data-id="{{ $cohorte->id_cohortes }}"
        data-numero="{{ $cohorte->numero_cohorte }}" 
        data-descripcion="{{ $cohorte->descripcion_cohorte }}"
        data-estatus="{{ $cohorte->estatus_cohorte }}"
        data-fecha-inicio="{{ optional($periodo)->fecha_inicio?->format('Y-m-d') }}"
        data-fecha-fin="{{ optional($periodo)->fecha_fin?->format('Y-m-d') }}"
        title="Modificar Cohorte">
        <i class="bi bi-pencil"></i>
    </button>

    {{-- 3. Botón Eliminar (Icono de Papelera) --}}
    <button type="submit"
        form="form-delete-cohorte-{{ $cohorte->id_cohortes }}"
        class="btn btn-outline-secondary"
        title="Eliminar Cohorte">
        <i class="bi bi-trash"></i>
    </button>
</div>

<form id="form-delete-cohorte-{{ $cohorte->id_cohortes }}" action="{{ route('cohortes.destroy', $cohorte->id_cohortes) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>