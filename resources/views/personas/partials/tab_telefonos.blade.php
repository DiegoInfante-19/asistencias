<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE REGISTRO -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Agregar Teléfono
            </div>
            <div class="card-body bg-light">

                <div class="alert alert-info py-2 shadow-sm border-0 small mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Registre los números de contacto del estudiante.
                </div>

                <form action="{{ route('personas.telefonos.store', $persona->id_personas) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <!-- CORREGIDO: for, id y name ajustados a numero_telefono_personas -->
                        <label for="numero_telefono_personas" class="form-label fw-bold">Número <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('numero_telefono_personas') is-invalid @enderror"
                            id="numero_telefono_personas" name="numero_telefono_personas"
                            value="{{ old('numero_telefono_personas') }}" placeholder="Ej: 04141234567" required>
                        @error('numero_telefono_personas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tipo_telefono" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo_telefono') is-invalid @enderror" id="tipo_telefono" name="tipo_telefono" required>
                            <option value="" selected disabled>Seleccione un tipo...</option>
                            <option value="Móvil" {{ old('tipo_telefono') == 'Móvil' ? 'selected' : '' }}>Móvil</option>
                            <option value="Fijo" {{ old('tipo_telefono') == 'Fijo' ? 'selected' : '' }}>Fijo (Casa)</option>
                            <option value="Trabajo" {{ old('tipo_telefono') == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                            <option value="WhatsApp" {{ old('tipo_telefono') == 'WhatsApp' ? 'selected' : '' }}>Solo WhatsApp</option>
                        </select>
                        @error('tipo_telefono')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save-fill me-1"></i> Guardar Teléfono
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: TABLA DE TELÉFONOS REGISTRADOS -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-list-check me-1"></i> Teléfonos Registrados
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle shadow-sm border">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;" class="text-center">#</th>
                                <th style="width: 40%;">Número</th>
                                <th style="width: 35%;">Tipo</th>
                                <th style="width: 15%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->telefonos as $index => $telefono)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>

                                <!-- CORREGIDO: Muestra la variable real de la BD -->
                                <td class="fw-bold text-dark fs-6">
                                    {{ $telefono->numero_telefono_personas }}
                                </td>

                                <td>
                                    <!-- Estilos visuales unificados (Texto semántico sin fondo de botón) -->
                                    @if($telefono->tipo_telefono == 'WhatsApp')
                                    <div class="d-flex align-items-center text-success fw-bold">
                                        <i class="bi bi-whatsapp fs-5 me-2"></i>
                                        <span>WhatsApp</span>
                                    </div>
                                    @elseif($telefono->tipo_telefono == 'Móvil')
                                    <div class="d-flex align-items-center text-primary fw-bold">
                                        <i class="bi bi-phone fs-5 me-2"></i>
                                        <span>Móvil</span>
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center text-secondary fw-bold">
                                        <i class="bi bi-telephone fs-5 me-2"></i>
                                        <span>{{ $telefono->tipo_telefono }}</span>
                                    </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <!-- CORREGIDO: PK en la ruta (id_telefonos_personas) y clase form-delete para SweetAlert2 -->
                                    <form action="{{ route('personas.telefonos.destroy', ['persona' => $persona->id_personas, 'telefono' => $telefono->id_telefonos_personas]) }}" method="POST" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar Teléfono">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted bg-white">
                                    <i class="bi bi-telephone-x fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                                    <h6 class="fw-semibold">Sin teléfonos registrados</h6>
                                    <span class="small">El estudiante aún no tiene contactos telefónicos guardados.</span>
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