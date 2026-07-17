<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO LABORAL -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-briefcase-fill me-1"></i>
                {{ $persona->empresaPersona ? 'Actualizar' : 'Añadir' }} Experiencia Laboral
            </div>
            <div class="card-body bg-light">

                <form action="{{ route('personas.empresas.store', $persona->id_personas) }}" method="POST">
                    @csrf

                    <!-- Selección de Empresa -->
                    <div class="mb-3">
                        <label for="id_empresa" class="form-label fw-bold">Empresa <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_empresa') is-invalid @enderror" id="id_empresa" name="id_empresa" required>
                            <option value="" selected disabled>Seleccione una empresa...</option>
                            @foreach($empresas as $empresa)
                            <!-- Asumiendo que la llave primaria de la tabla empresas es 'id_empresa' -->
                            <option value="{{ $empresa->id_empresa }}"
                                {{ old('id_empresa', $persona->empresaPersona->id_empresa ?? '') == $empresa->id_empresa ? 'selected' : '' }}>
                                {{ $empresa->nombre_empresa ?? 'Empresa ' . $empresa->id_empresa }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_empresa')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selección de Cargo -->
                    <div class="mb-3">
                        <label for="id_cargo" class="form-label fw-bold">Cargo <span class="text-danger">*</span></label>
                        <!-- Agregamos la clase 'select2-buscador' -->
                        <select class="form-select select2-buscador @error('id_cargo') is-invalid @enderror" id="id_cargo" name="id_cargo" required>
                            <option value="" selected disabled>Seleccione un cargo...</option>
                            @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id_cargo }}"
                                {{ old('id_cargo', $persona->empresaPersona->id_cargo ?? '') == $cargo->id_cargo ? 'selected' : '' }}>
                                {{ $cargo->descripcion_cargo ?? 'Cargo ' . $cargo->id_cargo }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_cargo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> {{ $persona->empresaPersona ? 'Actualizar' : 'Guardar' }} Perfil Laboral
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: PERFIL LABORAL ACTUAL -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-person-workspace me-1"></i> Empleo Actual
            </div>
            <div class="card-body">
                @if ($persona->empresaPersona)
                <div class="row align-items-center">
                    <!-- Detalles del empleo -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="text-muted small fw-bold d-block text-uppercase">Empresa</label>
                            <!-- Si cargaste la relación empresa en el controlador, puedes usar $persona->empresaPersona->empresa->nombre_empresa -->
                            <span class="fs-5 fw-bold text-dark">
                                {{ $persona->empresaPersona->empresa->nombre_empresa ?? 'ID Empresa: ' . $persona->empresaPersona->id_empresa }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small fw-bold d-block text-uppercase">Cargo Asignado</label>
                            <!-- Si cargaste la relación cargo en el controlador, puedes usar $persona->empresaPersona->cargo->descripcion_cargo -->
                            <span class="fs-6 text-secondary">
                                <i class="bi bi-person-badge me-1"></i>
                                {{ $persona->empresaPersona->cargo->descripcion_cargo ?? 'ID Cargo: ' . $persona->empresaPersona->id_cargo }}
                            </span>
                        </div>
                    </div>

                    <!-- Botón de Acción -->
                    <div class="col-md-4 text-md-end text-center mt-3 mt-md-0 border-start ps-4">
                        <span class="badge bg-success mb-3 px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i> Empleado</span>

                        <form action="{{ route('personas.empresas.destroy', ['persona' => $persona->id_personas, 'empresa' => $persona->empresaPersona->id_empresa_personas]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar el perfil laboral actual del estudiante?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-briefcase fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                    <h5 class="fw-light">Sin experiencia laboral</h5>
                    <p class="small">El estudiante no tiene asignada ninguna empresa o cargo actualmente.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>