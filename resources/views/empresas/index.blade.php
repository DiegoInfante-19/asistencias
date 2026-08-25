@extends('layouts.admin')

@section('header')
<x-page-header title="Catálogo de Empresas">
    <li class="breadcrumb-item active" aria-current="page" style="font-weight: 500;">Empresas</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0" style="font-weight: 500;">Catálogo de Empresas</h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createEmpresaModal">
                <i class="bi bi-person-plus-fill me-1" style="font-weight: 500;"></i> Añadir Empresa
            </button>
        </div>
        
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
    </div>
</div>

@include('empresas.partials.modals')
@endsection

@push('scripts')
<!-- 1. Script del Modal envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        $('#UpdateEmpresaModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#UpdateEmpresaForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-empresa').val(button.data('nombre'));
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- 2. Script de Yajra modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush