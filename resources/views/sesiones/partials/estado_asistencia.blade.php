@php
    // Evitamos el TypeError extrayendo el valor del Enum
    $estadoNormalizado = $estadoActual instanceof \App\Enums\EstadoAsistencia ? $estadoActual->value : strtolower($estadoActual ?? 'presente');
    
    $badgeClass = match($estadoNormalizado) {
        'presente' => 'bg-success',
        'ausente'  => 'bg-danger',
        'justificada', 'justificado' => 'bg-warning text-dark',
        'tarde'    => 'bg-info text-dark',
        default => 'bg-secondary'
    };
    $badgeText = ucfirst($estadoNormalizado);
@endphp

<div class="fila-estudiante d-inline-block w-100" data-inscripcion="{{ $inscripcion->id_inscripcion_seccion }}" data-estado-original="{{ $estadoNormalizado }}">
    
    <!-- MODO LECTURA: Badge -->
    <div class="modo-lectura text-center w-100 {{ !$puedeEditar || $tieneAsistencia ? '' : 'd-none' }}">
        <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 shadow-sm w-100" style="min-width: 120px;">
            {{ $badgeText }}
        </span>
    </div>

    <!-- MODO EDICIÓN: Radios -->
    <div class="modo-edicion w-100 {{ !$puedeEditar || $tieneAsistencia ? 'd-none' : '' }}">
        <div class="btn-group w-100 shadow-sm btn-group-asistencia" role="group" aria-label="Estado de asistencia">
            
            <!-- Presente -->
            <input type="radio" class="btn-check btn-estado" name="estado_desk_{{ $inscripcion->id_inscripcion_seccion }}"
                id="presente_{{ $inscripcion->id_inscripcion_seccion }}" value="presente"
                {{ $estadoNormalizado == 'presente' ? 'checked' : '' }} autocomplete="off"
                {{ !$puedeEditar ? 'disabled' : '' }}>
            <label class="btn btn-outline-secondary btn-asistencia-presente text-center" 
                   for="presente_{{ $inscripcion->id_inscripcion_seccion }}" title="Presente">
                <span class="d-none d-md-inline">Presente</span>
                <span class="d-inline d-md-none">Pres.</span>
            </label>

            <!-- Ausente -->
            <input type="radio" class="btn-check btn-estado" name="estado_desk_{{ $inscripcion->id_inscripcion_seccion }}"
                id="ausente_{{ $inscripcion->id_inscripcion_seccion }}" value="ausente"
                {{ $estadoNormalizado == 'ausente' ? 'checked' : '' }} autocomplete="off"
                {{ !$puedeEditar ? 'disabled' : '' }}>
            <label class="btn btn-outline-secondary btn-asistencia-ausente text-center" 
                   for="ausente_{{ $inscripcion->id_inscripcion_seccion }}" title="Ausente">
                <span class="d-none d-md-inline">Ausente</span>
                <span class="d-inline d-md-none">Aus.</span>
            </label>

            <!-- Justificada -->
            <input type="radio" class="btn-check btn-estado" name="estado_desk_{{ $inscripcion->id_inscripcion_seccion }}"
                id="justificado_{{ $inscripcion->id_inscripcion_seccion }}" value="justificada"
                {{ $estadoNormalizado == 'justificada' || $estadoNormalizado == 'justificado' ? 'checked' : '' }} autocomplete="off"
                {{ !$puedeEditar ? 'disabled' : '' }}>
            <label class="btn btn-outline-secondary btn-asistencia-justificado text-center" 
                   for="justificado_{{ $inscripcion->id_inscripcion_seccion }}" title="Justificada">
                <span class="d-none d-md-inline">Justificada</span>
                <span class="d-inline d-md-none">Just.</span>
            </label>
        </div>
    </div>
</div>

<style>
    /* Diseño base y efectos de colores (Conservados de tu versión original) */
    .btn-group-asistencia .btn { font-size: 0.9rem !important; padding: 0.4rem 0.75rem !important; line-height: 1.5; border-color: #ced4da !important; transition: all 0.2s ease-in-out; }
    .btn-group-asistencia .btn-outline-secondary { background-color: #f8f9fa !important; color: #6c757d !important; }
    .btn-group-asistencia .btn-asistencia-presente:hover:not(:disabled) { background-color: #198754 !important; border-color: #198754 !important; color: #ffffff !important; opacity: 0.85; }
    .btn-group-asistencia .btn-asistencia-ausente:hover:not(:disabled) { background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important; opacity: 0.85; }
    .btn-group-asistencia .btn-asistencia-justificado:hover:not(:disabled) { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #000000 !important; opacity: 0.85; }
    .btn-check:checked + .btn-asistencia-presente { background-color: #198754 !important; border-color: #198754 !important; color: #ffffff !important; opacity: 1; box-shadow: inset 0 3px 5px rgba(0,0,0,0.125); }
    .btn-check:checked + .btn-asistencia-ausente { background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important; opacity: 1; box-shadow: inset 0 3px 5px rgba(0,0,0,0.125); }
    .btn-check:checked + .btn-asistencia-justificado { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #000000 !important; opacity: 1; box-shadow: inset 0 3px 5px rgba(0,0,0,0.125); }

    /* Modos de visualización */
    .modo-edicion, .modo-lectura { transition: opacity 0.3s ease-in-out; }

    @media (max-width: 767.98px) {
        .btn-group-asistencia { display: flex !important; width: 100% !important; }
        .btn-group-asistencia .btn { flex: 1 !important; font-size: 0.9rem !important; padding: 0.5rem 0.1rem !important; display: flex; align-items: center; justify-content: center; white-space: nowrap; }
    }
</style>