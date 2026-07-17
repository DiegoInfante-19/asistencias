<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Agregar Formación Previa
            </div>
            <div class="card-body bg-light">

                <div class="alert alert-info py-2 shadow-sm border-0 small">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Debe seleccionar un <b>Título Base</b> o un <b>Título PNF</b> (o ambos).
                </div>

                <form action="{{ route('personas.formacion.store', $persona->id_personas) }}" method="POST">
                    @csrf

                    <!-- Selección de Título Base -->
                    <div class="mb-3">
                        <label for="id_titulos" class="form-label fw-bold">Título Base</label>
                        <select class="form-select select2-buscador @error('id_titulos') is-invalid @enderror" id="id_titulos" name="id_titulos">
                            <option value="" selected>Seleccione un título base (Opcional)...</option>
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

                    <!-- Selección de Título PNF -->
                    <div class="mb-3">
                        <label for="id_titulos_pnf" class="form-label fw-bold">Título PNF</label>
                        <select class="form-select select2-buscador @error('id_titulos_pnf') is-invalid @enderror" id="id_titulos_pnf" name="id_titulos_pnf">
                            <option value="" selected>Seleccione un título PNF (Opcional)...</option>
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

                    <!-- Observación -->
                    <div class="mb-3">
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
                                    <!-- COLUMNA 1: TIPO DE TÍTULO (Texto estilizado, no botón) -->
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

                                    <!-- COLUMNA 2: NOMBRE DEL TÍTULO DINÁMICO -->
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

                                    <!-- COLUMNA 3: OBSERVACIÓN -->
                                    <td class="small text-muted">
                                        {{ $formacion->observacion_formacion_academica ?? 'Sin observaciones registradas.' }}
                                    </td>

                                    <!-- COLUMNA 4: ACCIONES -->
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
</div>