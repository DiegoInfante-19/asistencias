<!-- Modal para Generación Masiva de Sesiones Académicas -->
<div class="modal fade" id="modalGenerarMasivo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalGenerarMasivoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" id="modalGenerarMasivoLabel" style="font-weight: 500;">
                    Generación Masiva de Sesiones
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <form id="formGenerarMasivo" action="{{ route('sesiones.store_masivo') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_seccion" value="{{ $seccion->id_seccion ?? '' }}">

                    <!-- Información del Período Lectivo -->
                    <div class="p-3 bg-light rounded border mb-4">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-calendar-range text-primary me-2"></i>
                            <span class="fw-bold small text-muted">Período Lectivo de la Sección</span>
                        </div>
                        <p class="text-dark fw-semibold mb-1">
                            {{ $seccion->periodoAcademico?->fecha_inicio ? \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_inicio)->format('d/m/Y') : 'N/D' }}
                            &nbsp;&rarr;&nbsp;
                            {{ $seccion->periodoAcademico?->fecha_fin ? \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_fin)->format('d/m/Y') : 'N/D' }}
                        </p>
                        <small class="text-muted d-block">Se programarán automáticamente los días <strong>miércoles</strong> comprendidos dentro del lapso.</small>
                    </div>

                    <!-- 1. Profesor Responsable -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Profesor Responsable <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-secondary-subtle shadow-sm" name="id_profesor" id="masivo_id_profesor" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @if(isset($seccion) && $seccion->profesores)
                                @foreach($seccion->profesores as $profesor)
                                    <option value="{{ $profesor->id_profesor }}">
                                        {{ trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? '')) }} (Titular)
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- 2. Alcance de Programación -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Alcance de Programación <span class="text-danger">*</span></label>
                        <div class="p-3 bg-light border rounded shadow-sm">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="modo_generacion" id="modoPeriodo" value="periodo" checked>
                                <label class="form-check-label fw-semibold text-dark small" for="modoPeriodo">
                                    Todo el Período Académico
                                </label>
                                <span class="d-block text-muted style-help" style="font-size: 0.75rem;">Programar todo el lapso lectivo de una vez.</span>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="modo_generacion" id="modoMeses" value="meses">
                                <label class="form-check-label fw-semibold text-dark small" for="modoMeses">
                                    Meses Específicos
                                </label>
                                <span class="d-block text-muted style-help" style="font-size: 0.75rem;">Seleccionar únicamente meses determinados dentro del período.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Desplegable: Selección de Meses -->
                    <div class="form-group mb-3 d-none" id="panelModoMeses">
                        <div class="p-3 bg-light border rounded">
                            <label class="form-label fw-bold small text-muted d-block mb-2">Seleccione los meses a incluir:</label>
                            <div class="row g-2">
                                @php
                                    $inicioPeriodo = $seccion->periodoAcademico?->fecha_inicio ? \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_inicio) : null;
                                    $finPeriodo = $seccion->periodoAcademico?->fecha_fin ? \Carbon\Carbon::parse($seccion->periodoAcademico->fecha_fin) : null;
                                @endphp

                                @if($inicioPeriodo && $finPeriodo)
                                    @php
                                        $current = $inicioPeriodo->copy()->startOfMonth();
                                        $endMonth = $finPeriodo->copy()->startOfMonth();
                                    @endphp
                                    @while($current->lte($endMonth))
                                        <div class="col-md-4 col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="meses[]" value="{{ $current->format('Y-m') }}" id="mes_{{ $current->format('Y_m') }}" checked>
                                                <label class="form-check-label text-capitalize small text-dark" for="mes_{{ $current->format('Y_m') }}">
                                                    {{ $current->locale('es')->monthName }} {{ $current->format('Y') }}
                                                </label>
                                            </div>
                                        </div>
                                        @php $current->addMonth(); @endphp
                                    @endwhile
                                @else
                                    <div class="text-muted small">Sin fechas de período definidas.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. Reglas Automatizadas -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Reglas Automatizadas</label>
                        <div class="p-3 bg-light border rounded shadow-sm">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="omitir_pasadas" value="1" id="omitir_pasadas" checked>
                                <label class="form-check-label fw-bold small text-dark" for="omitir_pasadas">
                                    Omitir fechas pasadas (anteriores a hoy)
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="omitir_recesos" value="1" id="omitir_recesos" checked>
                                <label class="form-check-label fw-bold small text-dark" for="omitir_recesos">
                                    Omitir feriados y recesos institucionales
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="omitir_existentes" value="1" id="omitir_existentes" checked>
                                <label class="form-check-label fw-bold small text-dark" for="omitir_existentes">
                                    Omitir clases previamente registradas
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Observación -->
                    <div class="form-group mb-2">
                        <label class="form-label fw-bold small text-muted">Observación (Opcional)</label>
                        <input type="text" class="form-control bg-light border-secondary-subtle shadow-sm" name="observacion_generacion" placeholder="Ej: Generación masiva de clases">
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit" form="formGenerarMasivo" class="btn btn-primary fw-bold" id="btnEjecutarMasivo">
                    <i class="bi bi-magic me-1"></i> Generar Sesiones
                </button>
            </div>

        </div>
    </div>
</div>