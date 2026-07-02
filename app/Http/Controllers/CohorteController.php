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
        return $dataTable->render('cohortes.index');
    }

    public function store(StoreCohorteRequest $request)
    {
        Cohorte::create($request->validated());

        return redirect()->route('cohortes.index')
            ->with('success', 'Cohorte registrado correctamente.');
    }

    public function update(UpdateCohorteRequest $request, $id)
    {
        $cohorte = Cohorte::findOrFail($id);
        $cohorte->update($request->validated());

        return redirect()->route('cohortes.index')
            ->with('success', 'Cohorte actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $cohorte = Cohorte::findOrFail($id);
            $cohorte->delete();

            return redirect()->route('cohortes.index')
                ->with('success', 'Cohorte eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('cohortes.index')
                ->with('error', 'No se puede eliminar este cohorte porque tiene registros vinculados.');
        }
    }
}