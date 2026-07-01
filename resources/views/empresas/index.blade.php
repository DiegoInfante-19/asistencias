@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">
        <div class="card-header bg-white py-3" style="position: relative;">
            <h3 class="card-title fw-bold text-dark mb-0">Catálogo de Empresas</h3>

            <button type="button" class="btn btn-outline-secondary btn-tabla" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" data-bs-toggle="modal" data-bs-target="#createEmpresaModal">
                <i class="bi bi-person-plus-fill me-1"></i><b>Nueva Empresa</b>
            </button>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('empresas.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {
        // Lógica simple para pasar los datos al modal de edición
        $('#UpdateEmpresaModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            
            modal.find('#UpdateEmpresaForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-empresa').val(button.data('nombre'));
        });
    });
</script>
@endsection