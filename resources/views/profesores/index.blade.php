@extends('layouts.admin')

@section('styles')
    <style>
        /* Ajustes menores para que el buscador y la paginación se alineen con AdminLTE */
        div.dataTables_wrapper div.dataTables_filter {
            text-align: right;
            margin-bottom: 15px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
        }
    </style>
@endsection

@section('content')
<div class="content" style="margin: 20px;">
    <div class="row">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="card-title fw-bold text-dark mb-0">Personal y Profesores Registrados</h3>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Yajra dibuja el esqueleto HTML de la tabla automáticamente -->
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle w-100']) !!}
                </div>
            </div>
            
            <div class="card-footer bg-white text-muted small py-3">
                Procesamiento en tiempo real activo desde el servidor.
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Cargamos exclusivamente el archivo JavaScript complementario del paquete de tu cabecera -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.8/datatables.min.js" crossorigin="anonymous"></script>
    <!-- La inicialización automática de Yajra -->
    {!! $dataTable->scripts() !!}
@endsection