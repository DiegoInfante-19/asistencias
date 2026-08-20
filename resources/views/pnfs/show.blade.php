@extends('layouts.app')

@section('header')
<x-page-header title="Programa Nacional de Formación (PNF)">
    <li class="breadcrumb-item"><a href="{{ route('pnfs.index') }}">PNFs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detalle</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">

                <div class="flex-grow-1">
                    <span class="badge {{ $pnf->vigencia_pnf ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-2 fs-6">
                        {{ $pnf->vigencia_pnf ? 'Activo' : 'Inactivo' }}
                    </span>
                    <h3 class="fw-bold text-dark mb-1">{{ $pnf->nombre_pnf }}</h3>
                    <p class="text-muted mb-0">{{ $pnf->descripcion_pnf ?? 'Sin descripción registrada.' }}</p>
                </div>

                <div class="mt-3 mt-md-0 d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('pnfs.index') }}" class="btn btn-outline-secondary fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Volver al Catálogo
                    </a>
                    <button type="button" class="btn btn-warning fw-bold text-dark"
                        data-bs-toggle="modal"
                        data-bs-target="#UpdatePnfModal"
                        data-url="{{ route('pnfs.update', $pnf->id_pnf) }}"
                        data-nombre="{{ $pnf->nombre_pnf }}"
                        data-vigencia="{{ $pnf->vigencia_pnf ? 1 : 0 }}"
                        data-descripcion="{{ $pnf->descripcion_pnf }}">
                        <i class="bi bi-pencil me-1"></i> Editar Datos
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <ul class="nav nav-tabs nav-fill card-header-tabs" id="pnfDashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 text-secondary" id="titulos-tab" data-bs-toggle="tab" data-bs-target="#titulos-pane" type="button" role="tab" aria-controls="titulos-pane" aria-selected="true">
                        <i class="bi bi-mortarboard fs-5 me-2"></i> Títulos Ofertados ({{ $pnf->titulosPnf->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-secondary" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas-pane" type="button" role="tab" aria-controls="empresas-pane" aria-selected="false">
                        <i class="bi bi-building fs-5 me-2"></i> Empresas Aliadas / Convenios ({{ $pnf->empresasPnf->count() }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="pnfDashboardTabsContent">
                <div class="tab-pane fade show active" id="titulos-pane" role="tabpanel" aria-labelledby="titulos-tab" tabindex="0">
                    @include('pnfs.partials.tab_titulos')
                </div>

                <div class="tab-pane fade" id="empresas-pane" role="tabpanel" aria-labelledby="empresas-tab" tabindex="0">
                    @include('pnfs.partials.tab_empresas')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdatePnfModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Modificar PNF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdatePnfForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="origen" value="update_pnf_show">

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Nombre del PNF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_pnf" id="edit-nombre-pnf" required autocomplete="off">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Vigencia <span class="text-danger">*</span></label>
                        <select class="form-select" name="vigencia_pnf" id="edit-vigencia-pnf" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea class="form-control" name="descripcion_pnf" id="edit-descripcion-pnf" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="UpdatePnfForm" class="btn btn-warning fw-bold text-dark">Actualizar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Inicializar datos del modal de edición
        $('#UpdatePnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdatePnfForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-pnf').val(button.data('nombre'));
            modal.find('#edit-vigencia-pnf').val(button.data('vigencia'));
            modal.find('#edit-descripcion-pnf').val(button.data('descripcion'));
        });
    });

    $(document).ready(function() {
        // Inicializar datos del modal de edición
        $('#UpdatePnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdatePnfForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-pnf').val(button.data('nombre'));
            modal.find('#edit-vigencia-pnf').val(button.data('vigencia'));
            modal.find('#edit-descripcion-pnf').val(button.data('descripcion'));
        });
    });

    // Delegación de eventos para Confirmación de SweetAlert2
    document.addEventListener('submit', function(event) {

        // 1. Interceptar eliminación de Títulos
        if (event.target && event.target.classList.contains('form-desvincular-titulo')) {
            event.preventDefault(); // Detenemos el envío inmediato

            Swal.fire({
                title: '¿Desvincular Título?',
                text: "¿Está seguro de retirar este título del programa de formación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-link-45deg me-1"></i> Sí, desvincular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Enviamos el formulario
                }
            });
        }

        // 2. Interceptar eliminación de Empresas
        if (event.target && event.target.classList.contains('form-desvincular-empresa')) {
            event.preventDefault(); // Detenemos el envío inmediato

            Swal.fire({
                title: '¿Revocar Alianza?',
                text: "¿Está seguro de romper el convenio con esta empresa para este PNF?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-link-45deg me-1"></i> Sí, revocar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Enviamos el formulario
                }
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        // A) Leer si hay una pestaña guardada previamente en la memoria del navegador
        let activeTabId = localStorage.getItem('pnf_dashboard_active_tab');

        if (activeTabId) {
            let tabElement = document.getElementById(activeTabId);
            if (tabElement) {
                // Instanciar y mostrar la pestaña usando la API nativa de Bootstrap 5
                let tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }

        // B) Detectar cuando el usuario cambia de pestaña y guardar su ID
        let tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabElements.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(event) {
                // event.target es el botón de la pestaña que acaba de activarse
                localStorage.setItem('pnf_dashboard_active_tab', event.target.id);
            });
        });
    });
</script>
@endsection