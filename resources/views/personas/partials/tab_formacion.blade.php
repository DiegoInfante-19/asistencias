<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Agregar Título Previo
            </div>
            <div class="card-body bg-light">
                
                <form action="{{ route('personas.formacion.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nivel_academico" class="form-label fw-bold">Nivel Académico <span class="text-danger">*</span></label>
                        <select class="form-select @error('nivel_academico') is-invalid @enderror" id="nivel_academico" name="nivel_academico" required>
                            <option value="" selected disabled>Seleccione un nivel...</option>
                            <option value="Bachiller">Bachiller</option>
                            <option value="Técnico Medio">Técnico Medio</option>
                            <option value="Técnico Superior Universitario">TSU</option>
                            <option value="Licenciatura">Licenciatura</option>
                            <option value="Ingeniería">Ingeniería</option>
                            <option value="Postgrado">Postgrado</option>
                        </select>
                        @error('nivel_academico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="titulo_obtenido" class="form-label fw-bold">Título Obtenido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo_obtenido') is-invalid @enderror" 
                               id="titulo_obtenido" name="titulo_obtenido" placeholder="Ej: Bachiller en Ciencias" required>
                        @error('titulo_obtenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="institucion" class="form-label fw-bold">Institución / Casa de Estudios <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('institucion') is-invalid @enderror" 
                               id="institucion" name="institucion" placeholder="Ej: U.E.N. Simón Bolívar" required>
                        @error('institucion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="anio_egreso" class="form-label fw-bold">Año de Egreso <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('anio_egreso') is-invalid @enderror" 
                               id="anio_egreso" name="anio_egreso" placeholder="Ej: 2018" min="1950" max="{{ date('Y') }}" required>
                        @error('anio_egreso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
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
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-award-fill me-1"></i> Historial de Formación
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nivel</th>
                                <th>Título Obtenido</th>
                                <th>Institución</th>
                                <th class="text-center">Año</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Asumiendo que la relación en tu modelo Persona se llama formacionAcademica -->
                            @forelse ($persona->formacionAcademica as $formacion)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $formacion->nivel_academico }}</span></td>
                                    <td class="fw-bold">{{ $formacion->titulo_obtenido }}</td>
                                    <td>{{ $formacion->institucion }}</td>
                                    <td class="text-center">{{ $formacion->anio_egreso }}</td>
                                    <td class="text-center">
                                        <!-- Formulario inline para eliminar el registro -->
                                        <!-- Ajusta id_formacion a la clave primaria real de tu tabla si es diferente -->
                                        <form action="{{ route('personas.formacion.destroy', ['persona' => $persona->id_personas, 'formacion' => $formacion->id_formacion ?? $formacion->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro académico?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Formación">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                        Este estudiante no tiene formación académica previa registrada.
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