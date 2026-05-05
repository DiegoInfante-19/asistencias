<?php $i =0;?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de asistenicas</title>
</head>

<body>
    <br><br>
    <h1>Reporte de asistencias</h1>
    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead class="thead">
            <tr>
                <th>No</th>
                <th>Fecha</th>
                <th>Nombre y apellido del miembro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $asistencia->fecha }}</td>
                <td>{{ $asistencia->miembro->nombre_apellido }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>