<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body { font-family: sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 5px; text-align: center; }
    th { background-color: #e2e8f0; }
    .text-left { text-align: left; }
    .pnf-title { background-color: #334155; color: #fff; padding: 8px; font-size: 13px; font-weight: bold;}
</style>

<h2>Reporte Analítico: Asistencias por Empresa</h2>
<p>Generado el: {{ $parametros['fecha_generacion'] }}</p>

@foreach($datos as $pnf)
    <div class="pnf-title">PNF: {{ $pnf['nombre_pnf'] }}</div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Empresa Aliada</th>
                <th>Trabajadores</th>
                @foreach($pnf['meses_periodo'] ?? [] as $mes)
                    <th>{{ $mes }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($pnf['empresas'] ?? [] as $empresa)
            <tr>
                <td class="text-left">{{ $empresa['nombre_empresa'] }}</td>
                <td>{{ $empresa['cantidad_trabajadores'] }}</td>
                @foreach($pnf['meses_periodo'] ?? [] as $key => $mes)
                    <td>{{ $empresa['porcentaje_meses'][$key] ?? '0' }}%</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
@endforeach