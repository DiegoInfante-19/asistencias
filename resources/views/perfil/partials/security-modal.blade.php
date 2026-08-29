<div class="modal fade" id="securityModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow border-0">
            
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-shield-lock-fill text-primary me-2"></i> Seguridad de la Cuenta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo blanco absoluto con inputs bg-light -->
            <div class="modal-body bg-white p-4">
                
                <!-- SECCIÓN 1: CAMBIO DE CONTRASEÑA -->
                <form id="formPassword" action="{{ route('seguridad.password.update') }}" method="POST" class="mb-5">
                    @csrf 
                    @method('PUT')
                    
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                        Cambio de Contraseña
                    </h6>
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Contraseña Actual <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control bg-light border-secondary-subtle shadow-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control bg-light border-secondary-subtle shadow-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Confirmar Nueva <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control bg-light border-secondary-subtle shadow-sm" required>
                        </div>
                        
                        <!-- Botón alineado a la derecha de la sección -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-warning fw-bold text-dark btn-sm px-3 shadow-sm">
                                <i class="bi bi-key-fill me-1"></i> Actualizar Clave
                            </button>
                        </div>
                    </div>
                </form>

                <!-- SECCIÓN 2: PREGUNTAS DE SEGURIDAD -->
                <form id="formSecurity" action="{{ route('seguridad.preguntas.update') }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                        Preguntas de Seguridad
                    </h6>
                    
                    <div class="row g-4">
                        <!-- Pregunta 1 -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light border-secondary-subtle">
                                <label class="form-label fw-bold small text-muted">Seleccione la Pregunta 1 <span class="text-danger">*</span></label>
                                <select name="pregunta1" class="form-select bg-white mb-2 shadow-sm">
                                    @foreach(\App\Models\PreguntaSecreta::listaPreguntas1() as $id => $pregunta)
                                        <option value="{{ $id }}">{{ $pregunta }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label fw-bold small text-muted mt-2">Su Respuesta <span class="text-danger">*</span></label>
                                <input type="text" name="respuesta1" class="form-control bg-white shadow-sm" placeholder="Respuesta secreta..." required autocomplete="off">
                            </div>
                        </div>
                        
                        <!-- Pregunta 2 -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light border-secondary-subtle">
                                <label class="form-label fw-bold small text-muted">Seleccione la Pregunta 2 <span class="text-danger">*</span></label>
                                <select name="pregunta2" class="form-select bg-white mb-2 shadow-sm">
                                    @foreach(\App\Models\PreguntaSecreta::listaPreguntas2() as $id => $pregunta)
                                        <option value="{{ $id }}">{{ $pregunta }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label fw-bold small text-muted mt-2">Su Respuesta <span class="text-danger">*</span></label>
                                <input type="text" name="respuesta2" class="form-control bg-white shadow-sm" placeholder="Respuesta secreta..." required autocomplete="off">
                            </div>
                        </div>
                        
                        <!-- Botón alineado a la derecha de la sección -->
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary fw-bold btn-sm px-3 shadow-sm">
                                <i class="bi bi-shield-check me-1"></i> Guardar Preguntas
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>

            <!-- Footer genérico solo para cerrar el modal -->
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar Panel
                </button>
            </div>
            
        </div>
    </div>
</div>