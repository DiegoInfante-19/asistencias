@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">

    <!-- Encabezado del Módulo -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Gestión de PNF y Analítica Mensual</h3>
            <p class="text-muted small mb-0">Control de programas formativos, seguimiento por empresas aliadas y matriz de participación por periodos.</p>
        </div>
        
        <div class="d-flex gap-2">
            <!-- Menú Dropdown de Reportes -->
            <div class="dropdown">
                <button class="btn btn-success fw-bold shadow-sm dropdown-toggle" type="button" id="dropdownReportes" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-cloud-download me-1"></i> Exportar Reportes
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownReportes" style="min-width: 250px;">
                    <li><h6 class="dropdown-header text-dark fw-bold bg-light py-2"><i class="bi bi-file-pdf text-danger me-1"></i> Reportes PDF</h6></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="pnfs" data-formato="pdf"><i class="bi bi-card-list me-2 text-secondary"></i>1. Tabla Normal de PNF</a></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="empresas" data-formato="pdf"><i class="bi bi-building me-2 text-secondary"></i>2. Asistencias por Empresa</a></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="estudiantes" data-formato="pdf"><i class="bi bi-people me-2 text-secondary"></i>3. Asistencias por Persona</a></li>
                    
                    <li><hr class="dropdown-divider m-0"></li>
                    
                    <li><h6 class="dropdown-header text-dark fw-bold bg-light py-2"><i class="bi bi-file-excel text-success me-1"></i> Reportes Excel</h6></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="pnfs" data-formato="excel"><i class="bi bi-table me-2 text-secondary"></i>1. Tabla Normal de PNF</a></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="empresas" data-formato="excel"><i class="bi bi-buildings me-2 text-secondary"></i>2. Asistencias por Empresa</a></li>
                    <li><a class="dropdown-item py-2 btn-exportar" href="#" data-tipo="estudiantes" data-formato="excel"><i class="bi bi-person-vcard me-2 text-secondary"></i>3. Asistencias por Persona</a></li>
                </ul>
            </div>

            <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createPnfModal">
                <i class="bi bi-plus-circle me-1"></i> Añadir PNF
            </button>
        </div>
    </div>

    <!-- TARJETA DE FILTROS AVANZADOS (Diseño Unificado) -->
    <div class="card shadow-sm ecosystem-card mb-4" id="cardFiltros">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h4 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                <i class="bi bi-funnel-fill text-primary me-2"></i> Filtros de Búsqueda
            </h4>
        </div>

        <div class="card-body bg-white p-4">
            <div class="row g-3">
                <!-- Filtro por PNF -->
                <div class="col-md-4">
                    <label for="filtro_pnf" class="form-label fw-bold small text-muted text-uppercase">PNF Asignado</label>
                    <select id="filtro_pnf" class="form-select select2-buscador">
                        <option value="">Todos los PNF...</option>
                        @foreach(\App\Models\Pnf::where('vigencia_pnf', true)->orderBy('nombre_pnf')->get() as $pnf)
                        <option value="{{ $pnf->id_pnf }}">{{ $pnf->nombre_pnf }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Sección -->
                <div class="col-md-4">
                    <label for="filtro_seccion" class="form-label fw-bold small text-muted text-uppercase">Sección Específica</label>
                    <select id="filtro_seccion" class="form-select select2-buscador">
                        <option value="">Todas las Secciones...</option>
                        @foreach(\App\Models\Seccion::with(['pnfs', 'periodoAcademico'])->activasParaAsignacion()->orderBy('nombre_seccion')->get() as $seccion)
                        <option value="{{ $seccion->id_seccion }}">
                            Sección: {{ $seccion->nombre_seccion }} ({{ $seccion->pnfs->isEmpty() ? 'Sin PNF' : $seccion->pnfs->pluck('nombre_pnf')->implode(' / ') }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Empresa -->
                <div class="col-md-4">
                    <label for="filtro_empresa" class="form-label fw-bold small text-muted text-uppercase">Empresa Aliada</label>
                    <select id="filtro_empresa" class="form-select select2-buscador">
                        <option value="">Todas las Empresas...</option>
                        @foreach(\App\Models\Empresa::orderBy('nombre_empresa')->get() as $empresa)
                        <option value="{{ $empresa->id_empresa }}">{{ $empresa->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Footer del Card de Filtros (Forzado a Blanco) -->
        <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
            <button type="button" id="btn-limpiar-filtros" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tarjeta Principal con la Tabla Jerárquica -->
    <div class="card shadow-sm ecosystem-card mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title text-dark mb-0 fs-5" style="font-weight: 500;">
                Programas Nacionales de Formación Registrados
            </h5>
        </div>

        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-striped table-hover align-middle w-100 border', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <div class="card-footer bg-light py-2 text-muted small border-top">
            Haga clic en el botón de porcentaje <strong>(%)</strong> en las acciones de cualquier PNF para desplegar el desglose mensual de empresas y asistencias.
        </div>
    </div>
</div>

<div class="modal fade" id="modalPdfViewer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPdfViewerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <!-- Cabecera Limpia y Blanca (Igual que tus otros modales) -->
            <div class="modal-header bg-white border-bottom">
                <h1 class="modal-title fs-5 text-dark" id="modalPdfViewerLabel" style="font-weight: 500;">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Vista Previa del Reporte PDF
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo del Modal con Contenedor Limpio -->
            <div class="modal-body bg-white p-3">
                <div class="border rounded shadow-inner overflow-hidden bg-light" style="height: 75vh;">
                    <!-- El iframe se cargará dinámicamente vía JavaScript -->
                    <iframe id="pdfIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
            
            <!-- Pie de Modal Consistente -->
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

@include('pnfs.partials.modals')
@endsection

@section('styles')
<style>
    .ecosystem-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05) !important;
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }

    .ecosystem-card .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    #cardFiltros .card-footer {
        background-color: #ffffff !important;
    }

    .ecosystem-card .card-footer:not(#cardFiltros .card-footer) {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e2e8f0 !important;
    }

    .card-body.bg-white .form-control,
    .card-body.bg-white .form-select {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .card-body.bg-white .select2-container--bootstrap-5 .select2-selection {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }
</style>
@endsection

@push('scripts')
<script type="module">
    $(document).ready(function() {
        // Inicializar Select2 en los filtros
        $('.select2-buscador').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            placeholder: 'Seleccione una opción...'
        });

        let jerarquiaData = @json($jerarquiaPnf ?? []);
        let debounceTimer = null;

        const table = $('#pnfs-table').DataTable();

        table.on('preXhr.dt', function(e, settings, data) {
            data.pnf = $('#filtro_pnf').val();
            data.seccion = $('#filtro_seccion').val();
            data.empresa = $('#filtro_empresa').val();
        });

        function recargarDatosAnaliticos() {
            $.get("{{ route('pnfs.index') }}", {
                get_jerarquia: true,
                pnf: $('#filtro_pnf').val(),
                seccion: $('#filtro_seccion').val(),
                empresa: $('#filtro_empresa').val()
            }, function(res) {
                jerarquiaData = res.jerarquia || [];
                table.ajax.reload(null, false);
            });
        }

        $('.select2-buscador').on('change', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                recargarDatosAnaliticos();
            }, 350);
        });

        $('#btn-limpiar-filtros').on('click', function() {
            $('.select2-buscador').val(null).trigger('change.select2');
            recargarDatosAnaliticos();
        });

        $('.btn-exportar').on('click', function(e) {
            e.preventDefault();
            
            let tipoReporte = $(this).data('tipo');   // 'pnfs', 'empresas', 'estudiantes'
            let formato = $(this).data('formato');        // 'pdf', 'excel'
            
            // Obtener los filtros actualmente seleccionados por el usuario
            let pnf = $('#filtro_pnf').val() || '';
            let seccion = $('#filtro_seccion').val() || '';
            let empresa = $('#filtro_empresa').val() || '';

            // Construir la URL hacia el backend
            let url = `{{ url('/reportes/analitica-asistencias') }}?tipo=${tipoReporte}&formato=${formato}&pnf=${pnf}&seccion=${seccion}&empresa=${empresa}`;
            
            // Si es EXCEL, descargamos directamente
            if (formato === 'excel') {
                Swal.fire({
                    title: 'Generando Excel...',
                    text: 'Preparando el documento, esto puede tardar unos segundos.',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
                window.location.href = url; // Descarga limpia sin abrir pestañas
                return;
            }

            // Si es PDF, lo mostramos dentro del Modal con un visor integrado
            Swal.fire({
                title: 'Generando PDF...',
                text: 'Renderizando el reporte...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Petición AJAX para obtener el PDF como Blob e incrustarlo en el iframe
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('No se pudo generar el reporte PDF.');
                    return response.blob();
                })
                .then(blob => {
                    Swal.close();
                    let blobUrl = URL.createObjectURL(blob);
                    
                    // Asignar el enlace del blob al iframe del modal
                    $('#pdfIframe').attr('src', blobUrl);
                    
                    // Cambiar título dinámicamente según el reporte
                    $('#modalPdfViewerLabel').html(`<i class="bi bi-file-pdf-fill text-danger me-2"></i> Reporte de ${tipoReporte.toUpperCase()}`);
                    
                    // Mostrar el modal Bootstrap
                    let modalPdf = new bootstrap.Modal(document.getElementById('modalPdfViewer'));
                    modalPdf.show();
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
        });

        // Control del acordeón de filas hijas (Child Rows) mediante el botón de porcentaje
        $('#pnfs-table').on('click', '.toggle-details', function(e) {
            e.preventDefault();
            var btn = $(this);
            var tr = btn.closest('tr');

            // Si DataTables colapsa la fila en pantallas pequeñas (responsive child)
            if (tr.hasClass('child')) {
                tr = tr.prev('.parent');
            }

            var row = table.row(tr);
            var pnfId = btn.data('id');

            if (row.child.isShown()) {
                // Ocultar subtabla
                row.child.hide();
                tr.removeClass('shown');
                btn.removeClass('btn-secondary text-white').addClass('btn-outline-secondary');
            } else {
                // Buscar el objeto de jerarquía del PNF (asegurando coincidencia de tipo con ==)
                var pnfItem = jerarquiaData.find(item => item.id_pnf == pnfId);

                if (pnfItem) {
                    row.child(generarHtmlDetalle(pnfItem)).show();
                    tr.addClass('shown');
                    btn.removeClass('btn-outline-secondary').addClass('btn-secondary text-white');
                } else {
                    // Si por alguna razón no vino en el JSON inicial, creamos una estructura básica vacía para que no falle
                    var fallbackItem = {
                        nombre_pnf: tr.find('td:eq(1)').text().trim(),
                        empresas: [],
                        meses_periodo: @json($jerarquiaPnf[0]['meses_periodo'] ?? []),
                        total_estudiantes: 0,
                        totales_meses_pnf: {},
                        porcentaje_participacion_meses: {}
                    };
                    row.child(generarHtmlDetalle(fallbackItem)).show();
                    tr.addClass('shown');
                    btn.removeClass('btn-outline-secondary').addClass('btn-secondary text-white');
                }
            }
        });

        function generarHtmlDetalle(item) {
            let html = `<div class="p-3 bg-light border rounded shadow-inner">`;

            if (!item.empresas || item.empresas.length === 0) {
                html += `<div class="alert alert-warning mb-0 small">No hay empresas ni estudiantes activos registrados para este PNF con los filtros actuales.</div></div>`;
                return html;
            }

            let numMeses = Object.keys(item.meses_periodo).length;
            // +3 por las columnas: Botón(+), Empresa, Trabajadores
            let colspanSuma = numMeses + 3;

            html += `<div class="table-responsive">
                <!-- Tabla principal de Empresas -->
                <table class="table table-bordered table-sm bg-white align-middle text-center mb-0" style="font-size: 12px; border-color: #adb5bd !important;">
                    <thead class="table-secondary" style="border-color: #adb5bd !important;">
                        <tr>
                            <th class="py-2 text-dark" style="width: 40px; border-color: #adb5bd !important;"></th>
                            <th class="text-start ps-3 py-2 text-dark" style="border-color: #adb5bd !important;">EMPRESAS</th>
                            <th class="py-2 text-dark" style="border-color: #adb5bd !important;">CANTIDAD DE TRABAJADORES</th>`;

            Object.keys(item.meses_periodo).forEach(key => {
                html += `<th class="py-2 text-dark" style="border-color: #adb5bd !important;">${item.meses_periodo[key]}</th>`;
            });

            html += `</tr></thead><tbody>`;

            item.empresas.forEach((empresa, index) => {
                let idFila = `collapse-empresa-${item.id_pnf}-${index}`;

                // Nivel 3: Fila de la Empresa
                html += `<tr>
                    <td style="border-color: #adb5bd !important;" class="text-center align-middle">
                        <button class="btn btn-outline-secondary toggle-estudiantes shadow-sm" data-bs-toggle="collapse" data-bs-target="#${idFila}" aria-expanded="false" title="Ver Desglose de Estudiantes" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                            <i class="bi bi-person-plus-fill toggle-icon"></i>
                        </button>
                    </td>
                    <td class="text-start ps-3 fw-bold text-dark" style="border-color: #adb5bd !important;">${empresa.nombre_empresa}</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">${empresa.cantidad_trabajadores > 0 ? empresa.cantidad_trabajadores : ''}</td>`;

                Object.keys(item.meses_periodo).forEach(key => {
                    let asist = empresa.asistencias_meses[key] || 0;
                    let porc = empresa.porcentaje_meses[key];
                    // Renderiza N/A si no hubo clases (UAP = 0)
                    let celdaHtml = porc !== null ?
                        `${asist} <br><small class="text-muted">(${porc}%)</small>` :
                        `<span class="text-muted">N/A</span>`;
                    html += `<td class="text-dark" style="border-color: #adb5bd !important;">${celdaHtml}</td>`;
                });

                html += `</tr>`;

                // Nivel 4, 5 y 6: Fila colapsable con Estudiantes, Meses y Días (Miércoles)
                html += `<tr class="collapse bg-light" id="${idFila}">
                    <td colspan="${colspanSuma}" class="p-0 border-0">
                        <div class="p-3 border-start border-end border-bottom border-primary" style="background-color: #f8fafc;">
                            <h6 class="fw-bold mb-3 text-secondary">Desglose de Trabajadores: ${empresa.nombre_empresa}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm bg-white text-center align-middle mb-0 shadow-sm" style="font-size: 11px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2" class="align-middle text-start ps-2">CÉDULA</th>
                                            <th rowspan="2" class="align-middle text-start">NOMBRES Y APELLIDOS</th>
                                            <th rowspan="2" class="align-middle">SEMÁFORO<br>INASISTENCIA</th>`;

                // Nivel 5: Cabeceras de los meses fusionando los días
                Object.keys(item.calendario_dias).forEach(mesKey => {
                    let mesData = item.calendario_dias[mesKey];
                    if (mesData.total_clases > 0) {
                        html += `<th colspan="${mesData.total_clases}" class="border-bottom-0">${mesData.nombre}</th>`;
                    }
                });

                html += `</tr><tr>`;

                // Nivel 6: Cabeceras de los días (ej. '07', '14')
                Object.keys(item.calendario_dias).forEach(mesKey => {
                    let mesData = item.calendario_dias[mesKey];
                    mesData.fechas.forEach(fecha => {
                        let diaNum = fecha.split('-')[2];
                        html += `<th class="text-muted bg-light" style="font-size: 10px;">${diaNum}</th>`;
                    });
                });

                html += `</tr></thead><tbody>`;

                if (empresa.estudiantes && empresa.estudiantes.length > 0) {
                    empresa.estudiantes.forEach(est => {
                        html += `<tr>
                            <td class="text-start ps-2 fw-bold text-secondary">${est.cedula}</td>
                            <td class="text-start text-dark">${est.nombre}</td>`;

                       // FASE 3: Renderizado de barra tipográfica minimalista
                        let pct = est.metricas?.porcentaje ?? 0;
                        let colorCss = est.metricas?.clase_css ?? 'bg-secondary';
                        let txt = est.metricas?.texto ?? 'SIN DATOS';
                        let textoPorcentaje = txt === 'SIN DATOS' ? '0%' : `${pct}%`;

                        html += `<td style="min-width: 200px; width: 200px; vertical-align: middle;">
                                    <div class="w-100 px-2 py-1">
                                        <div class="d-flex justify-content-between align-items-end mb-1">
                                            <span class="fw-bold text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px; color: #475569;">
                                                ${txt}
                                            </span>
                                            <span class="fw-bold" style="font-size: 11px; color: #334155;">
                                                ${textoPorcentaje}
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 4px; background-color: #e2e8f0; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                                            <div class="progress-bar ${colorCss}" 
                                                 role="progressbar" 
                                                 style="width: ${pct}%; border-radius: 4px;" 
                                                 aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>`;
                                // Renderizado de estatus por día
                        Object.keys(item.calendario_dias).forEach(mesKey => {
                            let mesData = item.calendario_dias[mesKey];
                            mesData.fechas.forEach(fecha => {
                                let estado = est.estatus_diario[fecha] || null;
                                let icono = `<span class="text-muted">-</span>`; // Sin asistencia registrada

                                if (estado === 'presente') {
                                    icono = `<i class="bi bi-check-circle-fill text-success fs-6" title="Presente"></i>`;
                                } else if (estado === 'ausente') {
                                    icono = `<i class="bi bi-x-circle-fill text-danger fs-6" title="Ausente"></i>`;
                                } else if (estado === 'tarde') {
                                    icono = `<i class="bi bi-clock-fill text-warning fs-6" title="Tarde"></i>`;
                                } else if (estado === 'justificada') {
                                    icono = `<i class="bi bi-info-circle-fill text-info fs-6" title="Justificada"></i>`;
                                }
                                html += `<td>${icono}</td>`;
                            });
                        });
                        html += `</tr>`;
                    });
                } else {
                    html += `<tr><td colspan="100%" class="text-muted py-3">No hay estudiantes activos</td></tr>`;
                }

                html += `</tbody></table></div></div></td></tr>`;
            });

            html += `</tbody><tfoot class="fw-bold">
                <tr class="bg-light">
                    <td colspan="2" class="text-start ps-3 text-dark" style="border-color: #adb5bd !important;">TOTAL PNF</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">${item.total_estudiantes}</td>`;

            Object.keys(item.meses_periodo).forEach(key => {
                html += `<td class="text-dark" style="border-color: #adb5bd !important;">${item.totales_meses_pnf[key] || 0}</td>`;
            });

            html += `</tr><tr class="table-secondary text-dark">
                    <td colspan="2" class="text-start ps-3 text-dark" style="border-color: #adb5bd !important;">PORCENTAJE (%) PARTICIPACIÓN</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">-</td>`;

            Object.keys(item.meses_periodo).forEach(key => {
                let porcGlobal = item.porcentaje_participacion_meses[key];
                let textoGlobal = porcGlobal !== null ?
                    `${porcGlobal.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}%` :
                    'N/A';
                html += `<td class="text-dark" style="border-color: #adb5bd !important;">${textoGlobal}</td>`;
            });

            html += `</tr></tfoot></table></div></div>`;
            return html;
        }

        // Evento para alternar el ícono (+ / -) al expandir el acordeón
        $('#pnfs-table').on('click', '.toggle-estudiantes', function() {
            let btn = $(this);
            let icon = btn.find('.toggle-icon');
            
            // Si el botón ya tiene la clase secundaria sólida (está expandido)
            if (btn.hasClass('btn-secondary')) {
                // Contraer (volver al estilo outline)
                btn.removeClass('btn-secondary text-white').addClass('btn-outline-secondary');
                icon.removeClass('bi-person-dash-fill').addClass('bi-person-plus-fill');
            } else {
                // Expandir (aplicar estilo sólido)
                btn.removeClass('btn-outline-secondary').addClass('btn-secondary text-white');
                icon.removeClass('bi-person-plus-fill').addClass('bi-person-dash-fill');
            }
        });
        // Modales de edición y visualización de PNF
        $('#UpdatePnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#UpdatePnfForm').attr('action', button.data('url'));
            modal.find('#edit-nombre-pnf').val(button.data('nombre'));
            modal.find('#edit-descripcion-pnf').val(button.data('descripcion'));
            modal.find('#edit-vigencia-pnf').val(button.data('vigencia'));
        });

        $('#showPnfModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('#show-nombre-pnf').text(button.data('nombre'));
            var descripcion = button.data('descripcion');
            modal.find('#show-descripcion-pnf').text(descripcion ? descripcion : 'Sin descripción registrada.');
            var vigencia = button.data('vigencia');
            var badge = (vigencia == 1) ?
                '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>' :
                '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Inactivo</span>';
            modal.find('#show-vigencia-pnf').html(badge);
        });
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush