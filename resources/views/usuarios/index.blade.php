@extends('layouts.admin')
@section('content')

<div class="content" style="margin-left: 20px;">
    <h1>Listado de Usuarios</h1>

@if($message = Session::get('success'))
<script>
    Swal.fire({
        title: "{{$message}}",
        icon: "success"
    });
</script>

@endif

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuarios Registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{url('/usuarios/create')}}" class="btn btn-primary">
                            <i class="bi bi-person-fill-add"></i> Agregar nuevo Usuario
                        </a>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
                <table id="example3" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de Ingrsso</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 0; ?>
                        @foreach ($usuarios as $usuario)
                        <tr>
                            <td><?php echo $contador = $contador + 1; ?></td>
                            <td>{{ $usuario->name}}</td>
                            <td>{{ $usuario->email}}</td>
                            <td>{{ $usuario->fecha_ingreso}}</td>
                            <td style="text-align: center;">
                                <button class="btn btn-success btn-sm" style="border-radius: 20px;">Activo</button> 
                            </td>

                            <td style="text-align: center;">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('usuarios.show', $usuario->id) }}" type="button" class="btn btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" type="button" class="btn btn-success"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{url('usuarios', $usuario->id)}}" method="post">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este Usuario?')" class="btn btn-danger" value="">
                                            <i class="bi bi-trash-fill"></i>
                                        </button> 
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de Ingrsso</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>

<script>
    $(function() {
      // Solo UNA inicialización para #example1
      $("#example3").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "language": {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
          "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
          "infoFiltered": "(Filtrado de _MAX_ Usuarios totales)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Usuarios",
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