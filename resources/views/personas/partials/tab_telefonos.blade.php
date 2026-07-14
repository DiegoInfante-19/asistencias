<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Agregar Teléfono
            </div>
            <div class="card-body bg-light">
                <!-- Apuntamos a la ruta anidada, pasando el ID de la persona -->
                <form action="{{ route('personas.telefonos.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="numero_telefono" class="form-label fw-bold">Número <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('numero_telefono') is-invalid @enderror" 
                               id="numero_telefono" name="numero_telefono" placeholder="Ej: 04141234567" required>
                        @error('numero_telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipo_telefono" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo_telefono') is-invalid @enderror" id="tipo_telefono" name="tipo_telefono" required>
                            <option value="" selected disabled>Seleccione un tipo...</option>
                            <option value="Móvil">Móvil</option>
                            <option value="Fijo">Fijo (Casa)</option>
                            <option value="Trabajo">Trabajo</option>
                            <option value="WhatsApp">Solo WhatsApp</option>
                        </select>
                        @error('tipo_telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Guardar Teléfono
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: TABLA DE TELÉFONOS REGISTRADOS -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-list-check me-1"></i> Teléfonos Registrados
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->telefonos as $index => $telefono)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $telefono->numero_telefono }}</td>
                                    <td>
                                        <!-- Un pequeño badge visual según el tipo de teléfono -->
                                        @if($telefono->tipo_telefono == 'Móvil' || $telefono->tipo_telefono == 'WhatsApp')
                                            <span class="badge bg-success"><i class="bi bi-phone"></i> {{ $telefono->tipo_telefono }}</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-telephone"></i> {{ $telefono->tipo_telefono }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Formulario inline para eliminar el teléfono -->
                                        <form action="{{ route('personas.telefonos.destroy', ['persona' => $persona->id_personas, 'telefono' => $telefono->id_telefonos]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este teléfono?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Teléfono">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                        Este estudiante no tiene teléfonos registrados.
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