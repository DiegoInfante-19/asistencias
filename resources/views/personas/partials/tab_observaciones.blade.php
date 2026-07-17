<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-journal-text me-1"></i> Observaciones Especiales (Salud / Generales)
            </div>
            <div class="card-body bg-light">
                
                <div class="alert alert-info py-2 shadow-sm border-0 small">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Esta sección es vital para registrar condiciones médicas, alergias, discapacidades o notas internas importantes sobre el estudiante.
                </div>

                <form action="{{ route('personas.observaciones.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="observacion_personas" class="form-label fw-bold">Nota de Observación <span class="text-danger">*</span></label>
                        
                        <!-- 
                            1. Corregido: 'name' y 'id' ahora son observacion_personas 
                            2. Corregido: El maxlength se subió a 1000 para coincidir con tu Request
                            3. Corregido: Se busca el valor real en $persona->observacion->observacion_personas
                        -->
                        <textarea class="form-control @error('observacion_personas') is-invalid @enderror" 
                                  id="observacion_personas" 
                                  name="observacion_personas" 
                                  rows="6" 
                                  placeholder="Ej: El estudiante es alérgico a la penicilina..."
                                  maxlength="1000" required>{{ old('observacion_personas', $persona->observacion->observacion_personas ?? '') }}</textarea>
                        
                        @error('observacion_personas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted small">
                                @if($persona->observacion)
                                    <i class="bi bi-clock-history me-1"></i> Última actualización: {{ $persona->observacion->updated_at->format('d/m/Y h:i A') }}
                                @else
                                    <i class="bi bi-file-earmark-plus me-1"></i> Sin registros previos.
                                @endif
                            </span>
                            <span class="text-muted small">Máximo 1000 caracteres.</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
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