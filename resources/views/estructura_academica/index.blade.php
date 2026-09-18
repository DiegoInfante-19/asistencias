@extends('layouts.admin')

@section('content')
<div class="content pt-4" style="margin: 20px;">
    
    <!-- Tarjeta Principal del Nivel 1 (Cohortes y Períodos) -->
    <div class="card ecosystem-card rounded overflow-hidden shadow-sm">
        
        <!-- HEADER DE LA TARJETA -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="card-title text-dark mb-0 fw-bold fs-6">
                <i class="bi bi-diagram-3-fill me-2 text-primary"></i> Estructura Académica General: Cohortes y Períodos
            </h5>
            <button type="button" class="btn btn-primary fw-bold ms-auto shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCohorte">
                <i class="bi bi-plus-lg me-1"></i> Nueva Cohorte y Período
            </button>
        </div>

        <!-- CUERPO CON LA TABLA YAJRA -->
        <div class="card-body bg-white p-4">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover table-striped align-middle w-100 border', 'style' => 'width:100%;']) !!}
            </div>
        </div>

        <!-- FOOTER DE LA TARJETA -->
        <div class="card-footer bg-light py-2 text-muted small border-top">
            Administración jerárquica unificada de cohortes y sus períodos académicos asociados.
        </div>
    </div>
</div>

<!-- INCLUIMOS LOS MODALES EXCLUSIVOS DE COHORTES (Nivel 1) -->
@include('estructura_academica.partials.modals_cohorte')

@endsection

@section('styles')
<style>
    /* ---------------------------------------------------
       ESTÉTICA UNIFICADA DEL ECOSISTEMA DE TARJETAS
    ----------------------------------------------------- */
    .ecosystem-card {
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        background-color: #ffffff !important;
    }
</style>
@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // AUTOMATIZACIÓN NÚMEROS ROMANOS Y FECHAS (Modal Crear Cohorte)
        // ==========================================
        const inputArabe = document.getElementById('input_numero_arabe');
        if (inputArabe) {
            const previewRomano = document.getElementById('preview_romano');
            const inputRomanoFinal = document.getElementById('numero_cohorte_final');
            const inputFechaInicio = document.getElementById('input_fecha_inicio');
            const inputFechaFin = document.getElementById('input_fecha_fin');

            function convertirAResultadoRomano(num) {
                const romanos = [
                    { val: 1000, numeral: 'M' }, { val: 900, numeral: 'CM' },
                    { val: 500, numeral: 'D' }, { val: 400, numeral: 'CD' },
                    { val: 100, numeral: 'C' }, { val: 90, numeral: 'XC' },
                    { val: 50, numeral: 'L' }, { val: 40, numeral: 'XL' },
                    { val: 10, numeral: 'X' }, { val: 9, numeral: 'IX' },
                    { val: 5, numeral: 'V' }, { val: 4, numeral: 'IV' },
                    { val: 1, numeral: 'I' }
                ];
                let resultado = '';
                let n = parseInt(num);
                if (isNaN(n) || n <= 0) return '---';
                for (let i = 0; i < romanos.length; i++) {
                    while (n >= romanos[i].val) {
                        resultado += romanos[i].numeral;
                        n -= romanos[i].val;
                    }
                }
                return resultado;
            }

            function actualizarValoresCohorte() {
                const val = inputArabe.value;
                const num = parseInt(val);

                if (num && num > 0) {
                    const romano = convertirAResultadoRomano(num);
                    const textoFinal = romano + ' COHORTE';
                    previewRomano.textContent = textoFinal;
                    inputRomanoFinal.value = textoFinal;

                    const anioInicio = 2023 + (num - 1);
                    const anioFin = anioInicio + 1;

                    if (num === 3) {
                        if (inputFechaInicio) inputFechaInicio.value = '2025-11-05';
                        if (inputFechaFin) inputFechaFin.value = '2026-07-29';
                    } else {
                        if (inputFechaInicio) {
                            inputFechaInicio.value = `${anioInicio}-08-01`;
                        }
                        if (inputFechaFin) {
                            inputFechaFin.value = `${anioFin}-07-27`;
                        }
                    }
                } else {
                    previewRomano.textContent = '--- COHORTE';
                    inputRomanoFinal.value = '';
                    if (inputFechaInicio) inputFechaInicio.value = '';
                    if (inputFechaFin) inputFechaFin.value = '';
                }
            }

            inputArabe.addEventListener('input', actualizarValoresCohorte);
            inputArabe.addEventListener('change', actualizarValoresCohorte);
        }

        // ==========================================
        // RELLENO DINÁMICO MODAL EDITAR COHORTE (Corregido y Asegurado)
        // ==========================================
        const modalEditarCohorte = document.getElementById('modalEditarCohorte');
        if (modalEditarCohorte) {
            modalEditarCohorte.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                const numero = button.getAttribute('data-numero');
                const descripcion = button.getAttribute('data-descripcion');
                const estatus = button.getAttribute('data-estatus');
                const fechaInicio = button.getAttribute('data-fecha-inicio');
                const fechaFin = button.getAttribute('data-fecha-fin');
                const id = button.getAttribute('data-id');

                document.getElementById('edit_numero_cohorte').value = numero || '';
                document.getElementById('edit_descripcion_cohorte').value = descripcion || '';
                document.getElementById('edit_estatus_cohorte').value = estatus || 'Activo';
                document.getElementById('edit_fecha_inicio').value = fechaInicio || '';
                document.getElementById('edit_fecha_fin').value = fechaFin || '';

                // Asignar la ruta correcta de actualización
                document.getElementById('formEditarCohorte').action = `/cohortes/${id}`;
            });
        }
    });
</script>

<script src="{{ asset('js/admin-validations.js') }}" defer></script>

<!-- Renderizado del script Yajra DataTable de Cohortes -->
{!! $dataTable->scripts(null, ['type' => 'module']) !!}
@endpush