@extends('layouts.admin')

@section('header')
    <x-page-header title="Gestión de Localidades">
        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Localidades</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card border-0 shadow-sm">
        <div class="card-body bg-white">
            <!-- Pestañas de Navegación -->
            <ul class="nav nav-tabs mb-4" id="localidadTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#tab-estados" type="button" role="tab">Estados</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tab-ciudades" type="button" role="tab">Ciudades</button>
                </li>
            </ul>

            <div class="tab-content pt-2">
                <!-- TAB ESTADOS -->
                <div class="tab-pane fade show active" id="tab-estados" role="tabpanel">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Gestión de Estados</h4>
                        <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createEstateModal">
                            <i class="bi bi-plus-lg me-1" style="font-weight: 500;"></i> Añadir Estado
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

                <!-- TAB CIUDADES -->
                <div class="tab-pane fade" id="tab-ciudades" role="tabpanel">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Gestión de Ciudades</h4>
                        <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createCiudadModal">
                            <i class="bi bi-plus-lg me-1" style="font-weight: 500;"></i> Añadir Ciudad
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