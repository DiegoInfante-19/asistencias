<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Agregar Formación Previa
            </div>
            <div class="card-body bg-light">

                <div class="alert alert-info py-2 shadow-sm border-0 small mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Seleccione primero el tipo de titulación que desea registrar.
                </div>

                <form action="{{ route('personas.formacion.store', $persona->id_personas) }}" method="POST" id="form-formacion">
                    @csrf

                    <!-- PESTAÑAS DE SELECCIÓN (TIPO DE TÍTULO) -->
                    <ul class="nav nav-pills nav-fill mb-3" id="pills-tipo-titulo" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2" id="tab-base-btn" data-bs-toggle="pill" data-bs-target="#pane-base" type="button" role="tab" aria-controls="pane-base" aria-selected="true">
                                <i class="bi bi-mortarboard me-1"></i> Título Base
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2" id="tab-pnf-btn" data-bs-toggle="pill" data-bs-target="#pane-pnf" type="button" role="tab" aria-controls="pane-pnf" aria-selected="false">
                                <i class="bi bi-building-fill-check me-1"></i> Título PNF
                            </button>
                        </li>
                    </ul>

                    <!-- CONTENIDO DE LAS PESTAÑAS -->
                    <div class="tab-content" id="pills-tabContent">
                        
                        <!-- PESTAÑA 1: TÍTULO BASE -->
                        <div class="tab-pane fade show active" id="pane-base" role="tabpanel" aria-labelledby="tab-base-btn">
                            <div class="mb-3">
                                <label for="id_titulos" class="form-label fw-semibold text-secondary small">Seleccionar Título Base Externo/General</label>
                                <select class="form-select select2-buscador @error('id_titulos') is-invalid @enderror" id="id_titulos" name="id_titulos">
                                    <option value="" selected>Seleccione...</option>
                                    @foreach($titulos as $titulo)
                                    <option value="{{ $titulo->id_titulos }}" {{ old('id_titulos') == $titulo->id_titulos ? 'selected' : '' }}>
                                        {{ $titulo->nombre_titulo_base }} ({{ $titulo->nivel_academico }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_titulos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- PESTAÑA 2: TÍTULO PNF -->
                        <div class="tab-pane fade" id="pane-pnf" role="tabpanel" aria-labelledby="tab-pnf-btn">
                            <div class="mb-3">
                                <label for="id_titulos_pnf" class="form-label fw-semibold text-secondary small">Seleccionar Título PNF Universitario</label>
                                <select class="form-select select2-buscador @error('id_titulos_pnf') is-invalid @enderror" id="id_titulos_pnf" name="id_titulos_pnf">
                                    <option value="" selected>Seleccione...</option>
                                    @foreach($titulos_pnf as $tituloPnf)
                                    <option value="{{ $tituloPnf->id_titulos_pnf }}" {{ old('id_titulos_pnf') == $tituloPnf->id_titulos_pnf ? 'selected' : '' }}>
                                        {{ $tituloPnf->nombre_titulo_pnf }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_titulos_pnf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Observación -->
                    <div class="mb-3 mt-2">
                        <label for="observacion_formacion_academica" class="form-label fw-bold">Observación</label>
                        <textarea class="form-control @error('observacion_formacion_academica') is-invalid @enderror"
                            id="observacion_formacion_academica" name="observacion_formacion_academica"
                            rows="3" placeholder="Detalles adicionales (opcional)...">{{ old('observacion_formacion_academica') }}</textarea>
                        @error('observacion_formacion_academica')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Guardar Formación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: TABLA DE FORMACIÓN REGISTRADA -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="bi bi-award-fill me-1"></i> Grado de Instrucción del Estudiante</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle shadow-sm border">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 18%;">Categoría</th>
                                <th style="width: 42%;">Nombre del Título</th>
                                <th style="width: 30%;">Observación</th>
                                <th class="text-center" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->formacionAcademica as $formacion)
                            <tr>
                                <td>
                                    @if($formacion->id_titulos)
                                    <div class="d-flex align-items-center text-success fw-bold">
                                        <span>Título Base</span>
                                    </div>
                                    @elseif($formacion->id_titulos_pnf)
                                    <div class="d-flex align-items-center text-primary fw-bold">
                                        <span>Título PNF</span>
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center text-muted fw-bold">
                                        <span>Desconocido</span>
                                    </div>
                                    @endif
                                </td>

                                <td class="fw-bold text-dark">
                                    @if($formacion->id_titulos && $formacion->titulo)
                                    {{ $formacion->titulo->nombre_titulo_base }}
                                    <span class="text-muted small fw-normal d-block mt-1">Nivel: {{ $formacion->titulo->nivel_academico }}</span>
                                    @elseif($formacion->id_titulos_pnf && $formacion->tituloPnf)
                                    {{ $formacion->tituloPnf->nombre_titulo_pnf }}
                                    @else
                                    <span class="text-danger small">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Error de registro (Revisar BD).
                                    </span>
                                    @endif
                                </td>

                                <td class="small text-muted">
                                    {{ $formacion->observacion_formacion_academica ?? 'Sin observaciones registradas.' }}
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('personas.formacion.destroy', ['persona' => $persona->id_personas, 'formacion' => $formacion->id_persona_formacion_academica]) }}" method="POST" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar registro">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted bg-white">
                                    <i class="bi bi-award fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                                    <h6 class="fw-semibold">Sin Formación Previa</h6>
                                    <span class="small">El estudiante no tiene títulos registrados en su historial.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectBase = document.getElementById('id_titulos');
        const selectPnf = document.getElementById('id_titulos_pnf');
        const tabBaseBtn = document.getElementById('tab-base-btn');
        const tabPnfBtn = document.getElementById('tab-pnf-btn');

        // Función para limpiar y deshabilitar el campo opuesto al cambiar de pestaña
        function actualizarCamposActivos() {
            if (tabPnfBtn.classList.contains('active')) {
                // Si estamos en PNF, limpiamos Título Base
                if (selectBase) {
                    selectBase.value = '';
                    if (window.jQuery && $(selectBase).data('select2')) {
                        $(selectBase).val('').trigger('change');
                    }
                }
            } else {
                // Si estamos en Base, limpiamos Título PNF
                if (selectPnf) {
                    selectPnf.value = '';
                    if (window.jQuery && $(selectPnf).data('select2')) {
                        $(selectPnf).val('').trigger('change');
                    }
                }
            }
        }

        if (tabBaseBtn && tabPnfBtn) {
            tabBaseBtn.addEventListener('shown.bs.tab', actualizarCamposActivos);
            tabPnfBtn.addEventListener('shown.bs.tab', actualizarCamposActivos);
        }

        // Si hay un error de validación previo en PNF, abrir automáticamente esa pestaña al recargar
        @if(old('id_titulos_pnf'))
            if (tabPnfBtn) {
                var pnfTab = new bootstrap.Tab(tabPnfBtn);
                pnfTab.show();
            }
        @endif
    });
</script>
@endpush