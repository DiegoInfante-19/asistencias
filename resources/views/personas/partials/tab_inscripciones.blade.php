<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE INSCRIPCIÓN -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-calendar-plus me-1"></i> Inscribir en Cohorte
            </div>
            <div class="card-body bg-light">
                
                <form action="{{ route('personas.inscripciones.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <!-- Selección de la Cohorte -->
                    <div class="mb-3">
                        <label for="id_cohortes" class="form-label fw-bold">Cohorte <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_cohortes') is-invalid @enderror" id="id_cohortes" name="id_cohortes" required>
                            <option value="" selected disabled>Seleccione una cohorte...</option>
                            @foreach($cohortes as $cohorte)
                                <!-- Ajusta 'nombre_cohorte' o 'numero_cohorte' según tu tabla de cohortes -->
                                <option value="{{ $cohorte->id_cohortes }}">
                                    {{ $cohorte->nombre_cohorte ?? 'Cohorte ' . $cohorte->id_cohortes }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cohortes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha de Inscripción -->
                    <div class="mb-3">
                        <label for="fecha_inscripcion" class="form-label fw-bold">Fecha de Inscripción <span class="text-danger">*</span></label>
                        <!-- Usamos \Carbon\Carbon::now() para poner la fecha de hoy por defecto -->
                        <input type="date" class="form-control @error('fecha_inscripcion') is-invalid @enderror" 
                               id="fecha_inscripcion" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('fecha_inscripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estatus de la Inscripción -->
                    <div class="mb-3">
                        <label for="estatus_inscripcion_cohortes" class="form-label fw-bold">Estatus <span class="text-danger">*</span></label>
                        <select class="form-select @error('estatus_inscripcion_cohortes') is-invalid @enderror" id="estatus_inscripcion_cohortes" name="estatus_inscripcion_cohortes" required>
                            <option value="Activo" selected>Activo</option>
                            <option value="Retirado">Retirado</option>
                            <option value="Suspendido">Suspendido</option>
                        </select>
                        @error('estatus_inscripcion_cohortes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Registrar Inscripción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: HISTORIAL DE INSCRIPCIONES -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-calendar-check-fill me-1"></i> Historial de Cohortes
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Cohorte</th>
                                <th>Fecha de Inscripción</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->inscripciones as $inscripcion)
                                <tr>
                                    <!-- Si tienes la relación configurada en el modelo InscripcionCohorte, puedes acceder al nombre así: $inscripcion->cohorte->nombre_cohorte -->
                                    <td class="fw-bold">Cohorte #{{ $inscripcion->id_cohortes }}</td>
                                    
                                    <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                                    
                                    <td class="text-center">
                                        @if($inscripcion->estatus_inscripcion_cohortes == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">{{ $inscripcion->estatus_inscripcion_cohortes }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <!-- Ajusta id_inscripcion_cohortes a la clave primaria real si difiere -->
                                        <form action="{{ route('personas.inscripciones.destroy', ['persona' => $persona->id_personas, 'inscripcion' => $inscripcion->id_inscripcion_cohortes ?? $inscripcion->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de anular esta inscripción?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Anular Inscripción">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                        Este estudiante no está inscrito en ninguna cohorte.
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