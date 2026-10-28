<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\PeriodoAcademico;
use App\DataTables\CohortesDataTable;
use App\Http\Requests\StoreCohorteRequest;
use App\Http\Requests\UpdateCohorteRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CohorteController extends Controller
{
    public function index(CohortesDataTable $dataTable)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('cohortes.index');
    }

    public function store(StoreCohorteRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // 1. Crear la Cohorte
                $cohorte = Cohorte::create([
                    'numero_cohorte'      => $request->numero_cohorte,
                    'descripcion_cohorte' => $request->descripcion_cohorte,
                    'estatus_cohorte'     => $request->estatus_cohorte,
                ]);

                // 2. Crear automáticamente su Período Académico asociado (Relación 1 a 1)
                PeriodoAcademico::create([
                    'id_cohortes'     => $cohorte->id_cohortes,
                    'fecha_inicio'    => $request->fecha_inicio,
                    'fecha_fin'       => $request->fecha_fin,
                    'estatus_periodo' => $request->estatus_cohorte === 'Activo' ? 'Activo' : 'Finalizado',
                ]);
            });

            return redirect()->route('estructura.index')
                ->with('success', 'Cohorte y período académico registrados correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar la cohorte: ' . $e->getMessage());
        }
    }

    public function update(UpdateCohorteRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $cohorte = Cohorte::findOrFail($id);
                
                // 1. Actualizamos la Cohorte
                $cohorte->update([
                    'numero_cohorte'      => $request->numero_cohorte,
                    'descripcion_cohorte' => $request->descripcion_cohorte,
                    'estatus_cohorte'     => $request->estatus_cohorte,
                ]);

                // 2. Sincronizamos las fechas del Período Académico asociado
                $periodo = $cohorte->periodosAcademicos()->first();
                if ($periodo) {
                    $periodo->update([
                        'fecha_inicio' => $request->fecha_inicio,
                        'fecha_fin'    => $request->fecha_fin,
                        // El estatus_periodo se actualiza automáticamente 
                        // gracias al evento "updated" en el modelo Cohorte.
                    ]);
                }
            });

            return redirect()->route('estructura.index')
                ->with('success', 'Cohorte y período académico actualizados correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la cohorte: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $cohorte = Cohorte::findOrFail($id);
            $cohorte->delete();

            return redirect()->route('estructura.index')
                ->with('success', 'Sello de cohorte eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('estructura.index')
                ->with('error', 'No se puede eliminar este sello de cohorte porque tiene períodos académicos vinculados.');
        }
    }
}