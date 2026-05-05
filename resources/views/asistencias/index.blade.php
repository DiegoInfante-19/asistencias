@extends('layouts.admin')



@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de asistencias
                        </span>

                        <div class="float-right">
                            <a href="{{ route('asistencias.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                Crear Nueva asistencia
                            </a>
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success m-4">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table id="example4" class="table table-bordered table-striped">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>

                                    <th>Fecha</th>
                                    <th>Nombre y apellido del miembro</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $asistencia)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $asistencia->fecha }}</td>
                                    <td>{{ $asistencia->miembro->nombre_apellido }}</td>

                                    <td>
                                        <form action="{{ route('asistencias.destroy', $asistencia->id) }}" method="POST">
                                            <a class="btn btn-sm btn-primary " href="{{ route('asistencias.show', $asistencia->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                            <a class="btn btn-sm btn-success" href="{{ route('asistencias.edit', $asistencia->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $asistencias->withQueryString()->links() !!}
        </div>
    </div>
</div>
<script>
    $(function() {
        // Solo UNA inicialización para #example1
        $("#example4").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Asistencias",
                "infoEmpty": "Mostrando 0 a 0 de 0 asistencias",
                "infoFiltered": "(Filtrado de _MAX_ asistencias totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ asistencias",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad",
                    "print": "Imprimir",
                    "copyTitle": "Copiado al portapapeles",
                    "copySuccess": {
                        "_": "%d filas copiadas",
                        "1": "1 fila copiada"
                    }
                }
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection