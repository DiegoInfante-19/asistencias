@extends('layouts.admin')
@section('content')

<div class="content" style="margin-left: 20px;">
    <h1>Listado de Ministerios</h1>

@if($message = Session::get('mensaje'))
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
                <h3 class="card-title">Ministerios Registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{url('/ministerios/create')}}" class="btn btn-primary">
                            <i class="bi bi-person-fill-add"></i> Agregar nuevo Ministerio
                        </a>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre del ministerio</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Fecha de ingreso</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 0; ?>
                        @foreach ($ministerios as $ministerio)
                        <tr>
                            <td><?php echo $contador = $contador + 1; ?></td>
                            <td>{{ $ministerio->nombre_ministerio}}</td>
                            <td>{!!$ministerio->descripcion!!}</td>
                            <td style=" text-align: center;">
                                <button class="btn btn-success btn-sm" >Activo</button>
                            </td>
                            <td>{{ $ministerio->fecha_ingreso}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('ministerios.show', $ministerio->id) }}" type="button" class="btn btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('ministerios.edit', $ministerio->id) }}" type="button" class="btn btn-success"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{url('ministerios', $ministerio->id)}}" method="post">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este ministerio?')" class="btn btn-danger" value="">
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
                            <th>Nombre del ministerio</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Fecha de ingreso</th>
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