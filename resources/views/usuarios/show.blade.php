@extends('layouts.admin')
@section('content')

<div class="content" style="margin-left: 20px;">
    <h1>Datos del Usuario</h1>

    @if($message = Session::get('mensaje'))
    <script>
        Swal.fire({
            title: "{{$message}}",
            icon: "success"
        });
    </script>

    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Revise los datos Correcto</h3>
                </div>
                <div class="card-body" style="display: block;">


                    <div class="row mb-6">
                        <label for="name" class="col-md-4 col-form-label text-md-end">Nombres y apellidos del usuario</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $usuario->name}}" disabled>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">Correo Electronico</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $usuario->email }}" disabled>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">Fecha de Ingreso</label>
                        <div class="col-md-6">
                            <input id="password" type="text" class="form-control" name="password" value="{{$usuario->fecha_ingreso}}" disabled>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{url('/usuario')}}" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection