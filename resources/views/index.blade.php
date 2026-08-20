@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    
    {/* Fila 1: Tarjetas de Métricas Principales (Personal activo, asistencias, etc.) */}
    <div class="col-span-12">
      <x-ecommerce.ecommerce-metrics />
    </div>

    {/* Fila 2: Gráficos de Estadísticas Generales y Distribución */}
    <div class="col-span-12 xl:col-span-7">
      <x-ecommerce.statistics-chart />
    </div>

    <div class="col-span-12 xl:col-span-5">
      <x-ecommerce.monthly-target />
    </div>

    <div class="col-span-12 xl:col-span-12">
      <x-ecommerce.customer-demographic />
    </div>

  </div>
@endsection