@extends('layouts.admin')
@section('content')
<div class="content" style="margin: 20px;">
    <h1>Pagina Principal</h1>
    <div class="row">
        <div class="col-lg-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <?php $contador_de_ministerios = 0 ?>
                    @foreach($ministerios as $ministerio)
                    <?php $contador_de_ministerios = $contador_de_ministerios + 1; ?>
                    @endforeach
                    <p><?= $contador_de_ministerios; ?></p>
                    <p>Cohorte</p>
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
                    <?php $contador_de_miembros = 0 ?>
                    @foreach($miembros as $miembro)
                    <?php $contador_de_miembros = $contador_de_miembros + 1; ?>
                    @endforeach
                    <p><?= $contador_de_miembros; ?></p>
                    <p>Estudiantes</p>
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
                    <?php $contador_de_usuarios = 0 ?>
                    @foreach($usuarios as $usuario)
                    <?php $contador_de_usuarios = $contador_de_usuarios + 1; ?>
                    @endforeach
                    <p><?= $contador_de_usuarios; ?></p>
                    <p>Usuarios</p>
                </div>
                <div class="icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <a href="{{ url('usuarios') }}" class="small-box-footer">Mas informacion<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <?php $contador_de_asistencia = 0 ?>
                    @foreach($asistencias as $asistencia)
                    <?php $contador_de_asistencia = $contador_de_asistencia + 1; ?>
                    @endforeach
                    <p><?= $contador_de_asistencia; ?></p>
                    <p>Asistencias</p>
                </div>
                <div class="icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <a href="{{ url('asistencias') }}" class="small-box-footer">Mas informacion<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="container-fluid"> <!-- O la clase que uses como contenedor principal -->

        <!-- Título Sección 1 -->
        <div class="section-title text-center">
            <span>Información del sistema</span>
            <h2>Estadistica General Universitaria</h2>
        </div>
        <br>

        <!-- Fila de Gráficos Duales -->
        <div class="row">
            <!-- PNF -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <canvas id="pnfChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- NIVEL -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <canvas id="nivelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <!-- Título Sección 2 -->
        <div class="section-title text-center">
            <span>Información del sistema</span>
            <h2>Estadistica General por Empresas</h2>
        </div>

        <!-- Fila de Gráfico Único -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <canvas id="empresaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const colores = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
            '#e74a3b', '#858796', '#5a5c69', '#20c997',
            '#6610f2', '#fd7e14', '#6f42c1', '#d63384',
            '#198754'
        ];

        new Chart(document.getElementById('pnfChart'), {
            type: 'doughnut',
            data: {
                labels: [
                    'Distribución y Logística', 'Electricidad', 'Electrónica',
                    'Geociencias', 'Higiene y Seguridad', 'Informática',
                    'Ing. Materiales', 'Instrumentación', 'Mantenimiento',
                    'Mecánica', 'Orfebrería', 'Química', 'Calidad'
                ],
                datasets: [{
                    data: [22, 19, 2, 0, 21, 1, 16, 6, 10, 61, 8, 1, 0],
                    backgroundColor: colores
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Nro de Participantes por PNF'
                    }
                }
            }
        });


        new Chart(document.getElementById('empresaChart'), {
            type: 'bar',
            data: {
                labels: [
                    'ALCASA', 'BAUXILUM', 'BRIQCAR', 'Briq. Orinoco',
                    'BRIQVEN', 'CABELUM', 'CARBONORCA', 'Casa Matriz',
                    'CONSIGUA', 'FERROCA', 'FERROMINERA', 'LOGÍSTICA',
                    'MINERVEN', 'SIDOR', 'VENALUM', 'MIERVEN'
                ],
                datasets: [{
                    label: 'Participantes',
                    data: [20, 34, 7, 13, 19, 6, 14, 2, 17, 23, 32, 11, 2, 10, 35, 19],
                    backgroundColor: colores
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución por Empresas'
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 60,
                            minRotation: 45
                        }
                    }
                }
            }
        });
        new Chart(document.getElementById('nivelChart'), {
            type: 'pie',
            data: {
                labels: ['TSU', 'Licenciatura', 'Ingeniería'],
                datasets: [{
                    data: [182, 8, 74],
                    backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b']
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Nivel Académico. Titulos a optar'
                    }
                }
            }
        });

    });
</script>
@endsection