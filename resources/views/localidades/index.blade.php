@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño unificado (Ecosystem Card) -->
    <div class="card shadow-sm ecosystem-card">
        
        <!-- Cabecera Principal y Navegación de Pestañas Integradas -->
        <div class="card-header bg-white pt-3 pb-0 px-0 border-bottom-0">
            <!-- Título -->
            <div class="d-flex align-items-center px-4 pb-3">
                <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                    Registro de Localidades
                </h5>
            </div>

            <!-- Navegación de Pestañas unida a la cabecera -->
            <div class="bg-light px-4 pt-2 border-top border-bottom">
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
        </div>

        <!-- Cuerpo con fondo blanco puro -->
        <div class="card-body bg-white p-4">
            <div class="tab-content" id="localidadTabContent">
                
                <!-- TAB ESTADOS -->
                <div class="tab-pane fade show active" id="tab-estados" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-secondary mb-0" style="font-weight: 500;">Directorio de Estados</h6>
                        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createEstateModal">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Estado
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100 border', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

                <!-- TAB CIUDADES -->
                <div class="tab-pane fade" id="tab-ciudades" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-secondary mb-0" style="font-weight: 500;">Directorio de Ciudades</h6>
                        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createCiudadModal">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Ciudad
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $ciudadesTable->table(['class' => 'table table-striped table-hover align-middle w-100 border', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Footer añadido para cerrar el diseño de la tarjeta -->
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Gestión geográfica de estados y ciudades.
        </div>
    </div>
</div>

@include('localidades.partials.modals')
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

    .ecosystem-card .card-header {
        background-color: #ffffff !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }

    .ecosystem-card .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom-left-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }

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