@extends('layouts.admin')

@section('header')
<x-page-header title="Programa Nacional de Formación (PNF)">
    <li class="breadcrumb-item"><a href="{{ route('pnfs.index') }}">PNFs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detalle</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- TARJETA DE CABECERA DEL PNF (Ecosystem Card) -->
    <div class="card mb-4 shadow-sm ecosystem-card">
        <div class="card-body p-4 bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">

                <div class="flex-grow-1">
                    <span class="badge {{ $pnf->vigencia_pnf ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-2 fs-6">
                        {{ $pnf->vigencia_pnf ? 'Activo' : 'Inactivo' }}
                    </span>
                    <h3 class="fw-bold text-dark mb-1">{{ $pnf->nombre_pnf }}</h3>
                    <p class="text-muted mb-0">{{ $pnf->descripcion_pnf ?? 'Sin descripción registrada.' }}</p>
                </div>

                <div class="mt-3 mt-md-0 d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('pnfs.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver al Catálogo
                    </a>
                    <button type="button" class="btn btn-primary shadow-sm"
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

    <!-- TARJETA CONTENEDORA DE PESTAÑAS (Ecosystem Card) -->
    <div class="card shadow-sm ecosystem-card">
        <!-- Encabezado de la tarjeta -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">
                 Detalles y Vinculaciones del PNF
            </h4>
        </div>
        
        <!-- Navegación de pestañas unificada -->
        <div class="card-header bg-light pt-2 pb-0 border-top border-bottom">
            <ul class="nav nav-tabs nav-fill card-header-tabs" id="pnfDashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 text-secondary" id="titulos-tab" data-bs-toggle="tab" data-bs-target="#titulos-pane" type="button" role="tab" aria-controls="titulos-pane" aria-selected="true">
                        Títulos Ofertados ({{ $pnf->titulosPnf->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-secondary" id="empresas-tab" data-bs-toggle="tab" data-bs-target="#empresas-pane" type="button" role="tab" aria-controls="empresas-pane" aria-selected="false">
                        Empresas Aliadas / Convenios ({{ $pnf->empresasPnf->count() }})
                    </button>
                </li>
            </ul>
        </div>
        
        <!-- Contenido de las pestañas -->
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
        
        <!-- Footer unificado -->
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Secciones operativas del expediente del PNF.
        </div>
    </div>
</div>

<!-- Incluimos los modales externos para no duplicar código -->
@include('pnfs.partials.modals')

@endsection

@section('styles')
<style>
    /* ---------------------------------------------------
        ESTÉTICA UNIFICADA DEL ECOSISTEMA DE TARJETAS
    ----------------------------------------------------- */
    .ecosystem-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05) !important;
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }

    .ecosystem-card .card-header.bg-white {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .ecosystem-card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom-left-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }

    /* Estética unificada para las pestañas y corrección del solapamiento */
    .card-header-tabs {
        margin-right: 0 !important;
        margin-left: 0 !important;
        margin-bottom: -1px !important;
    }

    .card-header-tabs .nav-link {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.75rem 1rem;
    }

    .card-header-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .card-header-tabs .nav-link.active {
        color: #0d6efd !important;
        background-color: #ffffff !important;
        border-color: #dee2e6 #dee2e6 #ffffff !important;
    }

    /* Blindaje visual para tarjetas internas en los tabs (Partial views) */
    .tab-content .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        background-color: #ffffff !important;
    }

    .tab-content .card .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }

    .tab-content .card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
    }
</style>
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