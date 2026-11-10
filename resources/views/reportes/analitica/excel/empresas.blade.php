@php
    $mesesCabecera = !empty($datos[0]['meses_periodo']) ? $datos[0]['meses_periodo'] : [];
    $totalColumnas = 2 + count($mesesCabecera);
@endphp

<table>
    <tr>
        <th colspan="{{ $totalColumnas }}" style="background-color: #0F172A; color: #FFFFFF; font-weight: bold; font-size: 13px; text-align: left; height: 25px;">
            Reporte Analítico: Asistencias por Empresa
        </th>
    </tr>
    <tr>
        <th colspan="{{ $totalColumnas }}" style="font-style: italic; color: #475569; font-size: 10px; text-align: left;">
            Generado el: {{ $parametros['fecha_generacion'] }}
        </th>
    </tr>
    <tr><td colspan="{{ $totalColumnas }}"></td></tr> <!-- Espacio de separación -->

    @foreach($datos as $pnf)
        <tr>
            <th colspan="{{ $totalColumnas }}" style="background-color: #0F172A; color: #FFFFFF; font-weight: bold; text-align: left; height: 22px;">
                PNF: {{ $pnf['nombre_pnf'] }}
            </th>
        </tr>
        <tr><td colspan="{{ $totalColumnas }}"></td></tr> <!-- Espacio de separación de 2 celdas -->

        <tr style="background-color: #E2E8F0; font-weight: bold; text-align: center;">
            <th style="border: 1px solid #94A3B8; text-align: left;">Empresa Aliada</th>
            <th style="border: 1px solid #94A3B8;">Trabajadores</th>
            @foreach($mesesCabecera as $mes)
                <th style="border: 1px solid #94A3B8;">{{ $mes }}</th>
            @endforeach
        </tr>

        @foreach($pnf['empresas'] ?? [] as $empresa)
            <tr>
                <td style="border: 1px solid #CBD5E1; text-align: left;">{{ $empresa['nombre_empresa'] ?? 'N/A' }}</td>
                <td style="border: 1px solid #CBD5E1; text-align: center;">{{ $empresa['cantidad_trabajadores'] ?? 0 }}</td>
                @foreach($mesesCabecera as $key => $mes)
                    @php
                        $porc = $empresa['porcentaje_meses'][$key] ?? null;
                    @endphp
                    <td style="border: 1px solid #CBD5E1; text-align: center;">
                        {{ $porc !== null ? $porc . '%' : '0%' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
        
        <tr><td colspan="{{ $totalColumnas }}"></td></tr>
        <tr><td colspan="{{ $totalColumnas }}"></td></tr>
    @endforeach
</table>
