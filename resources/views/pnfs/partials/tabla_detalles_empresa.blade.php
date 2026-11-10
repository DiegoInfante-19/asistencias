<div class="p-3 bg-light border rounded shadow-inner">
    @if(empty($item['empresas']))
    <div class="alert alert-warning mb-0 small">
        No hay empresas ni estudiantes activos registrados para este PNF en el período actual.
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-sm bg-white align-middle text-center mb-0" style="font-size: 12px; border-color: #adb5bd !important;">
            <thead class="table-secondary" style="border-color: #adb5bd !important;">
                <tr>
                    <th class="py-2 text-dark" style="width: 40px; border-color: #adb5bd !important;"></th>
                    <th class="text-start ps-3 py-2 text-dark" style="border-color: #adb5bd !important;">EMPRESAS</th>
                    <th class="py-2 text-dark" style="border-color: #adb5bd !important;">CANTIDAD DE TRABAJADORES</th>
                    @foreach($item['meses_periodo'] as $mesKey => $mesNombre)
                    <th class="py-2 text-dark" style="border-color: #adb5bd !important;">{{ $mesNombre }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($item['empresas'] as $index => $empresa)
                @php $idFila = "collapse-blade-{$item['id_pnf']}-{$index}"; @endphp
                <tr>
                    <td style="border-color: #adb5bd !important;" class="text-center align-middle">
                        <button class="btn btn-outline-secondary toggle-estudiantes shadow-sm" data-bs-toggle="collapse" data-bs-target="#{{ $idFila }}" aria-expanded="false" title="Ver Desglose de Estudiantes" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                            <i class="bi bi-person-plus-fill toggle-icon"></i>
                        </button>
                    </td>
                    <td class="text-start ps-3 fw-bold text-dark" style="border-color: #adb5bd !important;">{{ $empresa['nombre_empresa'] }}</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">{{ $empresa['cantidad_trabajadores'] > 0 ? $empresa['cantidad_trabajadores'] : '' }}</td>
                    @foreach($item['meses_periodo'] as $mesKey => $mesNombre)
                    @php
                    $asist = $empresa['asistencias_meses'][$mesKey] ?? 0;
                    $porc = $empresa['porcentaje_meses'][$mesKey] ?? null;
                    @endphp
                    <td class="text-dark" style="border-color: #adb5bd !important;">
                        @if($porc !== null)
                        {{ $asist }} <br><small class="text-muted">({{ $porc }}%)</small>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    @endforeach
                </tr>

                <!-- Acordeón Anidado (Trabajadores) -->
                <tr class="collapse bg-light" id="{{ $idFila }}">
                    <td colspan="{{ count($item['meses_periodo']) + 3 }}" class="p-0 border-0">
                        <div class="p-3 border-start border-end border-bottom border-primary" style="background-color: #f8fafc;">
                            <h6 class="fw-bold mb-3 text-secondary">Desglose de Trabajadores: {{ $empresa['nombre_empresa'] }}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm bg-white text-center align-middle mb-0 shadow-sm" style="font-size: 11px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2" class="align-middle text-start ps-2">CÉDULA</th>
                                            <th rowspan="2" class="align-middle text-start">NOMBRES Y APELLIDOS</th>
                                            <th rowspan="2" class="align-middle">SEMÁFORO<br>INASISTENCIA</th>
                                            @foreach($item['calendario_dias'] as $mesKey => $mesData)
                                            @if($mesData['total_clases'] > 0)
                                            <th colspan="{{ $mesData['total_clases'] }}" class="border-bottom-0">{{ $mesData['nombre'] }}</th>
                                            @endif
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($item['calendario_dias'] as $mesKey => $mesData)
                                            @foreach($mesData['fechas'] as $fecha)
                                            <th class="text-muted bg-light" style="font-size: 10px;">{{ explode('-', $fecha)[2] }}</th>
                                            @endforeach
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($empresa['estudiantes'] as $est)
                                        <tr>
                                            <td class="text-start ps-2 fw-bold text-secondary">{{ $est['cedula'] }}</td>
                                            <td class="text-start text-dark">{{ $est['nombre'] }}</td>
                                            <td style="min-width: 200px; width: 200px; vertical-align: middle;">
                                                @include('partials.barra_progreso', [
                                                'porcentaje' => $est['metricas']['porcentaje'] ?? 0,
                                                'colorClass' => $est['metricas']['clase_css'] ?? 'bg-secondary',
                                                'alertaTexto' => $est['metricas']['texto'] ?? 'SIN DATOS'
                                                ])
                                            </td>
                                            @foreach($item['calendario_dias'] as $mesKey => $mesData)
                                            @foreach($mesData['fechas'] as $fecha)
                                            @php $estado = $est['estatus_diario'][$fecha] ?? null; @endphp
                                            <td>
                                                @if($estado === 'presente') <i class="bi bi-check-circle-fill text-success fs-6" title="Presente"></i>
                                                @elseif($estado === 'ausente') <i class="bi bi-x-circle-fill text-danger fs-6" title="Ausente"></i>
                                                @elseif($estado === 'tarde') <i class="bi bi-clock-fill text-warning fs-6" title="Tarde"></i>
                                                @elseif($estado === 'justificada') <i class="bi bi-info-circle-fill text-info fs-6" title="Justificada"></i>
                                                @else <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            @endforeach
                                            @endforeach
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="100%" class="text-muted py-3">No hay estudiantes activos</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr class="bg-light">
                    <td colspan="2" class="text-start ps-3 text-dark" style="border-color: #adb5bd !important;">TOTAL PNF</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">{{ $item['total_estudiantes'] }}</td>
                    @foreach($item['meses_periodo'] as $mesKey => $mesNombre)
                    <td class="text-dark" style="border-color: #adb5bd !important;">{{ $item['totales_meses_pnf'][$mesKey] ?? 0 }}</td>
                    @endforeach
                </tr>
                <tr class="table-secondary text-dark">
                    <td colspan="2" class="text-start ps-3 text-dark" style="border-color: #adb5bd !important;">PORCENTAJE (%) PARTICIPACIÓN</td>
                    <td class="text-dark" style="border-color: #adb5bd !important;">-</td>
                    @foreach($item['meses_periodo'] as $mesKey => $mesNombre)
                    @php $porcGlobal = $item['porcentaje_participacion_meses'][$mesKey] ?? null; @endphp
                    <td class="text-dark" style="border-color: #adb5bd !important;">
                        {{ $porcGlobal !== null ? number_format($porcGlobal, 2, ',', '.') . '%' : 'N/A' }}
                    </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>