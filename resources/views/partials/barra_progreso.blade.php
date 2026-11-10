@php
    $textoPct = $alertaTexto === 'SIN DATOS' ? '0%' : "{$porcentaje}%";
@endphp

<div class="w-100 px-2 py-1">
    <!-- Contenedor Tipográfico -->
    <div class="d-flex justify-content-between align-items-end mb-1">
        <span class="fw-bold text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px; color: #475569;">
            {{ $alertaTexto }}
        </span>
        <span class="fw-bold" style="font-size: 11px; color: #334155;">
            {{ $textoPct }}
        </span>
    </div>
    
    <!-- Barra de Progreso Minimalista -->
    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #e2e8f0; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
        <div class="progress-bar {{ $colorClass }}" 
             role="progressbar" 
             style="width: {{ $porcentaje }}%; border-radius: 4px;" 
             aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>