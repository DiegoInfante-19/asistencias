@extends('layouts.admin')

@section('header')
<x-page-header title="Programa Nacional de Formación (PNF)">
    <li class="breadcrumb-item"><a href="{{ route('pnfs.index') }}">PNFs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detalle</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- TARJETA DE CABECERA DEL PNF -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 bg-white rounded">
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
                    <button type="button" class="btn btn-warning fw-bold text-dark shadow-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#UpdatePnfModal"
                        data-url="{{ route('pnfs.update', $pnf->id_pnf) }}"
                        data-nombre="{{ $pnf->nombre_pnf }}"
                        data-vigencia="{{ $pnf->vigencia_pnf ? 1 : 0 }}"
                        data-descripcion="{{ $pnf->descripcion_pnf }}">
                        <i class="bi bi-pencil-square me-1"></i> Editar Datos
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- TARJETA CONTENEDORA DE PESTAÑAS (TÍTULOS Y EMPRESAS) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <ul class="nav nav-tabs nav-fill card-header-tabs" id="pnfDashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 text-secondary" id="titulos-tab" data-bs-toggle="tab" data-bs-target="#titulos-pane" type="button" role="tab" aria-controls="titulos-pane" aria-selected="true">
                        <i class="bi bi-mortarboard fs-5 me-2 text-primary"></i> Títulos Ofertados ({{ $pnf->titulosPnf->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-secondary" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas-pane" type="button" role="tab" aria-controls="empresas-pane" aria-selected="false">
                        <i class="bi bi-building fs-5 me-2 text-primary"></i> Empresas Aliadas / Convenios ({{ $pnf->empresasPnf->count() }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body bg-white p-4">
            <div class="tab-content" id="pnfDashboardTabsContent">
                <div class="tab-pane fade show active" id="titulos-pane" role="tabpanel" aria-labelledby="titulos-tab" tabindex="0">
                    @include('pnfs.partials.tab_titulos')
                </div>

                <div class="tab-pane fade" id="empresas-pane" role="tabpanel" aria-labelledby="empresas-tab" tabindex="0">
                    @include('pnfs.partials.tab_empresas')
                </div>
            </div>
        </div>
        <div class="card-footer bg-light py-2 text-muted small">
            Secciones operativas del expediente del PNF.
        </div>
    </div>
</div>

<!-- Incluimos los modales externos para no duplicar código -->
@include('pnfs.partials.modals')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let updateModal = document.getElementById('UpdatePnfModal');
        if (updateModal) {
            updateModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var modal = this;
                
                modal.querySelector('#UpdatePnfForm').setAttribute('action', button.getAttribute('data-url'));
                modal.querySelector('#edit-nombre-pnf').value = button.getAttribute('data-nombre');
                modal.querySelector('#edit-descripcion-pnf').value = button.getAttribute('data-descripcion');
                
                let vigenciaSelect = modal.querySelector('#edit-vigencia-pnf');
                vigenciaSelect.value = button.getAttribute('data-vigencia');
                
                if (typeof window.$ !== 'undefined') {
                    let $vigenciaSelect = $(vigenciaSelect);
                    if ($vigenciaSelect.data('select2')) { 
                        $vigenciaSelect.trigger('change'); 
                    }
                }
            });
        }
    });

    document.addEventListener('submit', function(event) {
        if (event.target && event.target.classList.contains('form-desvincular-titulo')) {
            event.preventDefault();
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
                if (result.isConfirmed) { event.target.submit(); }
            });
        }

        if (event.target && event.target.classList.contains('form-desvincular-empresa')) {
            event.preventDefault();
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
                if (result.isConfirmed) { event.target.submit(); }
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof window.bootstrap !== 'undefined') {
            let activeTabId = localStorage.getItem('pnf_dashboard_active_tab');
            if (activeTabId) {
                let tabElement = document.getElementById(activeTabId);
                if(tabElement) {
                    let tab = new bootstrap.Tab(tabElement);
                    tab.show();
                }
            }

            let tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabElements.forEach(function(tab) {
                tab.addEventListener('shown.bs.tab', function(event) {
                    localStorage.setItem('pnf_dashboard_active_tab', event.target.id);
                });
            });
        }
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endpush