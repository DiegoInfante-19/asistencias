<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO LABORAL -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-briefcase-fill me-1"></i> Añadir Experiencia Laboral
            </div>
            <div class="card-body bg-light">
                
                <form action="{{ route('personas.empresas.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <!-- Selección de Empresa -->
                    <div class="mb-3">
                        <label for="id_empresas" class="form-label fw-bold">Empresa <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_empresas') is-invalid @enderror" id="id_empresas" name="id_empresas" required>
                            <option value="" selected disabled>Seleccione una empresa...</option>
                            @foreach($empresas as $empresa)
                                <!-- Ajusta 'nombre_empresa' según la columna real de tu tabla empresas -->
                                <option value="{{ $empresa->id_empresas }}">
                                    {{ $empresa->nombre_empresa ?? 'Empresa ' . $empresa->id_empresas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_empresas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selección de Cargo -->
                    <div class="mb-3">
                        <label for="id_cargos" class="form-label fw-bold">Cargo <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_cargos') is-invalid @enderror" id="id_cargos" name="id_cargos" required>
                            <option value="" selected disabled>Seleccione un cargo...</option>
                            @foreach($cargos as $cargo)
                                <!-- Ajusta 'descripcion_cargo' según la columna real de tu tabla cargos -->
                                <option value="{{ $cargo->id_cargos }}">
                                    {{ $cargo->descripcion_cargo ?? 'Cargo ' . $cargo->id_cargos }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cargos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha de Ingreso -->
                    <div class="mb-3">
                        <label for="fecha_ingreso" class="form-label fw-bold">Fecha de Ingreso <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" 
                               id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
                        @error('fecha_ingreso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estatus en la empresa -->
                    <div class="mb-3">
                        <label for="estatus_empresa_personas" class="form-label fw-bold">Estatus <span class="text-danger">*</span></label>
                        <select class="form-select @error('estatus_empresa_personas') is-invalid @enderror" id="estatus_empresa_personas" name="estatus_empresa_personas" required>
                            <option value="Activo" selected>Activo (Trabajando actualmente)</option>
                            <option value="Inactivo">Inactivo (Empleo anterior)</option>
                        </select>
                        @error('estatus_empresa_personas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Guardar Perfil Laboral
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: HISTORIAL LABORAL -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-building-check me-1"></i> Historial Laboral Registrado
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Empresa</th>
                                <th>Cargo</th>
                                <th>Ingreso</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 'empresaPersona' fue el nombre que le dimos en el ->with() del controlador -->
                            @forelse ($persona->empresaPersona as $laboral)
                                <tr>
                                    <!-- Puedes usar $laboral->empresa->nombre_empresa si tienes la relación en el modelo -->
                                    <td class="fw-bold">ID Empresa: {{ $laboral->id_empresas }}</td>
                                    <td>ID Cargo: {{ $laboral->id_cargos }}</td>
                                    
                                    <td>{{ \Carbon\Carbon::parse($laboral->fecha_ingreso)->format('d/m/Y') }}</td>
                                    
                                    <td class="text-center">
                                        @if($laboral->estatus_empresa_personas == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <!-- Ajusta el nombre de la primary key (id_empresa_personas) según tu BD -->
                                        <form action="{{ route('personas.empresas.destroy', ['persona' => $persona->id_personas, 'empresa' => $laboral->id_empresa_personas ?? $laboral->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro laboral?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Registro">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                        El estudiante no posee experiencia laboral registrada.
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