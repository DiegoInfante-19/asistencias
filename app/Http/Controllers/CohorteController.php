<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\DataTables\CohortesDataTable;
use App\Http\Requests\StoreCohorteRequest;
use App\Http\Requests\UpdateCohorteRequest;
use Illuminate\Database\QueryException;

class CohorteController extends Controller
{
    public function index(CohortesDataTable $dataTable)
    {
        // Si es una petición asíncrona de DataTables, devolvemos exclusivamente el JSON
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        // Si es una entrada normal por el navegador, renderizamos la vista completa
        return $dataTable->render('cohortes.index');
    }

    public function store(StoreCohorteRequest $request)
    {
        Cohorte::create($request->validated());

        return redirect()->route('estructura.index')
            ->with('success', 'Sello de cohorte registrado correctamente.');
    }

    public function update(UpdateCohorteRequest $request, $id)
    {
        $cohorte = Cohorte::findOrFail($id);
        $cohorte->update($request->validated());

        return redirect()->route('estructura.index')
            ->with('success', 'Sello de cohorte actualizado correctamente.');
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