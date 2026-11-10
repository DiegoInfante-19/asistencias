<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .text-center { text-align: center; }
</style>

<h2>Reporte General de Programas Nacionales de Formación (PNF)</h2>
<p>Generado el: {{ $parametros['fecha_generacion'] }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Programa Nacional de Formación (PNF)</th>
            <th class="text-center">Total Empresas</th>
            <th class="text-center">Total Estudiantes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $index => $pnf)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $pnf['nombre_pnf'] }}</td>
            <td class="text-center">{{ count($pnf['empresas'] ?? []) }}</td>
            <td class="text-center">{{ $pnf['total_estudiantes'] ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>