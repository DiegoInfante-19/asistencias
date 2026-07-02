<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use App\DataTables\TitulosDataTable; // Lo crearemos en el Paso 5
use App\Http\Requests\StoreTituloRequest;
use App\Http\Requests\UpdateTituloRequest;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class TituloController extends Controller
{
    public function index(TitulosDataTable $dataTable)
    {
        return $dataTable->render('titulos.index');
    }

    public function store(StoreTituloRequest $request)
    {
        try {
            Titulo::create($request->validated());
            return redirect()->route('titulos.index')->with('success', 'Título registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Error al registrar el título: ' . $e->getMessage());
        }
    }

    public function update(UpdateTituloRequest $request, $id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->update($request->validated());
            return redirect()->route('titulos.index')->with('success', 'Título actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Error al actualizar el título.');
        }
    }

    public function destroy($id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->delete();
            return redirect()->route('titulos.index')->with('success', 'Título eliminado correctamente.');
        } catch (QueryException $e) {
            // Este catch es vital: captura errores de llave foránea (si el título está en uso)
            return redirect()->route('titulos.index')
                ->with('error', 'No se puede eliminar el título porque está siendo utilizado en otros registros.');
        } catch (\Exception $e) {
            return redirect()->route('titulos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
}