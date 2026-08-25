@extends('layouts.admin')

{{-- ELIMINAMOS @section('styles') PARA USAR LOS ESTILOS LOCALES DE VITE --}}

@section('header')
<x-page-header title="Catálogo de Títulos">
    <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Títulos</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Catálogo de Títulos</h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createTituloModal">
                <i class="bi bi-mortarboard-fill me-1" style="font-weight: 500;"></i> Añadir Título
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
@include('titulos.partials.modals')
@endsection

@push('scripts')
{{-- ELIMINAMOS LOS CDNS DE JQUERY Y DATATABLES --}}

<!-- Script local envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateTituloModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateTituloForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-titulo').val(button.data('nombre'));
            modal.find('#edit-nivel-titulo').val(button.data('nivel'));
        });
    });
</script>

<!-- Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush