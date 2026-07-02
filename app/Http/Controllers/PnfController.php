<?php

namespace App\Http\Controllers;

use App\Models\Pnf;
use App\DataTables\PnfsDataTable;
use App\Http\Requests\StorePnfRequest;
use App\Http\Requests\UpdatePnfRequest;
use Illuminate\Database\QueryException;

class PnfController extends Controller
{
    /**
     * Muestra el catálogo de PNFs.
     */
    public function index(PnfsDataTable $dataTable)
    {
        return $dataTable->render('pnfs.index');
    }

    /**
     * Guarda un nuevo PNF.
     */
    public function store(StorePnfRequest $request)
    {
        Pnf::create($request->validated());

        return redirect()->route('pnfs.index')
            ->with('success', 'PNF registrado correctamente.');
    }

    /**
     * Actualiza un PNF existente.
     */
    public function update(UpdatePnfRequest $request, $id)
    {
        $pnf = Pnf::findOrFail($id);
        $pnf->update($request->validated());

        return redirect()->route('pnfs.index')
            ->with('success', 'PNF actualizado correctamente.');
    }

    /**
     *Elimina un PNF (protegido contra restricciones de clave foránea).
    */

    public function destroy($id)
    {
        try {
            $pnf = Pnf::findOrFail($id);
            $pnf->delete();

            return redirect()->route('pnfs.index')
                ->with('success', 'PNF eliminado correctamente.');
        } catch (QueryException $e) {
            // Un PNF suele estar atado fuertemente a la carga académica y convenios.
            return redirect()->route('pnfs.index')
                ->with('error', 'No se puede eliminar este PNF porque tiene acreditaciones, profesores o empresas vinculadas.');
        }
    }
}