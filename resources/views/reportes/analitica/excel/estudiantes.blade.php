<table>
    <tr>
        <th colspan="4" style="background-color: #0F172A; color: #FFFFFF; font-weight: bold; font-size: 13px; text-align: left; height: 25px;">
            Reporte Detallado: Asistencias por Persona
        </th>
    </tr>
    <tr>
        <th colspan="4" style="font-style: italic; color: #475569; font-size: 10px; text-align: left;">
            Generado el: {{ $parametros['fecha_generacion'] }}
        </th>
    </tr>
    <tr><td colspan="4"></td></tr> <!-- Espacio de separación -->

    @foreach($datos as $pnf)
        <tr>
            <th colspan="4" style="background-color: #0F172A; color: #FFFFFF; font-weight: bold; text-align: left; height: 22px;">
                PNF: {{ $pnf['nombre_pnf'] }}
            </th>
        </tr>
        <tr><td colspan="4"></td></tr> <!-- Espacio de separación de 2 celdas -->

        @foreach($pnf['empresas'] ?? [] as $empresa)
            <tr>
                <th colspan="4" style="background-color: #94A3B8; color: #000000; font-weight: bold; text-align: left; height: 20px;">
                    Empresa: {{ $empresa['nombre_empresa'] }}
                </th>
            </tr>
            <tr style="background-color: #E2E8F0; font-weight: bold; text-align: center;">
                <th style="border: 1px solid #94A3B8;">Cédula</th>
                <th style="border: 1px solid #94A3B8;">Nombres y Apellidos</th>
                <th style="border: 1px solid #94A3B8;">Asistencia Global</th>
                <th style="border: 1px solid #94A3B8;">Estatus</th>
            </tr>

            @forelse($empresa['estudiantes'] ?? [] as $est)
                <tr>
                    <td style="border: 1px solid #CBD5E1; text-align: left;">{{ $est['cedula'] ?? 'N/A' }}</td>
                    <td style="border: 1px solid #CBD5E1; text-align: left;">{{ $est['nombre'] ?? 'N/A' }}</td>
                    <td style="border: 1px solid #CBD5E1; text-align: center;">{{ $est['metricas']['porcentaje'] ?? 0 }}%</td>
                    <td style="border: 1px solid #CBD5E1; text-align: center;">{{ $est['metricas']['texto'] ?? 'N/D' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="border: 1px solid #CBD5E1; text-align: center;">Sin trabajadores registrados en este periodo.</td>
                </tr>
            @endforelse
            <tr><td colspan="4"></td></tr> <!-- Separación limpia entre tablas de empresas -->
        @endforeach
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>
    @endforeach
</table>