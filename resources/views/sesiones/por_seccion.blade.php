@extends('layouts.admin')

@section('header')
<x-page-header title="Historial de Sesiones">
    <li class="breadcrumb-item"><a href="{{ route('clases.secciones.index') }}">Directorio de Secciones</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sección: {{ $seccion->nombre_seccion }}</li>
</x-page-header>
@endsection

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- ENCABEZADO Y DATOS DE LA SECCIÓN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-easel2-fill me-2 text-primary"></i>Sección: {{ $seccion->nombre_seccion }}
            </h4>
            <p class="text-muted small mb-0">
                <strong>PNF:</strong> {{ $seccion->pnf->nombre_pnf ?? 'N/D' }} | 
                <strong>{{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }}</strong> | 
                <strong>Estatus:</strong> <span class="badge bg-success">{{ $seccion->estatus_seccion }}</span>
            </p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('clases.secciones.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Directorio
            </a>

            @can('create', App\Models\Sesion::class)
            <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="abrirModalCrear()">
                <i class="bi bi-calendar-plus me-1"></i> Programar Clase
            </button>
            @endcan
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON DATATABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom">
            <h5 class="card-title text-dark mb-3 mb-md-0 fs-5" style="font-weight: 500;">
                Clases Programadas en esta Sección
            </h5>
            <!-- FILTRO DE ASISTENCIA -->
            <div class="d-flex align-items-center" style="min-width: 250px;">
                <label for="filtro_asistencia" class="form-label me-2 mb-0 fw-bold small text-muted text-nowrap">Filtrar por:</label>
                <select id="filtro_asistencia" class="form-select form-select-sm shadow-sm border-secondary-subtle">
                    <option value="">Todas las clases</option>
                    <option value="registrada">Asistencia Registrada</option>
                    <option value="pendiente">Asistencia Pendiente</option>
                </select>
            </div>
        </div>
        
        <div class="card-body bg-white py-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
            Historial de sesiones académicas registradas para la sección {{ $seccion->nombre_seccion }}.
        </div>
    </div>

</div>

<!-- Incluir el Modal Único de Sesión -->
@include('sesiones.partials.modal_sesion')
@endsection

@section('styles')
<style>
    /* Estética unificada para Select2 dentro del modal */
    .modal-body .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
    }
</style>
@endsection

@push('scripts')
{!! $dataTable->scripts(null, ['type' => 'module']) !!}

<script type="module">
    let flatpickrInstance = null;

    $(document).ready(function() {
        // Escuchar cambios en el selector de filtro de la tabla
        $('#filtro_asistencia').on('change', function() {
            if (window.LaravelDataTables && window.LaravelDataTables['sesiones-seccion-table']) {
                window.LaravelDataTables['sesiones-seccion-table'].draw();
            } else if ($.fn.DataTable.isDataTable('#sesiones-seccion-table')) {
                $('#sesiones-seccion-table').DataTable().draw();
            }
        });

        // Configuración de periodos de receso y bloqueo de días distintos al miércoles
        const recesosDB = {!! json_encode($periodosRecesos ?? [], JSON_HEX_TAG) !!};
        let bloqueosFlatpickr = recesosDB.map(receso => {
            return {
                from: receso.fecha_inicio_periodo_receso.split('T')[0], 
                to: receso.fecha_fin_periodo_receso.split('T')[0]
            };
        });

        bloqueosFlatpickr.push(function(date) {
            return (date.getDay() !== 3); 
        });

        // Inicializar Flatpickr de manera local y segura
        if (typeof window.flatpickr !== 'undefined') {
            flatpickrInstance = window.flatpickr("#modal_fecha_sesion", {
                locale: window.Spanish || "es", 
                dateFormat: "Y-m-d", 
                maxDate: "today", 
                disable: bloqueosFlatpickr,
                allowInput: false
            });
        }

        // Inicializar Select2 en el modal cuando se abra (evita problemas de z-index y focus)
        $('#modalSesion').on('shown.bs.modal', function () {
            let $modal = $(this);$modal.find('.select2-buscador').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {$(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $modal, // Vital para modales Bootstrap
                        placeholder: 'Seleccione una opción...'
                    });
                }
            });
        });
    });

    window.abrirModalCrear = function() {
        document.getElementById('modalSesionLabel').innerHTML = 'Programar Sesión de Clase';
        document.getElementById('btnText').innerText = 'Programar Clase';
        document.getElementById('formSesion').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('formSesion').action = "{{ route('sesiones.store') }}";
        
        // Limpiar Select2 al crear
        $('#modal_id_profesor').val(null).trigger('change');

        if(flatpickrInstance) {
            flatpickrInstance.set('clickOpens', true);
            flatpickrInstance.clear();
        }
        document.getElementById('modal_fecha_sesion').removeAttribute('readonly');
        document.getElementById('alertaAsistenciaRegistrada').classList.add('d-none');
        document.getElementById('ayuda_fecha').classList.remove('d-none');

        var myModal = new bootstrap.Modal(document.getElementById('modalSesion'));
        myModal.show();
    }

    window.abrirModalEditar = function(sesionData) {
        let sesion = typeof sesionData === 'string' ? JSON.parse(sesionData) : sesionData;

        document.getElementById('modalSesionLabel').innerHTML = 'Editar Sesión de Clase';
        document.getElementById('btnText').innerText = 'Actualizar Clase';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('formSesion').action = `/sesiones/${sesion.id_sesiones}`;

        // Rellenar y disparar el evento change para que Select2 refleje el profesor seleccionado
        $('#modal_id_profesor').val(sesion.id_profesor).trigger('change');
        document.getElementById('modal_observacion_sesion').value = sesion.observacion_sesion || '';
        
        if(flatpickrInstance) {
            flatpickrInstance.setDate(sesion.fecha_sesion, true);
        } else {
            document.getElementById('modal_fecha_sesion').value = sesion.fecha_sesion;
        }

        // Blindaje si ya tiene asistencias registradas
        if (sesion.tiene_asistencia) {
            if(flatpickrInstance) flatpickrInstance.set('clickOpens', false);
            document.getElementById('modal_fecha_sesion').setAttribute('readonly', true);
            document.getElementById('alertaAsistenciaRegistrada').classList.remove('d-none');
            document.getElementById('ayuda_fecha').classList.add('d-none');
        } else {
            if(flatpickrInstance) flatpickrInstance.set('clickOpens', true);
            document.getElementById('modal_fecha_sesion').removeAttribute('readonly');
            document.getElementById('alertaAsistenciaRegistrada').classList.add('d-none');
            document.getElementById('ayuda_fecha').classList.remove('d-none');
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalSesion'));
        myModal.show();
    }

    // Envío del formulario mediante AJAX
    document.getElementById('formSesion').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const url = form.action;
        const method = document.getElementById('formMethod').value;

        let fetchOptions = {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };

        if(method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        fetch(url, fetchOptions)
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if(res.status === 200 && res.body.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalSesion')).hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: res.body.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    if (window.LaravelDataTables && window.LaravelDataTables['sesiones-seccion-table']) {
                        window.LaravelDataTables['sesiones-seccion-table'].draw();
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    text: res.body.message || 'Ocurrió un error de validación.'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: 'No se pudo procesar la solicitud.'
            });
        });
    });
</script>
@endpush