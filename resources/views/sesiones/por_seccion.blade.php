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
                <strong>PNF:</strong> {{ $seccion->pnfs->isEmpty() ? 'N/D' : $seccion->pnfs->pluck('nombre_pnf')->implode(' / ') }} |
                <strong>{{ $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D' }}</strong> |
                <strong>Estatus:</strong> <span class="badge bg-success">{{ $seccion->estatus_seccion }}</span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('clases.secciones.index') }}" class="btn btn-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Directorio
            </a>

            @can('create', App\Models\Sesion::class)
            <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="abrirModalCrear()">
                <i class="bi bi-calendar-plus me-1"></i> Programar Clase
            </button>
            <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="abrirModalGenerarMasivo()">
                <i class="bi bi-magic me-1"></i> Generación Masiva
            </button>
            @endcan
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON DATATABLE / TARJETAS MÓVILES -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between border-bottom">
            <h5 class="card-title text-dark mb-3 mb-md-0 fs-5" style="font-weight: 500;">
                Clases Programadas en esta Sección
            </h5>
            <!-- FILTRO DE ASISTENCIA -->
            <div class="d-flex align-items-center" style="min-width: 250px;">
                <label for="filtro_asistencia" class="form-label me-2 mb-0 fw-bold small text-muted text-nowrap">Filtrar por:</label>
                <select id="filtro_asistencia" class="form-select form-select-sm shadow-sm border-secondary-subtle">
                    <option value="" {{ request('filtro_asistencia') == '' ? 'selected' : '' }}>Todas las clases</option>
                    <option value="registrada" {{ request('filtro_asistencia') == 'registrada' ? 'selected' : '' }}>Asistencia Registrada</option>
                    <option value="pendiente" {{ request('filtro_asistencia') == 'pendiente' ? 'selected' : '' }}>Asistencia Pendiente</option>
                </select>
            </div>
        </div>

        <div class="card-body bg-white py-4">

            <!-- MODO COMPUTADORA (TABLA DATATABLES) -->
            <div class="table-responsive d-none d-md-block">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100', 'style' => 'width:100%;']) !!}
            </div>

            <!-- MODO TELÉFONO (TARJETAS TÁCTILES RESPONSIVE) -->
            <div class="d-block d-md-none">
                @php
                $queryMovil = App\Models\Sesion::query()
                ->with(['profesor.user'])
                ->withExists('asistencias')
                ->where('id_seccion', $seccion->id_seccion)
                ->orderBy('fecha_sesion', 'desc');

                if (request()->filled('filtro_asistencia')) {
                    $estado = request('filtro_asistencia');
                    if ($estado === 'registrada') {
                        $queryMovil->has('asistencias');
                    } elseif ($estado === 'pendiente') {
                        $queryMovil->doesntHave('asistencias');
                    }
                }

                $sesionesMovil = $queryMovil->get();
                @endphp

                @forelse($sesionesMovil as $sesionItem)
                @php
                $carbonDate = \Carbon\Carbon::parse($sesionItem->fecha_sesion)->locale('es');
                $diaSemana = ucfirst($carbonDate->dayName);
                $diaMes = $carbonDate->format('d');
                $mes = $carbonDate->monthName;
                $anio = $carbonDate->format('Y');
                $formatoFecha = "{$diaSemana}, {$diaMes} de {$mes} del año {$anio}";

                $nombreProf = trim(($sesionItem->profesor->user->name_users ?? '') . ' ' . ($sesionItem->profesor->user->last_name_users ?? ''));
                @endphp
                <div class="card card-sesion-movil mb-3 border shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="fw-bold text-dark mb-1">
                                <i class="bi bi-calendar-event text-primary me-1"></i>{{ $formatoFecha }}
                            </div>
                            <div class="text-secondary fw-semibold small text-fade-container mt-1">
                                <span>Profesor: {{ $nombreProf ?: 'Sin asignar' }}</span>
                            </div>
                        </div>
                        <div>
                            @if($sesionItem->asistencias_exists)
                            <span class="badge badge-asistencia-registrada shadow-sm px-2 py-1">Registrada</span>
                            @else
                            <span class="badge badge-asistencia-pendiente shadow-sm px-2 py-1">Pendiente</span>
                            @endif
                        </div>
                    </div>

                    @if($sesionItem->observacion_sesion)
                    <div class="bg-light rounded p-2 mb-3 small text-secondary">
                        <strong><i class="bi bi-chat-text me-1"></i>Obs:</strong> {{ $sesionItem->observacion_sesion }}
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('sesiones.show', $sesionItem->id_sesiones) }}" class="btn btn-primary btn-sm fw-bold flex-grow-1 shadow-sm py-2">
                            <i class="bi bi-check2-square me-1"></i> Tomar Asistencia
                        </a>
                        @can('update', $sesionItem)
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-2 shadow-sm" onclick="abrirModalEditar({{ json_encode($sesionItem) }})" title="Editar Sesión">
                            <i class="bi bi-pencil"></i>
                        </button>
                        @endcan
                        @can('delete', $sesionItem)
                        <form action="{{ route('sesiones.destroy', $sesionItem->id_sesiones) }}" method="POST" class="d-inline form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm px-3 py-2 shadow-sm" title="Anular Sesión">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    <p class="mb-0">No se encontraron sesiones registradas con los filtros seleccionados.</p>
                </div>
                @endforelse
            </div>

        </div>

        <div class="card-footer bg-white text-muted small py-3">
            Historial de sesiones académicas registradas para la sección {{ $seccion->nombre_seccion }}.
        </div>
    </div>

</div>

<!-- INCLUSIÓN DE MODALES DENTRO DEL CONTENT -->
@include('sesiones.partials.modal_sesion')
@include('sesiones.partials.modal_generar_sesiones_masivo')
@endsection

@section('styles')
<style>
    .modal-body .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.9rem;
    }

    .badge-asistencia-registrada {
        background-color: #d1e7dd !important;
        color: #0f5132 !important;
        border: 1px solid #198754 !important;
        font-weight: 600;
    }

    .badge-asistencia-pendiente {
        background-color: #fff3cd !important;
        color: #664d03 !important;
        border: 1px solid #ffc107 !important;
        font-weight: 600;
    }

    .text-fade-container {
        display: flex;
        align-items: center;
        white-space: nowrap;
        overflow: hidden;
        position: relative;
        max-width: 220px;
    }

    .text-fade-container span {
        overflow: hidden;
        text-overflow: ellipsis;
        mask-image: linear-gradient(to right, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0) 100%);
        -webkit-mask-image: linear-gradient(to right, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0) 100%);
    }

    @media (max-width: 767.98px) {
        .content {
            margin: 5px !important;
            padding: 0 !important;
        }

        .card {
            border-radius: 0.5rem !important;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        .card-sesion-movil {
            transition: transform 0.2s ease;
        }

        .card-sesion-movil:active {
            transform: scale(0.99);
        }
    }
</style>
@endsection

@push('scripts')
{!! $dataTable->scripts(null, ['type' => 'module']) !!}

<script type="module">
    let flatpickrInstance = null;

    $(document).ready(function() {
        $('#filtro_asistencia').on('change', function() {
            let filtro = $(this).val();
            if (window.innerWidth < 768) {
                let url = new URL(window.location.href);
                if (filtro) {
                    url.searchParams.set('filtro_asistencia', filtro);
                } else {
                    url.searchParams.delete('filtro_asistencia');
                }
                window.location.href = url.toString();
            } else {
                if (window.LaravelDataTables && window.LaravelDataTables['sesiones-seccion-table']) {
                    window.LaravelDataTables['sesiones-seccion-table'].draw();
                } else if ($.fn.DataTable.isDataTable('#sesiones-seccion-table')) {
                    $('#sesiones-seccion-table').DataTable().draw();
                }
            }
        });

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

        if (typeof window.flatpickr !== 'undefined') {
            flatpickrInstance = window.flatpickr("#modal_fecha_sesion", {
                locale: window.Spanish || "es",
                dateFormat: "Y-m-d",
                minDate: "{{ $seccion->periodoAcademico?->fecha_inicio?->format('Y-m-d') ?? '' }}",
                maxDate: "{{ $seccion->periodoAcademico?->fecha_fin?->format('Y-m-d') ?? '' }}",
                disable: bloqueosFlatpickr,
                allowInput: false
            });
        }

        // Cambio de modalidad en modal masivo
        $('input[name="modo_generacion"]').on('change', function() {
            if ($(this).val() === 'meses') {
                $('#panelModoMeses').removeClass('d-none');
            } else {
                $('#panelModoMeses').addClass('d-none');
            }
        });
        
    });

    window.abrirModalCrear = function() {
        document.getElementById('modalSesionLabel').innerHTML = 'Programar Sesión de Clase';
        document.getElementById('btnText').innerText = 'Programar Clase';
        document.getElementById('formSesion').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('formSesion').action = "{{ route('sesiones.store') }}";

        $('#modal_id_profesor').val(null).trigger('change');

        if (flatpickrInstance) {
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

        $('#modal_id_profesor').val(sesion.id_profesor).trigger('change');
        document.getElementById('modal_observacion_sesion').value = sesion.observacion_sesion || '';

        if (flatpickrInstance) {
            flatpickrInstance.setDate(sesion.fecha_sesion, true);
        } else {
            document.getElementById('modal_fecha_sesion').value = sesion.fecha_sesion;
        }

        if (sesion.tiene_asistencia) {
            if (flatpickrInstance) flatpickrInstance.set('clickOpens', false);
            document.getElementById('modal_fecha_sesion').setAttribute('readonly', true);
            document.getElementById('alertaAsistenciaRegistrada').classList.remove('d-none');
            document.getElementById('ayuda_fecha').classList.add('d-none');
        } else {
            if (flatpickrInstance) flatpickrInstance.set('clickOpens', true);
            document.getElementById('modal_fecha_sesion').removeAttribute('readonly');
            document.getElementById('alertaAsistenciaRegistrada').classList.add('d-none');
            document.getElementById('ayuda_fecha').classList.remove('d-none');
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalSesion'));
        myModal.show();
    }

    window.abrirModalGenerarMasivo = function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalGenerarMasivo'));
        myModal.show();
    }

    // Manejo de Formulario Individual
    document.getElementById('formSesion').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const url = form.action;
        const method = document.getElementById('formMethod').value;

        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(async response => {
            const data = await response.json();
            return { ok: response.ok, status: response.status, body: data };
        })
        .then(res => {
            if (res.ok && res.body.success) {
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
                let errorMsg = res.body.message || 'Ocurrió un error de validación.';
                if (res.body.errors) {
                    errorMsg = Object.values(res.body.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    html: errorMsg
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error de comunicación',
                text: 'Ocurrió un detalle al procesar la respuesta del servidor.'
            });
        });
    });

    // Manejo de Formulario Masivo
    document.getElementById('formGenerarMasivo').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const url = form.action;

        const btn = document.getElementById('btnEjecutarMasivo');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(async response => {
            const data = await response.json();
            return { ok: response.ok, status: response.status, body: data };
        })
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generar Sesiones';

            if (res.ok && res.body.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalGenerarMasivo')).hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Generación Completada!',
                    text: res.body.message,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    if (window.LaravelDataTables && window.LaravelDataTables['sesiones-seccion-table']) {
                        window.LaravelDataTables['sesiones-seccion-table'].draw();
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                let errorMsg = res.body.message || 'No se pudieron generar las sesiones.';
                if (res.body.errors) {
                    errorMsg = Object.values(res.body.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    html: errorMsg
                });
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generar Sesiones';
            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: 'Ocurrió un error inesperado en la red o servidor.'
            });
        });
    });
</script>
@endpush