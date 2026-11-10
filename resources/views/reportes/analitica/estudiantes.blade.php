<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body { font-family: sans-serif; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 4px; text-align: center; }
    th { background-color: #e2e8f0; }
    .text-left { text-align: left; }
    .title-bar { background-color: #0f172a; color: white; padding: 5px; margin-top: 15px; font-weight: bold; }
    .subtitle-bar { background-color: #94a3b8; color: black; padding: 4px; font-weight: bold; }
</style>

<h2>Reporte Detallado: Asistencias por Persona</h2>
<p>Generado el: {{ $parametros['fecha_generacion'] }}</p>

@foreach($datos as $pnf)
    <div class="title-bar">PNF: {{ $pnf['nombre_pnf'] }}</div>
    
    @foreach($pnf['empresas'] ?? [] as $empresa)
        <div class="subtitle-bar">Empresa: {{ $empresa['nombre_empresa'] }}</div>
        <table>
            <thead>
                <tr>
                    <th class="text-left">Cédula</th>
                    <th class="text-left">Nombres y Apellidos</th>
                    <th>Asistencia Global</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresa['estudiantes'] ?? [] as $est)
                <tr>
                    <td class="text-left">{{ $est['cedula'] }}</td>
                    <td class="text-left">{{ $est['nombre'] }}</td>
                    <td>{{ $est['metricas']['porcentaje'] ?? '0' }}%</td>
                    <td>{{ $est['metricas']['texto'] ?? 'N/D' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Sin trabajadores registrados en este periodo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
@endforeach