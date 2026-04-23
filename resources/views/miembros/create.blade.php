@extends('layouts.admin')

@section('content')
<div class="content">
    <h1>Crear Miembro</h1>


    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            <li>{{$error}}</li>
        </div>
    @endforeach

    prueba la validacion oite

    <div class="row">
        <div class="col-md-11">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos</h3>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ url('/miembros') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre_apellido">Nombres y Apellidos*</label>
                                            <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" required>
                                            @error('nombre_apellido')
                                            <small class="text-danger">Este campo es obligatorio</small>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Email*</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                            @error('email')
                                            <small class="text-danger">Este campo es obligatorio</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono">Telefono*</label>
                                            <input type="number" class="form-control" id="telefono" name="telefono" required>
                                            @error('telefono')
                                            <small class="text-danger">Este campo es obligatorio</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fecha_nacimiento">Fecha de Nacimiento*</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                            @error('fecha_nacimiento')
                                            <small class="text-danger">Este campo es obligatorio</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="file">Fotografía</label>
                                    <input type="file" id="file" class="form-control" name="fotografia">
                                    <br>
                                    <output id="list"></output>

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
                                                        document.getElementById("list").innerHTML = [
                                                            '<img class="thumb thumbnail" src="', e.target.result,
                                                            '" width="100%" title="', escape(theFile.name), '"/>'
                                                        ].join('');
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

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="genero">Genero*</label>
                                            <select class="form-control" id="genero" name="genero">
                                                <option value="">Seleccione una opción</option>
                                                <option value="masculino">Masculino</option>
                                                <option value="femenino">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ministerio">Ministerio*</label>
                                            <input type="text" class="form-control" id="ministerio" name="ministerio">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion">Direccion*</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <a href="" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
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