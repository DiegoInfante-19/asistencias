@extends('layouts.admin')

@section('header')
    <x-page-header title="Catálogo de Cargos">
        <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Cargos</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Catálogo de Cargos</h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createCargoModal">
                <i class="bi bi-briefcase me-1" style="font-weight: 500;"></i> Añadir Cargo
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

@include('cargos.partials.modals')
@endsection

@push('scripts')
<!-- 1. Script del Modal envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateCargoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCargoForm').attr('action', button.data('url'));
            modal.find('#edit-descripcion-cargo').val(button.data('descripcion'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Inicialización modular de Yajra DataTables -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush