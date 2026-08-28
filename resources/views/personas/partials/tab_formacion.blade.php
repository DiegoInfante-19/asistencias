<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 fw-bold text-dark">
                <i class="bi bi-plus-circle me-1 text-primary"></i> Agregar Formación Previa
            </div>
            <div class="card-body bg-white py-4">

                <div class="alert alert-info py-2 shadow-sm border-0 small mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Seleccione primero el tipo de titulación que desea registrar.
                </div>

                <form action="{{ route('personas.formacion.store', $persona->id_personas) }}" method="POST" id="form-formacion">
                    @csrf

                    <!-- CAMPO OCULTO: ORIGEN DE LA FORMACIÓN -->
                    <input type="hidden" name="origen_formacion" id="origen_formacion" value="{{ old('origen_formacion', 'Externo') }}">

                    <!-- PESTAÑAS DE SELECCIÓN -->
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
                                <label for="id_titulos" class="form-label fw-bold small text-muted">Seleccionar Título Base Externo/General</label>
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
                                <label for="id_titulos_pnf" class="form-label fw-bold small text-muted">Seleccionar Título PNF Universitario</label>
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

                    @error('origen_formacion')
                    <div class="alert alert-danger py-1 small mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Observación -->
                    <div class="mb-3 mt-2">
                        <label for="observacion_formacion_academica" class="form-label fw-bold small text-muted">Observación</label>
                        <textarea class="form-control @error('observacion_formacion_academica') is-invalid @enderror"
                            id="observacion_formacion_academica" name="observacion_formacion_academica"
                            rows="3" placeholder="Detalles adicionales (opcional)...">{{ old('observacion_formacion_academica') }}</textarea>
                        @error('observacion_formacion_academica')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-save me-1"></i> Guardar Formación
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light py-2 text-muted small">
                Registro de títulos previos.
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: TABLA DE FORMACIÓN REGISTRADA -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 fw-bold text-dark"><i class="bi bi-award-fill me-1 text-primary"></i> Grado de Instrucción del Estudiante</div>
            <div class="card-body bg-white p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
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
                                    <div class="d-flex flex-column">
                                        @if($formacion->origen_formacion === 'Interno')
                                        <span class="fw-bold text-primary mb-1">Interno</span>
                                        @else
                                        <span class="fw-bold text-success mb-1">Externo</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="fw-bold text-dark">
                                    @if($formacion->id_titulos && $formacion->titulo)
                                    {{ $formacion->titulo->nombre_titulo_base }}
                                    <span class="text-muted small fw-normal d-block mt-1">Nivel: {{ $formacion->titulo->nivel_academico }}</span>
                                    @elseif($formacion->id_titulos_pnf && $formacion->tituloPnf)
                                    {{ $formacion->tituloPnf->nombre_titulo_pnf }}
                                    @else
                                    <span class="text-danger small">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Error de registro.
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
            <div class="card-footer bg-light py-2 text-muted small">
                Historial de instrucción académica.
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectBase = $('#id_titulos');
        const selectPnf = $('#id_titulos_pnf');
        const tabBaseBtn = document.getElementById('tab-base-btn');
        const tabPnfBtn = document.getElementById('tab-pnf-btn');
        const inputOrigen = document.getElementById('origen_formacion');

        function actualizarCamposYOrigen() {
            if (tabPnfBtn && tabPnfBtn.classList.contains('active')) {
                inputOrigen.value = 'Interno';
                if (selectBase.length) {
                    selectBase.val('');
                    if (selectBase.data('select2')) {
                        selectBase.trigger('change');
                    }
                }
            } else {
                inputOrigen.value = 'Externo';
                if (selectPnf.length) {
                    selectPnf.val('');
                    if (selectPnf.data('select2')) {
                        selectPnf.trigger('change');
                    }
                }
            }
        }

        if (tabBaseBtn && tabPnfBtn) {
            tabBaseBtn.addEventListener('shown.bs.tab', actualizarCamposYOrigen);
            tabPnfBtn.addEventListener('shown.bs.tab', actualizarCamposYOrigen);
        }

        @if(old('id_titulos_pnf') || old('origen_formacion') === 'Interno')
        if (tabPnfBtn && typeof bootstrap !== 'undefined') {
            var pnfTab = new bootstrap.Tab(tabPnfBtn);
            pnfTab.show();
            inputOrigen.value = 'Interno';
        }
        @endif
    });
</script>
@endpush