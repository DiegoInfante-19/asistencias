@extends('layouts.admin')

@section('header')
    <x-page-header title="Catálogo: Estatus de Expedientes">
        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Estatus de Expedientes</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Catálogo: Estatus de Expedientes</h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createEstatusModal">
                <i class="bi bi-folder-plus me-1" style="font-weight: 500;"></i> Añadir Estatus
            </button>
        </div>
        
        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('estatus_expedientes.partials.modals')
@endsection

@push('scripts')
<!-- 1. Script de lógica local envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        // Llenado dinámico del modal de edición
        $('#UpdateEstatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            
            // Actualizar la ruta del formulario (action)
            modal.find('#UpdateEstatusForm').attr('action', button.data('url'));
            
            // Inyectar el nombre del estatus en el input
            modal.find('#edit-nombre-estatus').val(button.data('nombre'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización modular de Yajra DataTables -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush