<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0">Empresas Aliadas con Convenio Vigente</h5>
    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#vincularEmpresaModal">
        <i class="bi bi-plus-circle me-1"></i> Registrar Convenio / Alianza
    </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center nowrap" style="width: 100%;">
        <thead class="table-light">
            <tr>
                <th style="width: 60px;">#</th>
                <th class="text-start">Empresa Alianza</th>
                <th>Tipo de Relación Académica</th>
                <th class="text-start">Observación del Convenio</th>
                <th style="width: 100px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pnf->empresasPnf as $index => $vinculo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start fw-bold text-dark">
                        <i class="bi bi-building me-2 text-muted"></i>{{ $vinculo->empresa->nombre_empresa }}
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border border-secondary px-3 py-2 fw-semibold">
                            {{ $vinculo->tipo_relacion }}
                        </span>
                    </td>
                    <td class="text-start text-wrap" style="max-width: 300px;">
                        <small class="text-muted">{{ $vinculo->observacion_empresa_pnf ?? 'Sin observaciones de convenio.' }}</small>
                    </td>
                    <td>
                        <form action="{{ route('pnfs.empresas.destroy', $vinculo->id_empresa_pnf) }}" method="POST" class="m-0 p-0">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Romper Convenio con Empresa" onclick="return confirm('¿Está seguro de revocar la alianza con esta empresa para este PNF?')">
                                <i class="bi bi-link-45deg"></i> Desvincular
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted py-4">
                        <i class="bi bi-building-dash fs-4 d-block mb-2"></i> No hay convenios institucionales o empresas aliadas asociadas a este PNF actualmente.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="vincularEmpresaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">Establecer Convenio Empresarial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="vincularEmpresaForm" method="POST" action="{{ route('pnfs.empresas.store', $pnf->id_pnf) }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Seleccione la Empresa Aliada <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_empresa" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach($catalogoEmpresas as $emp)
                                <option value="{{ $emp->id_empresa }}">{{ $emp->nombre_empresa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Tipo de Relación o Convenio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tipo_relacion" placeholder="Ej: Pasantías, Proyecto Socio-Integrador" required autocomplete="off">
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label fw-bold small text-muted">Observaciones Adicionales (Opcional)</label>
                        <textarea class="form-control" name="observacion_empresa_pnf" rows="3" placeholder="Detalles de convenios, cantidad de vacantes permitidas, etc."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="vincularEmpresaForm" class="btn btn-primary fw-bold">Registrar Alianza</button>
            </div>
        </div>
    </div>
</div>