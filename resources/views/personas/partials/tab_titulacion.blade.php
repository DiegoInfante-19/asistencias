<div class="row mt-4">
    <div class="col-md-10 offset-md-1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-mortarboard-fill me-1"></i> Perfil Académico Principal
            </div>
            <div class="card-body bg-light">

                <div class="alert alert-warning py-2 shadow-sm border-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Esta sección define el objetivo académico del estudiante en la institución. Los cambios en el estatus del expediente afectarán la emisión de constancias.
                </div>

                <form action="{{ route('personas.titulacion.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <!-- Selección del PNF -->
                        <div class="col-md-12">
                            <label for="id_pnfs" class="form-label fw-bold">Programa Nacional de Formación (PNF) <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_pnfs') is-invalid @enderror" id="id_pnfs" name="id_pnfs" required>
                                <option value="" selected disabled>Seleccione el PNF a cursar...</option>
                                @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnfs }}" 
                                        {{ old('id_pnfs', $persona->titulacion->id_pnfs ?? '') == $pnf->id_pnfs ? 'selected' : '' }}>
                                        {{ $pnf->nombre_pnf ?? 'PNF ' . $pnf->id_pnfs }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pnfs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección del Título a optar -->
                        <div class="col-md-6">
                            <label for="id_titulos" class="form-label fw-bold">Título a Optar <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_titulos') is-invalid @enderror" id="id_titulos" name="id_titulos" required>
                                <option value="" selected disabled>Seleccione el título...</option>
                                @foreach($titulos as $titulo)
                                    <option value="{{ $titulo->id_titulos }}"
                                        {{ old('id_titulos', $persona->titulacion->id_titulos ?? '') == $titulo->id_titulos ? 'selected' : '' }}>
                                        {{ $titulo->nombre_titulo ?? 'Título ' . $titulo->id_titulos }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_titulos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección del Estatus del Expediente -->
                        <div class="col-md-6">
                            <label for="id_estatus_expediente" class="form-label fw-bold">Estatus del Expediente <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_estatus_expediente') is-invalid @enderror" id="id_estatus_expediente" name="id_estatus_expediente" required>
                                <option value="" selected disabled>Seleccione el estatus...</option>
                                @foreach($estatusExpedientes as $estatus)
                                    <option value="{{ $estatus->id_estatus_expediente }}"
                                        {{ old('id_estatus_expediente', $persona->titulacion->id_estatus_expediente ?? '') == $estatus->id_estatus_expediente ? 'selected' : '' }}>
                                        {{ $estatus->nombre_estatus ?? 'Estatus ' . $estatus->id_estatus_expediente }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_estatus_expediente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-1"></i> 
                            {{ $persona->titulacion ? 'Actualizar Expediente' : 'Asignar Expediente' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>