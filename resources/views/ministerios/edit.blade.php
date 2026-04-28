@extends('layouts.admin')

@section('content')
<div class="content" style="margin-left: 20px;">
    <h1>Actualizacion del Ministerio</h1>


    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            <li>{{$error}}</li>
        </div>
    @endforeach

    <div class="row">
        <div class="col-md-11">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Revise los datos</h3>
                </div>

                <div class="card-body" style="display: block;">
                    <form action="{{url('/ministerios', $ministerio->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{method_field('PATCH')}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nombre_ministerio">Nombres del ministerio*</label>
                                            <input type="text" class="form-control" id="nombre_ministerio" name="nombre_ministerio" value="{{($ministerio->nombre_ministerio) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fecha_ingreso">Fecha de Ingreso*</label>
                                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{($ministerio->fecha_ingreso) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12"> 
                                        <div class="form-group">
                                            <label for="descripcion">Descripción</label>
                                            <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10">{{($ministerio->descripcion) }}</textarea>
                                            <script>
                                                CKEDITOR.replace('descripcion');
                                            </script>
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
                                    <button type="submit" class="btn btn-success">Actualizar Ministerio</button>
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



