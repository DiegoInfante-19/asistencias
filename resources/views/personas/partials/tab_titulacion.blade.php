<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE ASIGNACIÓN -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-mortarboard-fill me-1"></i>
                {{ $persona->titulacionPersona ? 'Actualizar' : 'Asignar' }} Expediente Académico
            </div>
            <div class="card-body bg-light">

                <div class="alert alert-warning py-2 shadow-sm border-0 small mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Esta sección define el objetivo académico del estudiante en la institución.
                </div>

                <form action="{{ route('personas.titulacion.store', $persona->id_personas) }}" method="POST">
                    @csrf

                    <!-- 1. Selección del PNF -->
                    <div class="mb-3">
                        <label for="id_pnf" class="form-label fw-bold">Programa Nacional de Formación (PNF) <span class="text-danger">*</span></label>
                        <select class="form-select select2-buscador @error('id_pnf') is-invalid @enderror" id="id_pnf" name="id_pnf" required>
                            <option value="" selected disabled>Seleccione el PNF a cursar...</option>
                            @foreach($pnfs as $pnf)
                            <option value="{{ $pnf->id_pnf }}"
                                {{ old('id_pnf', $persona->titulacionPersona->id_pnf ?? '') == $pnf->id_pnf ? 'selected' : '' }}>
                                {{ $pnf->nombre_pnf }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_pnf')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 2. TÍTULO Y ESTATUS EN LA MISMA LÍNEA -->
                    <div class="row g-3 mb-3">
                        <!-- Selección del Título (Se llena vía AJAX) -->
                        <div class="col-md-6">
                            <label for="id_titulacion" class="form-label fw-bold">Título a Optar <span class="text-danger">*</span></label>
                            <select class="form-select select2-buscador @error('id_titulacion') is-invalid @enderror" id="id_titulacion" name="id_titulacion" required>
                                <option value="">Seleccione primero un PNF...</option>
                            </select>
                            @error('id_titulacion')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección del Estatus -->
                        <div class="col-md-6">
                            <label for="id_estatus_expediente" class="form-label fw-bold">Estatus <span class="text-danger">*</span></label>
                            <select class="form-select select2-buscador @error('id_estatus_expediente') is-invalid @enderror" id="id_estatus_expediente" name="id_estatus_expediente" required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach($estatusExpedientes as $estatus)
                                <option value="{{ $estatus->id_estatus_expediente }}"
                                    {{ old('id_estatus_expediente', $persona->titulacionPersona->id_estatus_expediente ?? '') == $estatus->id_estatus_expediente ? 'selected' : '' }}>
                                    {{ $estatus->nombre_estatus ?? $estatus->nombre_estatus_expediente ?? $estatus->descripcion_estatus ?? 'ID: '.$estatus->id_estatus_expediente }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_estatus_expediente')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save-fill me-1"></i>
                            {{ $persona->titulacionPersona ? 'Actualizar Expediente' : 'Guardar Expediente' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: EXPEDIENTE ACTUAL REGISTRADO -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-folder-check me-1"></i> Estado del Expediente Académico
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                @if ($persona->titulacionPersona)
                <div class="p-4 border-start border-4 border-primary bg-light rounded shadow-sm">

                    <!-- PNF Asignado -->
                    <div class="mb-4">
                        <label class="text-muted small fw-bold d-block text-uppercase mb-1">Programa Cursante (PNF)</label>
                        <span class="fs-5 fw-bold text-dark">
                            <i class="bi bi-book text-secondary me-2"></i>
                            {{ $persona->titulacionPersona->pnf->nombre_pnf ?? 'Sin nombre' }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <!-- Título a Optar -->
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold d-block text-uppercase mb-1">Título a Optar</label>
                            <div class="fs-6 text-secondary fw-bold d-flex align-items-start">
                                <i class="bi bi-award me-2 mt-1 flex-shrink-0"></i>
                                <span class="text-wrap">
                                    {{ $persona->titulacionPersona->titulacion->nombre_titulo_base ?? 'Sin nombre' }}
                                </span>
                            </div>
                        </div>

                        <!-- Estatus Visual -->
                        <div class="col-md-6 text-md-end">
                            <label class="text-muted small fw-bold d-block text-uppercase mb-1">Estatus Actual</label>
                            <div class="fs-6 text-primary fw-bold d-flex align-items-start justify-content-md-end justify-content-start">
                                <i class="bi bi-info-circle-fill me-2 mt-1 flex-shrink-0"></i>
                                <span class="text-wrap text-start text-md-end">
                                    {{ $persona->titulacionPersona->estatus->nombre_estatus ?? $persona->titulacionPersona->estatus->nombre_estatus_expediente ?? 'Desconocido' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-folder-x fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                    <h5 class="fw-light">Sin expediente configurado</h5>
                    <p class="small">El estudiante aún no tiene asignado un PNF ni un estatus académico.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let preselectedTitulo = "{{ old('id_titulacion', $persona->titulacionPersona->id_titulacion ?? '') }}";

        function inicializarSelectsTitulacion() {
            if (typeof window.$ !== 'undefined' && typeof window.$.fn.select2 !== 'undefined') {
                let $pnfSelect = $('#id_pnf');
                let $tituloSelect = $('#id_titulacion');

                function cargarTitulos(pnfId, tituloSeleccionado = "") {
                    if (!pnfId) {
                        $tituloSelect.empty().append('<option value="">Seleccione primero un PNF...</option>').prop('disabled', true);
                        if ($tituloSelect.data('select2')) { $tituloSelect.trigger('change'); }
                        return;
                    }

                    $tituloSelect.prop('disabled', true).html('<option value="">Cargando títulos...</option>');
                    if ($tituloSelect.data('select2')) { $tituloSelect.trigger('change'); }

                    $.ajax({
                        url: "{{ url('/titulos-por-pnf') }}/" + pnfId,
                        type: 'GET',
                        success: function(data) {
                            $tituloSelect.empty().append('<option value="">Seleccione un título...</option>');
                            $.each(data, function(key, item) {
                                let isSelected = (item.id_titulo == tituloSeleccionado) ? 'selected' : '';
                                $tituloSelect.append(`<option value="${item.id_titulo}" ${isSelected}>${item.nombre_titulo_pnf}</option>`);
                            });
                            
                            $tituloSelect.prop('disabled', false);
                            if ($tituloSelect.data('select2')) { $tituloSelect.trigger('change'); }
                        },
                        error: function() {
                            $tituloSelect.empty().append('<option value="">Error al cargar los títulos</option>').prop('disabled', true);
                            if ($tituloSelect.data('select2')) { $tituloSelect.trigger('change'); }
                        }
                    });
                }

                $pnfSelect.off('change.titulacion').on('change.titulacion', function() {
                    cargarTitulos($(this).val(), preselectedTitulo);
                    preselectedTitulo = "";
                });

                let currentPnf = $pnfSelect.val();
                if (currentPnf) {
                    cargarTitulos(currentPnf, preselectedTitulo);
                }
            } else {
                setTimeout(inicializarSelectsTitulacion, 50);
            }
        }

        inicializarSelectsTitulacion();
    });
</script>
@endpush