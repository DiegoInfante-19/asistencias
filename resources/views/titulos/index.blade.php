@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Catálogo de Títulos</h5>
            
            <button type="button" class="btn btn-outline-secondary btn-tabla" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" data-bs-toggle="modal" data-bs-target="#createTituloModal">
                <i class="bi bi-mortarboard-fill me-1"></i> <b>Nuevo Título</b>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>
@include('titulos.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
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
@endsection