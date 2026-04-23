<!--  -->

@extends('layouts.admin')

@section('content')
<div class="content">
    <h1>Detalles del Miembro</h1>

    <div class="row">
        <div class="col-md-11">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Miembro</h3>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nombre_apellido">Nombres y Apellidos*</label>
                                        <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" value="{{ $miembro->nombre_apellido }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email*</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $miembro->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="telefono">Telefono*</label>
                                        <input type="number" class="form-control" id="telefono" name="telefono" value="{{ $miembro->telefono }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento*</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $miembro->fecha_nacimiento }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="file">Fotografía</label><br>

                                @if($miembro->fotografia == '')
                                    @if($miembro->genero == 'MASCULINO')
                                        <center>
                                            <img src="{{url('images/hombre.png')}}" width="100" alt="">
                                        </center>
                                     @else
                                        <center>
                                            <img src="{{url('images/mujer.png')}}" width="100" alt="">
                                        </center>
                                     @endif
                                @else
                                    <center>
                                        <img src="{{asset('storage').'/'.$miembro->fotografia}}" width="100" alt="">
                                    </center>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="genero">Genero*</label>
                                        <input type="text" id="genero" class="form-control" name="genero" value="{{ $miembro->genero }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ministerio">Ministerio*</label>
                                        <input type="text" class="form-control" id="ministerio" name="ministerio" value="{{ $miembro->ministerio }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Direccion*</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $miembro->direccion }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>


                </div>
            </div>
        </div>
    </div>
</div>




@endsection