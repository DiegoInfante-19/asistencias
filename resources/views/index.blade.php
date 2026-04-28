@extends('layouts.admin')
@section('content')
<div class="content" style="margin: 20px;">
    <h1>Pagina Principal</h1>
    <div>
        <div class="col-lg-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <?php $contador_de_ministerios = 0?>
                    @foreach($ministerios as $ministerio)
                        <?php $contador_de_ministerios = $contador_de_ministerios + 1; ?> 
                    @endforeach
                    <p><?= $contador_de_ministerios; ?></p>
                    <p>Ministerios</p>
                </div>
                <div class="icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <a href="{{ url('ministerios') }}" class="small-box-footer">Mas informacion<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <?php $contador_de_miembros = 0?>
                    @foreach($miembros as $miembro)
                        <?php $contador_de_miembros = $contador_de_miembros + 1; ?> 
                    @endforeach
                    <p><?= $contador_de_miembros; ?></p>
                    <p>Miembros</p>
                </div>
                <div class="icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <a href="{{ url('ministerios') }}" class="small-box-footer">Mas informacion<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        
    </div>
</div>
@endsection