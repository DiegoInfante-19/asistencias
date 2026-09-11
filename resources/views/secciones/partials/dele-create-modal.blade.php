<!-- Modal para Crear Nueva Sección -->
<div class="modal fade" id="createSeccionModal" tabindex="-1" aria-labelledby="createSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('secciones.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="createSeccionModalLabel">
                        <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nueva Sección
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <!-- Selección de Período Académico -->
                    <div class="mb-3">
                        <label for="id_periodo" class="form-label fw-bold text-dark">Período Académico <span class="text-danger">*</span></label>
                        <select name="id_periodo" id="id_periodo" class="form-select @error('id_periodo') is-invalid @enderror" required>
                            <option value="" selected disabled>Seleccione un período...</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id_periodo }}">
                                    Período (Inicia: {{ $periodo->fecha_inicio }} - Fin: {{ $periodo->fecha_fin }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_periodo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selección de PNF -->
                    <div class="mb-3">
                        <label for="id_pnf" class="form-label fw-bold text-dark">PNF (Aislamiento de Sección) <span class="text-danger">*</span></label>
                        <select name="id_pnf" id="id_pnf" class="form-select @error('id_pnf') is-invalid @enderror" required>
                            <option value="" selected disabled>Seleccione un PNF...</option>
                            @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small text-muted">Todos los estudiantes inscritos en esta sección deberán pertenecer obligatoriamente a este PNF.</div>
                        @error('id_pnf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nombre de la Sección -->
                    <div class="mb-3">
                        <label for="nombre_seccion" class="form-label fw-bold text-dark">Identificador / Nombre de la Sección <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_seccion" id="nombre_seccion" class="form-control @error('nombre_seccion') is-invalid @enderror" placeholder="Ej. M1, M2, IN1..." value="{{ old('nombre_seccion') }}" required>
                        @error('nombre_seccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label for="estatus_seccion" class="form-label fw-bold text-dark">Estatus Inicial</label>
                        <select name="estatus_seccion" id="estatus_seccion" class="form-select" required>
                            <option value="Activa" selected>Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">
                        <i class="bi bi-save-fill me-1"></i> Guardar Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>