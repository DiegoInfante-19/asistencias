@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- TARJETA DE FILTROS AVANZADOS -->
    <div class="card border shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fw-bold fs-6">
                <i class="bi bi-funnel-fill text-primary me-2"></i> Filtros Avanzados de Búsqueda
            </h5>
            <button class="btn btn-sm btn-outline-secondary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="true" aria-controls="collapseFiltros">
                <i class="bi bi-chevron-down"></i> Ocultar / Mostrar
            </button>
        </div>
        <div class="collapse show" id="collapseFiltros">
            <div class="card-body bg-light p-4">
                <div class="row g-3">
                    
                    <!-- 1. Filtro por PNF -->
                    <div class="col-md-3">
                        <label for="filtro_pnf" class="form-label fw-bold small text-dark">PNF Asignado</label>
                        <select id="filtro_pnf" class="form-select select2-buscador">
                            <option value="" selected>Todos los PNF</option>
                            @if(isset($pnfs))
                                @foreach($pnfs as $pnf)
                                    <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- 2. Filtro por Sección -->
                    <div class="col-md-3">
                        <label for="filtro_seccion" class="form-label fw-bold small text-dark">Sección Específica</label>
                        <select id="filtro_seccion" class="form-select select2-buscador">
                            <option value="" selected>Todas las Secciones</option>
                            @if(isset($secciones))
                                @foreach($secciones as $seccion)
                                    <option value="{{ $seccion->id_seccion }}">{{ $seccion->nombre_seccion }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- 3. Filtro por Rol -->
                    <div class="col-md-3">
                        <label for="filtro_rol" class="form-label fw-bold small text-dark">Rol de Usuario</label>
                        <select id="filtro_rol" class="form-select select2-buscador">
                            <option value="" selected>Todos los Roles</option>
                            @if(isset($roles))
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol ?? $rol->name_role ?? 'Rol #' . $rol->id_rol }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- 4. Filtro por Estatus -->
                    <div class="col-md-3">
                        <label for="filtro_estatus" class="form-label fw-bold small text-dark">Estatus del Usuario</label>
                        <select id="filtro_estatus" class="form-select select2-buscador">
                            <option value="" selected>Todos los Estatus</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- 5. Filtro por Sección Activa -->
                    <div class="col-md-3">
                        <label for="filtro_seccion_activa" class="form-label fw-bold small text-dark">Sección Activa</label>
                        <select id="filtro_seccion_activa" class="form-select select2-buscador">
                            <option value="" selected>Indiferente</option>
                            <option value="1">Sí (En secciones activas)</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- 6. Filtro Booleano: Tiene Sección -->
                    <div class="col-md-3">
                        <label for="filtro_tiene_seccion" class="form-label fw-bold small text-dark">Asignación de Sección</label>
                        <select id="filtro_tiene_seccion" class="form-select select2-buscador">
                            <option value="" selected>Indiferente</option>
                            <option value="1">Tiene Sección Asignada</option>
                            <option value="0">No tiene Sección</option>
                        </select>
                    </div>

                    <!-- 7. Filtro Booleano: Tiene PNF -->
                    <div class="col-md-3">
                        <label for="filtro_tiene_pnf" class="form-label fw-bold small text-dark">Asignación de PNF</label>
                        <select id="filtro_tiene_pnf" class="form-select select2-buscador">
                            <option value="" selected>Indiferente</option>
                            <option value="1">Tiene PNF Asignado</option>
                            <option value="0">No tiene PNF</option>
                        </select>
                    </div>

                    <!-- 8. Filtro por Nivel Asignado -->
                    <div class="col-md-3">
                        <label for="filtro_nivel" class="form-label fw-bold small text-dark">Nivel Asignado</label>
                        <select id="filtro_nivel" class="form-select select2-buscador">
                            <option value="" selected>Todos los Niveles</option>
                            <option value="TSU">TSU</option>
                            <option value="Ingeniería">Ingeniería</option>
                        </select>
                    </div>

                </div>

                <!-- Botón de Reseteo de Filtros -->
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button type="button" id="btnResetFiltros" class="btn btn-outline-secondary btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tarjeta Principal con diseño limpio -->
    <div class="card border shadow-sm">

        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h4 class="card-title text-dark mb-0 fw-bold fs-6">
                <i class="bi bi-people-fill text-primary me-2"></i> Personal y Profesores Registrados
            </h4>
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createUserModal">
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
    /* Asegurar que Select2 luzca perfectamente integrado con Bootstrap 5 */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
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

        // 2. REAJUSTE DE DATATABLES (Responsive seguro para evitar error de recalc)
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
            $('.select2-buscador').val(null).trigger('change');
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