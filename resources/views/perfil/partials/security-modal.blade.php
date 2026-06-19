<div class="modal fade" id="securityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check text-primary"></i> Seguridad de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="formPassword" action="{{ route('seguridad.password.update') }}" method="POST">
                    @csrf @method('PUT')
                    <h6 class="fw-bold border-bottom pb-2">Cambiar Contraseña</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small">Actual</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Nueva</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Confirmar</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </form>

                <form id="formSecurity" action="{{ route('seguridad.preguntas.update') }}" method="POST">
                    @csrf @method('PUT')
                    <h6 class="fw-bold border-bottom pb-2">Preguntas de Seguridad</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Pregunta 1</label>
                            <select name="pregunta1" class="form-select">
                                @foreach(\App\Models\PreguntaSecreta::listaPreguntas1() as $id => $pregunta)
                                    <option value="{{ $id }}">{{ $pregunta }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="respuesta1" class="form-control mt-2" placeholder="Respuesta..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Pregunta 2</label>
                            <select name="pregunta2" class="form-select">
                                @foreach(\App\Models\PreguntaSecreta::listaPreguntas2() as $id => $pregunta)
                                    <option value="{{ $id }}">{{ $pregunta }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="respuesta2" class="form-control mt-2" placeholder="Respuesta..." required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formPassword" class="btn btn-warning">Actualizar Clave</button>
                <button type="submit" form="formSecurity" class="btn btn-primary">Guardar Preguntas</button>
            </div>
        </div>
    </div>
</div>
