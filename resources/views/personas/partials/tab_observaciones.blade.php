<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-journal-text me-1"></i> Observaciones Especiales (Salud / Generales)
            </div>
            <div class="card-body bg-light">
                
                <div class="alert alert-info py-2 shadow-sm border-0">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Esta sección es vital para registrar condiciones médicas, alergias, discapacidades o notas internas importantes sobre el estudiante.
                </div>

                <form action="{{ route('personas.observaciones.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="observacion_persona" class="form-label fw-bold">Nota de Observación</label>
                        <!-- Usamos null coalescing (??) para que cargue la observación solo si la relación existe -->
                        <textarea class="form-control @error('observacion_persona') is-invalid @enderror" 
                                  id="observacion_persona" 
                                  name="observacion_persona" 
                                  rows="6" 
                                  placeholder="Ej: El estudiante es alérgico a la penicilina..."
                                  maxlength="500">{{ old('observacion_persona', $persona->observacion->observacion_persona ?? '') }}</textarea>
                        @error('observacion_persona')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted small mt-1 text-end">Máximo 500 caracteres.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-1"></i> 
                            {{ $persona->observacion ? 'Actualizar Observación' : 'Guardar Observación' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
