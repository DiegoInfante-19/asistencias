@extends('layouts.admin')

@section('header')
<x-page-header title="Períodos Académicos">
    <li class="breadcrumb-item active" aria-current="page">Gestión de Períodos</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0 fw-bold"><i class="bi bi-calendar-range me-2 text-primary"></i>Catálogo de Períodos Académicos</h4>
            <button type="button" class="btn btn-primary ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#createPeriodoModal">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Período
            </button>
        </div>

        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>

</div>

<!-- INCLUIMOS LOS MODALES -->
@include('periodos_academicos.partials.modals')

@endsection

@push('scripts')
<!-- Lógica para poblar el modal de Edición al hacer clic -->
<script type="module">
    $(document).ready(function() {
        $('#editPeriodoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            
            // Pasamos los datos del data-attribute al formulario
            modal.find('#editPeriodoForm').attr('action', button.data('url'));
            modal.find('#edit_cohorte').val(button.data('cohorte'));
            modal.find('#edit_estatus').val(button.data('estatus'));
            modal.find('#edit_inicio').val(button.data('inicio'));
            modal.find('#edit_fin').val(button.data('fin'));
        });
    });
</script>

<!-- Inicialización de DataTables -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush