@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            Reporte de asistencias
                        </span>
                    </div>
                </div>
                @if($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <a href="{{ url('/asistencias/pdf') }}">
                                        <i class="far"><i class="bi bi-printer"></i></i>
                                    </a>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Imprimir reporte</span>
                                    <span class="info-box-number">Asistenicias</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <a href="{{ url('/asistencias/pdf') }}">
                                        <i class="far"><i class="bi bi-printer"></i></i>
                                    </a>
                                </span>
                                <div class="info-box-content">
                                    <form action="{{route('asistencias/pdf_fechas')}}" method="get">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Fecha de Inicio</label>
                                                <input type="date" class="form-control" name="fi">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Fecha del Final</label>
                                                <input type="date" class="form-control" name ="ff">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success">Generar Reporte</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection