<table>
    <tr>
        <th colspan="4" style="background-color: #0F172A; color: #FFFFFF; font-weight: bold; font-size: 13px; text-align: left; height: 25px;">
            Reporte General de Programas Nacionales de Formación (PNF)
        </th>
    </tr>
    <tr>
        <th colspan="4" style="font-style: italic; color: #475569; font-size: 10px; text-align: left;">
            Generado el: {{ $parametros['fecha_generacion'] }}
        </th>
    </tr>
    <tr><td colspan="4"></td></tr> <!-- Espacio de separación -->

    <tr style="background-color: #E2E8F0; font-weight: bold; text-align: center;">
        <th style="border: 1px solid #94A3B8; width: 8px;">#</th>
        <th style="border: 1px solid #94A3B8; text-align: left;">Programa Nacional de Formación (PNF)</th>
        <th style="border: 1px solid #94A3B8;">Total Empresas</th>
        <th style="border: 1px solid #94A3B8;">Total Estudiantes</th>
    </tr>

    @foreach($datos as $index => $pnf)
        <tr>
            <td style="border: 1px solid #CBD5E1; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #CBD5E1; text-align: left;">{{ $pnf['nombre_pnf'] ?? 'N/A' }}</td>
            <td style="border: 1px solid #CBD5E1; text-align: center;">{{ count($pnf['empresas'] ?? []) }}</td>
            <td style="border: 1px solid #CBD5E1; text-align: center;">{{ $pnf['total_estudiantes'] ?? 0 }}</td>
        </tr>
    @endforeach
</table>