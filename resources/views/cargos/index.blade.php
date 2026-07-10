@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('header')
    <x-page-header title="Catálogo de Cargos">
        <li class="breadcrumb-item active" aria-current="page">Cargos</li>
    </x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title fw-bold text-dark mb-0">Catálogo de Cargos</h5>
            <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createCargoModal">
                <i class="bi bi-briefcase me-1"></i> <b>Nuevo Cargo</b>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap dt-responsive', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('cargos.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}

<script>
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

@endsection