<div class="fila-estudiante d-inline-block w-100" data-inscripcion="{{ $inscripcion->id_inscripcion_seccion }}">
    <div class="btn-group w-100 shadow-sm btn-group-asistencia" role="group" aria-label="Estado de asistencia">
        
        <!-- Presente -->
        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_seccion }}"
            id="presente_{{ $inscripcion->id_inscripcion_seccion }}" value="Presente"
            {{ $estadoActual == 'Presente' ? 'checked' : '' }} autocomplete="off"
            {{ !$puedeEditar ? 'disabled' : '' }}>
        <label class="btn btn-sm btn-outline-secondary btn-asistencia-presente fw-bold" 
               for="presente_{{ $inscripcion->id_inscripcion_seccion }}" title="Marcar como Presente">
            <i class="bi bi-check-circle me-1"></i> Presente
        </label>

        <!-- Ausente -->
        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_seccion }}"
            id="ausente_{{ $inscripcion->id_inscripcion_seccion }}" value="Ausente"
            {{ $estadoActual == 'Ausente' ? 'checked' : '' }} autocomplete="off"
            {{ !$puedeEditar ? 'disabled' : '' }}>
        <label class="btn btn-sm btn-outline-secondary btn-asistencia-ausente fw-bold" 
               for="ausente_{{ $inscripcion->id_inscripcion_seccion }}" title="Marcar como Ausente">
            <i class="bi bi-x-circle me-1"></i> Ausente
        </label>

        <!-- Justificado -->
        <input type="radio" class="btn-check btn-estado" name="estado_{{ $inscripcion->id_inscripcion_seccion }}"
            id="justificado_{{ $inscripcion->id_inscripcion_seccion }}" value="Justificado"
            {{ $estadoActual == 'Justificado' ? 'checked' : '' }} autocomplete="off"
            {{ !$puedeEditar ? 'disabled' : '' }}>
        <label class="btn btn-sm btn-outline-secondary btn-asistencia-justificado fw-bold" 
               for="justificado_{{ $inscripcion->id_inscripcion_seccion }}" title="Marcar como Justificado">
            <i class="bi bi-exclamation-circle me-1"></i> Justificado
        </label>

    </div>
</div>

<style>
    /* Estado base desmarcado: Fondo blanco y borde gris neutro idéntico al estándar del sistema */
    .btn-group-asistencia .btn-outline-secondary {
        background-color: #ffffff !important;
        border-color: #6c757d !important;
        color: #6c757d !important;
    }

    /* Presente activo (Verde) */
    .btn-check:checked + .btn-asistencia-presente {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important;
    }

    /* Ausente activo (Rojo) */
    .btn-check:checked + .btn-asistencia-ausente {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
    }

    /* Justificado activo (Amarillo) */
    .btn-check:checked + .btn-asistencia-justificado {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000000 !important;
    }
</style>