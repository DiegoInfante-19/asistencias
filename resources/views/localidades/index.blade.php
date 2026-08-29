@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card border-0 shadow-sm">
        
        <!-- Cabecera Principal -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Registro de Localidades
            </h5>
        </div>

        <!-- Navegación de Pestañas con estilo unificado -->
        <div class="card-header bg-light pt-2 pb-0 border-top border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="localidadTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-dark" data-bs-toggle="tab" data-bs-target="#tab-estados" type="button" role="tab" style="font-weight: 500;">
                        Estados
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-dark" data-bs-toggle="tab" data-bs-target="#tab-ciudades" type="button" role="tab" style="font-weight: 500;">
                        Ciudades
                    </button>
                </li>
            </ul>
        </div>

        <!-- Cuerpo con fondo blanco puro -->
        <div class="card-body bg-white py-4">
            <div class="tab-content" id="localidadTabContent">
                
                <!-- TAB ESTADOS -->
                <div class="tab-pane fade show active" id="tab-estados" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4" >
                        <h6 class="text-secondary mb-0 " style="font-weight: 500;">Directorio de Estados</h6>
                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createEstateModal">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Estado
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

                <!-- TAB CIUDADES -->
                <div class="tab-pane fade" id="tab-ciudades" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-secondary mb-0 " style="font-weight: 500;">Directorio de Ciudades</h6>
                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createCiudadModal">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Ciudad
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $ciudadesTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('localidades.partials.modals')
@endsection

@section('styles')
<style>
    /* Estética limpia para las pestañas y corrección del solapamiento */
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
        padding: 0.5rem 1rem;
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
</style>
@endsection

@push('scripts')
<!-- Script modular para manejar modales y cambio de pestañas -->
<script type="module">
    $(document).ready(function() {
        // --- 1. Lógica para editar Estados ---
        $('#UpdateEstateModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateEstateForm').attr('action', button.data('url'));
            modal.find('#edit-id-estado').val(button.data('id'));
            modal.find('#edit-nombre-estado').val(button.data('nombre'));
            modal.find('input').trigger('input');
        });

        // --- 2. Lógica para editar Ciudades ---
        $('#UpdateCiudadModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCiudadForm').attr('action', button.data('url'));
            modal.find('#edit-id-ciudad').val(button.data('id'));
            modal.find('#edit-nombre-ciudad').val(button.data('nombre'));
            modal.find('#edit-id-estado-ciudad').val(button.data('id-estado'));
            modal.find('input, select').trigger('input');
        });

        // Aseguramos que las tablas DataTable se redibujen correctamente al alternar entre pestañas
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- Scripts de Yajra configurados para ESM -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
{!! $ciudadesTable->scripts(null, ['type' => 'module']) !!}
@endpush