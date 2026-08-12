<div class="row g-4 mt-2">
    <!-- COLUMNA IZQUIERDA: FORMULARIO DE INSCRIPCIÓN -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-calendar-plus me-1"></i> Inscribir en Grupo Académico
            </div>
            <div class="card-body bg-light">
                
                <!-- VALIDACIÓN DE PRERREQUISITOS DE NEGOCIO (HARD LOCK) -->
                @if(!$persona->titulacionPersona)
                    <div class="text-center py-5">
                        <i class="bi bi-shield-lock-fill text-danger mb-3" style="font-size: 3.5rem;"></i>
                        <h5 class="fw-bold">Acción Bloqueada</h5>
                        <p class="text-muted small px-2">Para poder inscribir a este estudiante en una cohorte, <strong>primero debe asignarle un Programa Nacional de Formación (PNF)</strong> en la pestaña "Exp. Académico".</p>
                    </div>
                @else
                    <!-- RETROALIMENTACIÓN VISUAL (CONTEXTO) -->
                    <div class="alert alert-primary py-2 shadow-sm border-0 small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Filtrando grupos exclusivos para:<br>
                        <strong>{{ $persona->titulacionPersona->pnf->nombre_pnf ?? 'PNF Asignado' }}</strong>
                    </div>

                    <form action="{{ route('personas.inscripciones.store', $persona->id_personas) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_personas" value="{{ $persona->id_personas }}">

                        <!-- Select: Cohorte (Se limpiará vía JS según el PNF) -->
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

                        <!-- Select: Nivel (TSU/Ing) -->
                        <div class="mb-3">
                            <label for="select_nivel" class="form-label fw-bold">2. Nivel Académico <span class="text-danger">*</span></label>
                            <select id="select_nivel" class="form-select" disabled required>
                                <option value="">-- Primero elija Cohorte --</option>
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
                @endif
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: HISTORIAL DE INSCRIPCIONES (Queda Exactamente Igual) -->
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
                                    <td class="fw-bold">{{ $inscripcion->grupo->cohorte->numero_cohorte ?? 'N/A' }}</td>
                                    <td>
                                        <div class="text-dark fw-semibold">{{ $inscripcion->grupo->pnf->nombre_pnf ?? 'N/A' }}</div>
                                        <div class="small text-muted">Nivel: {{ $inscripcion->grupo->nivel_academico->value ?? $inscripcion->grupo->nivel_academico }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if($inscripcion->estatus_inscripcion_cohortes == 'Activo')
                                            <span class="badge bg-success px-2 py-1">Activo</span>
                                        @elseif($inscripcion->estatus_inscripcion_cohortes == 'Retirado')
                                            <span class="badge bg-danger px-2 py-1">Retirado</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">{{ $inscripcion->estatus_inscripcion_cohortes }}</span>
                                        @endif
                                    </td>
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

@if($persona->titulacionPersona)
<!-- LÓGICA DE JAVASCRIPT: FILTRADO ESTRICTO POR DOMINIO ACADÉMICO -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gruposDisponibles = @json($gruposData ?? []);
    
    // 1. Obtenemos el PNF base del estudiante
    const pnfEstudianteId = {{ $persona->titulacionPersona->id_pnf }};

    // 2. Filtramos el JSON para que JS "olvide" que existen otros PNF
    const gruposDelPnf = gruposDisponibles.filter(g => g.id_pnf == pnfEstudianteId);
    
    // 3. Extraemos solo los IDs de las cohortes que sí tienen grupos para este PNF
    const cohortesValidas = new Set(gruposDelPnf.map(g => String(g.id_cohortes)));

    const selectCohorte = document.getElementById('select_cohorte');
    const selectNivel = document.getElementById('select_nivel');
    const inputGrupoHidden = document.getElementById('id_grupo_hidden');
    const btnInscribir = document.getElementById('btn_inscribir');

    if(selectCohorte) {
        
        // 4. Limpieza del Selector de Cohortes (Elimina del HTML las cohortes ajenas)
        Array.from(selectCohorte.options).forEach(option => {
            if (option.value && !cohortesValidas.has(option.value)) {
                option.remove(); 
            }
        });

        // 5. Manejo de caso vacío
        if (selectCohorte.options.length === 1) {
            selectCohorte.options[0].text = "No hay cohortes activas para este PNF";
            selectCohorte.disabled = true;
        }

        // 6. Evento Cambio de Cohorte -> Carga de Niveles
        selectCohorte.addEventListener('change', function () {
            const cohorteId = this.value;
            
            // Reiniciamos el Nivel
            selectNivel.innerHTML = '<option value="" selected disabled>-- Seleccione Nivel --</option>';
            inputGrupoHidden.value = '';
            btnInscribir.disabled = true;

            if (!cohorteId) return;

            // Extraemos los niveles (TSU, Ingeniería, etc.) disponibles
            const niveles = gruposDelPnf.filter(g => g.id_cohortes == cohorteId);
            
            niveles.forEach(g => {
                selectNivel.innerHTML += `<option value="${g.id_grupo}">${g.nivel_academico}</option>`;
            });

            selectNivel.disabled = false;
        });

        // 7. Evento Cambio de Nivel -> Activación del Submit
        selectNivel.addEventListener('change', function () {
            inputGrupoHidden.value = this.value;
            btnInscribir.disabled = (this.value === '');
        });
    }
});
</script>
@endif