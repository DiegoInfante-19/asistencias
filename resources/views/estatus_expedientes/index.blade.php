@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title fw-bold text-dark mb-0">Catálogo: Estatus de Expedientes</h5>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createEstatusModal">
                <i class="bi bi-folder-plus me-1"></i> <b>Nuevo Estatus</b>
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('estatus_expedientes.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        // Llenado dinámico del modal de edición
        $('#UpdateEstatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que disparó el modal
            var modal = $(this);

            // Actualizar la ruta del formulario (action)
            modal.find('#UpdateEstatusForm').attr('action', button.data('url'));

            // Inyectar el nombre del estatus en el input
            modal.find('#edit-nombre-estatus').val(button.data('nombre'));
        });
    });
</script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection