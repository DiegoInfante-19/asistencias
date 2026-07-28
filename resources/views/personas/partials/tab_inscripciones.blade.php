<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE INSCRIPCIÓN -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-calendar-plus me-1"></i> Inscribir en Grupo Académico
            </div>
            <div class="card-body bg-light">
                
                <form action="{{ route('personas.inscripciones.store', $persona->id_personas) }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="id_personas" value="{{ $persona->id_personas }}">

                    <!-- Select: Cohorte -->
                    <div class="mb-3">
                        <label for="select_cohorte" class="form-label fw-bold">1. Cohorte <span class="text-danger">*</span></label>
                        <select id="select_cohorte" class="form-select" required>
                            <option value="" selected disabled>Seleccione una cohorte...</option>
                            @foreach($cohortes as $cohorte)
                                @if($cohorte->estatus_cohorte === 'Activo')
                                    <option value="{{ $cohorte->id_cohortes }}">
                                        {{ $cohorte->numero_cohorte }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Select: PNF -->
                    <div class="mb-3">
                        <label for="select_pnf" class="form-label fw-bold">2. PNF (Programa) <span class="text-danger">*</span></label>
                        <select id="select_pnf" class="form-select" disabled required>
                            <option value="">-- Primero elija Cohorte --</option>
                        </select>
                    </div>

                    <!-- Select: Nivel (TSU/Ing) -->
                    <div class="mb-3">
                        <label for="select_nivel" class="form-label fw-bold">3. Nivel Académico <span class="text-danger">*</span></label>
                        <select id="select_nivel" class="form-select" disabled required>
                            <option value="">-- Primero elija PNF --</option>
                        </select>
                    </div>

                    <!-- INPUT OCULTO QUE SE ENVÍA AL CONTROLADOR -->
                    <input type="hidden" name="id_grupo" id="id_grupo_hidden" value="">
                    @error('id_grupo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <!-- Fecha de Inscripción -->
                    <div class="mb-3 mt-3">
                        <label for="fecha_inscripcion" class="form-label fw-bold">Fecha de Inscripción <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_inscripcion') is-invalid @enderror" 
                               id="fecha_inscripcion" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('fecha_inscripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estatus Inicial -->
                    <div class="mb-4">
                        <label for="estatus_inscripcion_cohortes" class="form-label fw-bold">Estatus Inicial <span class="text-danger">*</span></label>
                        <select class="form-select @error('estatus_inscripcion_cohortes') is-invalid @enderror" id="estatus_inscripcion_cohortes" name="estatus_inscripcion_cohortes" required>
                            <option value="Activo" selected>Activo</option>
                            <option value="Retirado">Retirado</option>
                        </select>
                        @error('estatus_inscripcion_cohortes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" id="btn_inscribir" class="btn btn-primary btn-sm" disabled>
                            <i class="bi bi-save me-1"></i> Registrar Inscripción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: HISTORIAL DE INSCRIPCIONES -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-calendar-check-fill me-1"></i> Historial Académico del Estudiante
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Cohorte</th>
                                <th>PNF / Nivel</th>
                                <th>Inscripción</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persona->inscripciones as $inscripcion)
                                <tr>
                                    <!-- Columna 1: Cohorte -->
                                    <td class="fw-bold">{{ $inscripcion->grupo->cohorte->numero_cohorte ?? 'N/A' }}</td>
                                    
                                    <!-- Columna 2: PNF y Nivel -->
                                    <td>
                                        <div class="text-dark fw-semibold">{{ $inscripcion->grupo->pnf->nombre_pnf ?? 'N/A' }}</div>
                                        <div class="small text-muted">Nivel: {{ $inscripcion->grupo->nivel_academico->value ?? $inscripcion->grupo->nivel_academico }}</div>
                                    </td>
                                    
                                    <!-- Columna 3: Fecha -->
                                    <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                                    
                                    <!-- Columna 4: Estatus -->
                                    <td class="text-center">
                                        @if($inscripcion->estatus_inscripcion_cohortes == 'Activo')
                                            <span class="badge bg-success px-2 py-1">Activo</span>
                                        @elseif($inscripcion->estatus_inscripcion_cohortes == 'Retirado')
                                            <span class="badge bg-danger px-2 py-1">Retirado</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">{{ $inscripcion->estatus_inscripcion_cohortes }}</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Columna 5: Botón Eliminar -->
                                    <td class="text-center">
                                        <form action="{{ route('personas.inscripciones.destroy', ['persona' => $persona->id_personas, 'inscripcion' => $inscripcion->id_inscripcion_cohortes]) }}" method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Anular Inscripción">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3" style="opacity: 0.5;"></i>
                                        <h6 class="fw-semibold">Sin historial de inscripciones</h6>
                                        <span class="small">El estudiante aún no ha sido matriculado en ningún grupo.</span>
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

<!-- LÓGICA DE JAVASCRIPT PARA LOS SELECTS DEPENDIENTES -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Escuchamos solo cuando estemos en el Tab de Inscripciones
    const gruposDisponibles = @json($gruposData ?? []);

    const selectCohorte = document.getElementById('select_cohorte');
    const selectPnf = document.getElementById('select_pnf');
    const selectNivel = document.getElementById('select_nivel');
    const inputGrupoHidden = document.getElementById('id_grupo_hidden');
    const btnInscribir = document.getElementById('btn_inscribir');

    if(selectCohorte && gruposDisponibles.length > 0) {
        selectCohorte.addEventListener('change', function () {
            const cohorteId = this.value;
            selectPnf.innerHTML = '<option value="" selected disabled>-- Seleccione PNF --</option>';
            selectNivel.innerHTML = '<option value="" selected disabled>-- Primero elija PNF --</option>';
            selectNivel.disabled = true;
            inputGrupoHidden.value = '';
            validarBoton();

            if (!cohorteId) {
                selectPnf.disabled = true;
                return;
            }

            const mapPnfs = new Map();
            gruposDisponibles.forEach(g => {
                if (g.id_cohortes == cohorteId && !mapPnfs.has(g.id_pnf)) {
                    mapPnfs.set(g.id_pnf, g.nombre_pnf);
                    selectPnf.innerHTML += `<option value="${g.id_pnf}">${g.nombre_pnf}</option>`;
                }
            });

            selectPnf.disabled = false;
        });

        selectPnf.addEventListener('change', function () {
            const cohorteId = selectCohorte.value;
            const pnfId = this.value;

            selectNivel.innerHTML = '<option value="" selected disabled>-- Seleccione Nivel --</option>';
            inputGrupoHidden.value = '';
            validarBoton();

            if (!pnfId) {
                selectNivel.disabled = true;
                return;
            }

            const niveles = gruposDisponibles.filter(g => g.id_cohortes == cohorteId && g.id_pnf == pnfId);
            niveles.forEach(g => {
                selectNivel.innerHTML += `<option value="${g.id_grupo}">${g.nivel_academico}</option>`;
            });

            selectNivel.disabled = false;
        });

        selectNivel.addEventListener('change', function () {
            inputGrupoHidden.value = this.value;
            validarBoton();
        });

        function validarBoton() {
            if (inputGrupoHidden.value !== '') {
                btnInscribir.disabled = false;
            } else {
                btnInscribir.disabled = true;
            }
        }
    }
});
</script>