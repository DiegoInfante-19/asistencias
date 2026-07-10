@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" crossorigin="anonymous">
@endsection

@section('content')
<div class="content" style="margin: 20px;">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="localidadTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-estados" type="button">Estados</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ciudades" type="button">Ciudades</button>
                </li>
            </ul>

            <div class="tab-content pt-4">
                <div class="tab-pane fade show active" id="tab-estados">
                    
                    <div class="card-header bg-white py-3 d-flex align-items-center">
                        <h4 class="card-title fw-bold text-dark mb-0">Gestión de Estados</h4>
                        <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createEstateModal">
                            <i class="bi bi-plus-lg"></i> <b>Nuevo Estado</b>
                        </button>
                    </div>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-ciudades">
                    <div class="card-header bg-white py-3 d-flex align-items-center">
                        <h4 class="card-title fw-bold text-dark mb-0">Gestión de Ciudades</h4>
                        <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#createCiudadModal">
                            <i class="bi bi-plus-lg"></i> <b>Nueva Ciudad</b>
                        </button>
                    </div>
                    <div class="table-responsive">
                        {!! $ciudadesTable->table(['class' => 'table table-bordered table-striped table-hover align-middle nowrap', 'style' => 'width:100%;']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('localidades.partials.modals')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js" crossorigin="anonymous"></script>

{!! $dataTable->scripts() !!}
{!! $ciudadesTable->scripts() !!}

<script>
    $(document).ready(function() {
        $('#UpdateEstateModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateEstateForm').attr('action', button.data('url'));
            modal.find('#edit-id-estado').val(button.data('id'));
            modal.find('#edit-nombre-estado').val(button.data('nombre'));
            modal.find('input').trigger('input');
        });

        // --- 2. Lógica para editar Ciudades ---
        $('#UpdateCiudadModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdateCiudadForm').attr('action', button.data('url'));
            modal.find('#edit-id-ciudad').val(button.data('id'));
            modal.find('#edit-nombre-ciudad').val(button.data('nombre'));
            modal.find('#edit-id-estado-ciudad').val(button.data('id-estado'));
            modal.find('input, select').trigger('input');
        });
        // Aseguramos que las tablas se ajusten al cambiar de tab
        //    @if($errors->any())
        //         @if(old('origen') == 'create_estado')
        //             new bootstrap.Modal(document.getElementById('createEstateModal')).show();
        //         @elseif(old('origen') == 'create_ciudad')
        //             new bootstrap.Modal(document.getElementById('createCiudadModal')).show();
        //         @elseif(old('_method') == 'PUT')
        //             // Si el error viene de una edición, deberás reabrir el modal que corresponda
        //             // Puedes identificarlo verificando qué campos tienen error
        //         @endif
        //     @endif

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        });

    });
</script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>
@endsection