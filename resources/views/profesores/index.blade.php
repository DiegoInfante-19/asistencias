@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- TARJETA DE FILTROS AVANZADOS -->
    <div class="card shadow-sm ecosystem-card mb-4">
        <!-- Header estático -->
        <div class="card-header bg-white py-3 border-bottom">
            <h4 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Filtros Avanzados de Búsqueda
            </h4>
        </div>
        
        <!-- Body blanco -->
        <div class="card-body bg-white p-4">
            <div class="row g-4">
                
                <!-- 1. Filtro por PNF -->
                <div class="col-md-3">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted text-uppercase">PNF Asignado</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="" selected>Todos los PNF...</option>
                        @if(isset($pnfs))
                            @foreach($pnfs as $pnf)
                                <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- 2. Filtro por Sección -->
<div class="col-md-3">
    <label for="filtro_seccion" class="form-label fw-bold small text-muted text-uppercase">Sección Específica</label>
    <select id="filtro_seccion" class="form-select select2-buscador">
        <option value="" selected>Todas las Secciones...</option>
        @if(isset($secciones))
            <!-- CORRECCIÓN: Filtramos las secciones en vivo para mostrar solo las activas -->
            @foreach(\App\Models\Seccion::with('pnf')->activasParaAsignacion()->orderBy('nombre_seccion')->get() as $seccion)
                <option value="{{ $seccion->id_seccion }}">
                    {{ $seccion->nombre_seccion }} ({{ $seccion->pnf->nombre_pnf ?? 'Sin PNF' }})
                </option>
            @endforeach
        @endif
    </select>
</div>

                <!-- 3. Filtro por Rol -->
                <div class="col-md-3">
                    <label for="filtro_rol" class="form-label fw-bold small text-muted text-uppercase">Rol de Usuario</label>
                    <select id="filtro_rol" class="form-select select2-buscador">
                        <option value="" selected>Todos los Roles...</option>
                        @if(isset($roles))
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol ?? $rol->name_role ?? 'Rol #' . $rol->id_rol }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- 4. Filtro por Estatus -->
                <div class="col-md-3">
                    <label for="filtro_estatus" class="form-label fw-bold small text-muted text-uppercase">Estatus del Usuario</label>
                    <select id="filtro_estatus" class="form-select select2-buscador">
                        <option value="" selected>Todos los Estatus...</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <!-- 5. Filtro por Sección Activa -->
                <div class="col-md-3">
                    <label for="filtro_seccion_activa" class="form-label fw-bold small text-muted text-uppercase">Sección Activa</label>
                    <select id="filtro_seccion_activa" class="form-select select2-buscador">
                        <option value="" selected>Indiferente...</option>
                        <option value="1">Sí (En secciones activas)</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- 6. Filtro Booleano: Tiene Sección -->
                <div class="col-md-3">
                    <label for="filtro_tiene_seccion" class="form-label fw-bold small text-muted text-uppercase">Asignación de Sección</label>
                    <select id="filtro_tiene_seccion" class="form-select select2-buscador">
                        <option value="" selected>Indiferente...</option>
                        <option value="1">Tiene Sección Asignada</option>
                        <option value="0">No tiene Sección</option>
                    </select>
                </div>

                <!-- 7. Filtro Booleano: Tiene PNF -->
                <div class="col-md-3">
                    <label for="filtro_tiene_pnf" class="form-label fw-bold small text-muted text-uppercase">Asignación de PNF</label>
                    <select id="filtro_tiene_pnf" class="form-select select2-buscador">
                        <option value="" selected>Indiferente...</option>
                        <option value="1">Tiene PNF Asignado</option>
                        <option value="0">No tiene PNF</option>
                    </select>
                </div>

                <!-- 8. Filtro por Nivel Asignado -->
                <div class="col-md-3">
                    <label for="filtro_nivel" class="form-label fw-bold small text-muted text-uppercase">Nivel Asignado</label>
                    <select id="filtro_nivel" class="form-select select2-buscador">
                        <option value="" selected>Todos los Niveles...</option>
                        <option value="TSU">TSU</option>
                        <option value="Ingeniería">Ingeniería</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Footer BLANCO con el botón Limpiar (Alineado a la derecha) -->
        <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
            <button type="button" id="btnResetFiltros" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Personal y Profesores Registrados
            </h5>
            <button type="button" class="btn btn-primary shadow-sm ms-auto" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-person-plus-fill me-1" style="font-weight: 500;"></i> Añadir Profesor
            </button>
        </div>

        <!-- Cuerpo con fondo blanco -->
        <div class="card-body bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-white text-muted small py-3">
            Procesamiento en tiempo real activo desde el servidor.
        </div>

    </div>
</div>

@include('profesores.partials.modals')
@endsection

@section('styles')
<style>
    /* ---------------------------------------------------
       ESTÉTICA UNIFICADA PARA INPUTS Y SELECT2 "SOFT"
    ----------------------------------------------------- */
    
    /* 1. Entradas de texto y selects normales */
    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    /* 2. Forzar a Select2 para que adopte el fondo gris, borde y sombra */
    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
    }

    /* 3. Efecto Focus (Cuando haces clic para escribir/buscar) */
    .card-body.bg-white .form-control:focus,
    .card-body.bg-white .form-select:focus,
    .card-body.bg-white .select2-container--bootstrap-5.select2-container--open .select2-selection {
        background-color: #ffffff !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
</style>
@endsection

@push('scripts')
<!-- Script modular envuelto en type="module" -->
<script type="module">
    $(document).ready(function() {
        // 1. INICIALIZAR SELECT2 EN LOS FILTROS
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            placeholder: 'Seleccione una opción...'
        });

        // 2. REAJUSTE DE DATATABLES
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#users-table')) {
                var table = $('#users-table').DataTable();
                if (table.responsive) {
                    table.columns.adjust().responsive.recalc();
                }
            }
        });

        // 3. INTERCEPTOR AJAX DE DATATABLES PARA ENVIAR FILTROS
        $('#users-table').on('preXhr.dt', function(e, settings, data) {
            data.filtro_pnf            = $('#filtro_pnf').val();
            data.filtro_seccion        = $('#filtro_seccion').val();
            data.filtro_rol            = $('#filtro_rol').val();
            data.filtro_estatus        = $('#filtro_estatus').val();
            data.filtro_seccion_activa = $('#filtro_seccion_activa').val();
            data.filtro_tiene_seccion  = $('#filtro_tiene_seccion').val();
            data.filtro_tiene_pnf      = $('#filtro_tiene_pnf').val();
            data.filtro_nivel          = $('#filtro_nivel').val();
        });

        // 4. DISPARADOR DE RECARGA AL CAMBIAR CUALQUIER FILTRO
        $('#filtro_pnf, #filtro_seccion, #filtro_rol, #filtro_estatus, #filtro_seccion_activa, #filtro_tiene_seccion, #filtro_tiene_pnf, #filtro_nivel').on('change', function() {
            window.LaravelDataTables['users-table'].draw();
        });

        // 5. BOTÓN PARA LIMPIAR / RESETEAR FILTROS
        $('#btnResetFiltros').on('click', function() {
            // Usamos .trigger('change.select2') para evitar múltiples redibujados, y luego un solo draw()
            $('.select2-buscador').val(null).trigger('change.select2');
            window.LaravelDataTables['users-table'].draw();
        });

        // 6. LÓGICA PARA EL MODAL: VER DETALLES
        $('#viewUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#modal-username').text(button.data('username'));
            modal.find('#modal-name').text(button.data('name'));
            modal.find('#modal-lastname').text(button.data('lastname'));
            modal.find('#modal-cedula').text(button.data('cedula'));
            modal.find('#modal-email').text(button.data('email'));
            modal.find('#modal-phone').text(button.data('phone'));
            var status = button.data('status');
            var statusBadge = (status.toLowerCase() === 'activo') ?
                '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">' + status + '</span>';
            modal.find('#modal-status').html(statusBadge);
        });

        // 7. LÓGICA PARA EL MODAL: EDITAR DATOS
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            if (!button.length) return;

            var modal = $(this);
            modal.find('#editUserForm').attr('action', button.data('url'));
            modal.find('#edit_url').val(button.data('url'));
            modal.find('#edit-username').val(button.data('username'));
            modal.find('#edit-name').val(button.data('name'));
            modal.find('#edit-lastname').val(button.data('lastname'));
            modal.find('#edit-cedula').val(button.data('cedula'));
            modal.find('#edit-phone').val(button.data('phone'));
            modal.find('#edit-email').val(button.data('email'));
            modal.find('#edit-status').val(button.data('status'));
            modal.find('#edit-rol').val(button.data('rol'));
            modal.find('input, select').trigger('input');
        });

        // 8. LÓGICA PARA AUTO-ABRIR MODAL TRAS ERROR DE VALIDACIÓN
        @if($errors->any())
            @if(old('origen') == 'modal')
                var createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
                createUserModal.show();
            @elseif(old('_method') == 'PUT')
                $('#editUserForm').attr('action', $('#edit_url').val());
                var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editUserModal.show();
            @endif
        @endif
    });
</script>

<script src="{{ asset('js/core-validations.js') }}" defer></script>
<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- Inicialización de DataTables de forma modular -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush