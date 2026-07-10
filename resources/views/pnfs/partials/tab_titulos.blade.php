<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0">Títulos de Pregrado y Postgrado Vinculados</h5>
    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#vincularTituloModal">
        <i class="bi bi-plus-circle me-1"></i> Vincular Nuevo Título
    </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center nowrap" style="width: 100%;">
        <thead class="table-light">
            <tr>
                <th style="width: 60px;">#</th>
                <th>Título Base (Catálogo)</th>
                <th>Nombre de Certificación Específica del PNF</th>
                <th style="width: 100px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pnf->titulosPnf as $index => $vinculo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start">
                        <span class="badge bg-secondary me-2">{{ $vinculo->titulo->nivel_academico }}</span>
                        {{ $vinculo->titulo->nombre_titulo_base }}
                    </td>
                    <td class="text-start fw-semibold text-primary">{{ $vinculo->nombre_titulo_pnf }}</td>
                    <td>
                        <form action="{{ route('pnfs.titulos.destroy', $vinculo->id_titulos_pnf) }}" method="POST" class="m-0 p-0 form-desvincular-titulo">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-accion-desvinculacion btn-outline-secondary" title="Retirar Título del PNF">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-muted py-4">
                        <i class="bi bi-info-circle fs-4 d-block mb-2"></i> No hay títulos universitarios asociados a este programa de formación actualmente.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="vincularTituloModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">Vincular Título Base</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="vincularTituloForm" method="POST" action="{{ route('pnfs.titulos.store', $pnf->id_pnf) }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Seleccione el Título Base <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_titulo" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach($catalogoTitulos as $cat)
                                <option value="{{ $cat->id_titulos }}">[{{ $cat->nivel_academico }}] - {{ $cat->nombre_titulo_base }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label fw-bold small text-muted">Nombre Específico del Título Impreso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_titulo_pnf" placeholder="Ej: Ingeniero en Informática" required autocomplete="off">
                        <div class="form-text">Asigne el nombre literal que tendrá el egresado en su expediente académico.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="vincularTituloForm" class="btn btn-primary fw-bold">Asociar Título</button>
            </div>
        </div>
    </div>
</div>