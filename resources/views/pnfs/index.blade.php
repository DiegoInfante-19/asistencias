@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title fw-bold text-dark mb-0">Programas Nacionales de Formación (PNF)</h5>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createPnfModal">
                <i class="bi bi-person-plus-fill me-1"></i><b>Nuevo PNF</b>
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle', 'style' => 'width:100%;']) !!} </div>
        </div>
    </div>
</div>

@include('pnfs.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        // Llenar el modal de edición dinámicamente
        $('#UpdatePnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#UpdatePnfForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-pnf').val(button.data('nombre'));
            modal.find('#edit-descripcion-pnf').val(button.data('descripcion'));
            modal.find('#edit-vigencia-pnf').val(button.data('vigencia'));
        });
        $('#showPnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            // Asignar Nombre
            modal.find('#show-nombre-pnf').text(button.data('nombre'));

            // Asignar Descripción (o un texto por defecto si está vacía)
            var descripcion = button.data('descripcion');
            modal.find('#show-descripcion-pnf').text(descripcion ? descripcion : 'Sin descripción registrada.');

            // Asignar Vigencia como un Badge visual
            var vigencia = button.data('vigencia');
            var badge = (vigencia == 1) ?
                '<span class="badge bg-success px-3 py-2">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2">Inactivo</span>';
            modal.find('#show-vigencia-pnf').html(badge);
        });
    });
</script>
@endsection