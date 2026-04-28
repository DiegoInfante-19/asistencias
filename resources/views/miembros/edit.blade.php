@extends('layouts.admin')

@section('content')
<div class="content" style="margin-left: 20px;">
    <h1>Actualizar Datos de un Miembro</h1>

    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        <li>{{$error}}</li>
    </div>
    @endforeach


    <div class="row">
        <div class="col-md-11">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><b>Llene los datos</b></h3>
                </div>

                <div class="card-body" style="display: block">
                    <!-- <form action="{{url('miembros/', $miembro->id)}}" method="POST" enctype="multipart/form-data"> -->
                    <form action="{{ route('miembros.update', $miembro->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre_apellido">Nombres y Apellidos*</label>
                                            <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" value="{{ $miembro->nombre_apellido }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Email*</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $miembro->email }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono">Telefono*</label>
                                            <input type="number" class="form-control" id="telefono" name="telefono" value="{{ $miembro->telefono }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fecha_nacimiento">Fecha de Nacimiento*</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $miembro->fecha_nacimiento }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="genero">Genero*</label>
                                            <select class="form-control" id="genero" name="genero">
                                                @if($miembro->genero == 'MASCULINO')
                                                <option value="MASCULINO" selected>Masculino</option>
                                                <option value="FEMENINO">Femenino</option>
                                                @else
                                                <option value="FEMENINO" selected>Femenino</option>
                                                <option value="MASCULINO">Masculino</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ministerio">Ministerio*</label>
                                            <input type="text" class="form-control" id="ministerio" name="ministerio" value="{{ $miembro->ministerio }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion">Direccion*</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $miembro->direccion }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="file">Fotografía</label>
                                    <input type="file" id="file" class="form-control" name="fotografia"><br>
                                    <center>
                                        <output id="list">
                                            @if($miembro->fotografia == '')
                                            @if($miembro->genero == 'MASCULINO')
                                            <img src="{{url('images/hombre.png')}}" width="100" alt="">
                                            @else
                                            <img src="{{url('images/mujer.png')}}" width="100" alt="">
                                            @endif
                                            @else
                                            <img src="{{asset('storage').'/'.$miembro->fotografia}}" width="100" alt="">
                                            @endif
                                        </output>
                                    </center>

                                    <script>
                                        function archivo(evt) {
                                            var files = evt.target.files; // Variable correcta: files
                                            // Corregimos el ciclo: usamos 'files'
                                            for (var i = 0, f; f = files[i]; i++) {
                                                // Solo admitimos imágenes
                                                if (!f.type.match('image.*')) {
                                                    continue;
                                                }
                                                var reader = new FileReader();
                                                reader.onload = (function(theFile) {
                                                    return function(e) {
                                                        // Insertamos la imagen en el output
                                                        document.getElementById("list").innerHTML = ['<img class="thumb thumbnail" src="', e.target.result, '" width="100%" title="', escape(theFile.name), '"/>'].join('');
                                                    };
                                                })(f);
                                                reader.readAsDataURL(f);
                                            }
                                        }
                                        // Escuchar el cambio en el input
                                        document.getElementById('file').addEventListener('change', archivo, false);
                                    </script>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <a href="" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Guardar Registro</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection