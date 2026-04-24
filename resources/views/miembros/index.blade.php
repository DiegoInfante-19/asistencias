@extends('layouts.admin')
@section('content')
<h1>Listado de Miembros</h1>

@if($message = Session::get('mensaje'))
<script>
    Swal.fire({
        title: "Good job!",
        text: "You clicked the button!",
        icon: "success"
    });
</script>

@endif

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Miembros Registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{url('/miembros/create')}}" class="btn btn-primary">
                            <i class="bi bi-person-fill-add"></i> Agregar nuevo miembro
                        </a>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombres y Apellidos</th>
                            <th>Telefonos</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Agregado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 0; ?>
                        @foreach ($miembros as $miembro)
                        <tr>
                            <td><?php echo $contador = $contador + 1; ?></td>
                            <td>{{ $miembro->nombre_apellido}}</td>
                            <td>{{ $miembro->telefono}}</td>
                            <td>{{ $miembro->email}}</td>
                            <td>{{ $miembro->estado}}</td>
                            <td>{{ $miembro->fecha_ingreso}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('miembros.show', $miembro->id) }}" type="button" class="btn btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('miembros.edit', $miembro->id) }}" type="button" class="btn btn-success"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="{{ route('miembros.destroy', $miembro->id) }}" type="button" class="btn btn-danger"><i class="bi bi-trash-fill"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nro</th>
                            <th>Nombres y Apellidos</th>
                            <th>Telefonos</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Agregado</th>
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
@endsection