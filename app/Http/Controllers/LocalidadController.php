<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Ciudad;
use App\DataTables\EstadosDataTable;
use App\DataTables\CiudadesDataTable;
use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\StoreCiudadRequest;
use App\Http\Requests\UpdateEstadoRequest;
use App\Http\Requests\UpdateCiudadRequest;
use Illuminate\Http\Request;

class LocalidadController extends Controller
{
    public function index(EstadosDataTable $estadosTable, CiudadesDataTable $ciudadesTable)
    {
        if (request()->ajax()) {
            if (request()->has('table') && request()->get('table') === 'ciudades') {
                return $ciudadesTable->ajax();
            }
            return $estadosTable->ajax();
        }

        $estados = Estado::all();

        return view('localidades.index', [
            'dataTable' => $estadosTable->html(),
            'ciudadesTable' => $ciudadesTable->html(),
            'estados' => $estados
        ]);
    }

    // --- MÉTODOS DE CREACIÓN ---
    public function storeEstado(StoreEstadoRequest $request)
    {
        Estado::create($request->validated());
        return redirect()->route('localidades.index')->with('success', 'Estado registrado correctamente.');
    }

    public function storeCiudad(StoreCiudadRequest $request)
    {
        // 1. Guardamos la ciudad y la asignamos a una variable
        $ciudad = Ciudad::create($request->validated());
        // 2. Si la petición es AJAX (como la de nuestro panel colapsable)
        if ($request->ajax() || $request->has('origen')) {
            return response()->json([
                'success' => true,
                'ciudad' => $ciudad
            ]);
        }
        // 3. Si es una petición normal desde el módulo de localidades, redirige
        return redirect()->route('localidades.index')->with('success', 'Ciudad registrada correctamente.');
    }

    // --- MÉTODOS DE ACTUALIZACIÓN ---
    public function updateEstado(UpdateEstadoRequest $request, $id)
    {
        $estado = Estado::findOrFail($id);
        $estado->update($request->validated());
        return redirect()->route('localidades.index')->with('success', 'Estado actualizado.');
    }

    public function updateCiudad(UpdateCiudadRequest $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->update($request->validated());
        return redirect()->route('localidades.index')->with('success', 'Ciudad actualizada.');
    }

    // --- API HELPER ---
    public function getCiudadesPorEstado($id_estado)
    {
        $ciudades = Ciudad::where('id_estado', $id_estado)->get();
        return response()->json($ciudades);
    }

    // app/Http/Controllers/LocalidadController.php

    public function destroyEstado($id)
    {
        try {
            $estado = Estado::findOrFail($id);
            $estado->delete();
            return redirect()->route('localidades.index')->with('success', 'Estado eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturamos el error si el estado tiene ciudades asociadas
            return redirect()->route('localidades.index')->with('error', 'No puedes eliminar este estado porque tiene ciudades asociadas.');
        }
    }

    public function destroyCiudad($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->delete();
        return redirect()->route('localidades.index')->with('success', 'Ciudad eliminada correctamente.');
    }
}


// namespace App\Http\Controllers;

// use App\Models\Estado;
// use App\Models\Ciudad;
// use App\DataTables\EstadosDataTable;
// use App\DataTables\CiudadesDataTable;
// use App\Http\Requests\StoreEstadoRequest;
// use App\Http\Requests\StoreCiudadRequest;

// class LocalidadController extends Controller
// {
//     public function index(EstadosDataTable $estadosTable, CiudadesDataTable $ciudadesTable)
//     {
//         // Si es una petición AJAX, detectamos cuál tabla está solicitando datos
//         if (request()->ajax()) {
//             if (request()->has('table') && request()->get('table') === 'ciudades') {
//                 return $ciudadesTable->ajax();
//             }
//             return $estadosTable->ajax();
//         }

//         $estados = \App\Models\Estado::all();

//         return view('localidades.index', [
//             'dataTable' => $estadosTable->html(),
//             'ciudadesTable' => $ciudadesTable->html(),
//             'estados' => $estados
//         ]);
//     }

//     public function storeEstado(StoreEstadoRequest $request)
//     {
//         Estado::create($request->validated());
//         return redirect()->route('localidades.index')->with('success', 'Estado registrado.');
//     }

//     public function storeCiudad(StoreCiudadRequest $request)
//     {
//         Ciudad::create($request->validated());
//         return redirect()->route('localidades.index')->with('success', 'Ciudad registrada.');
//     }

//     public function getCiudadesPorEstado($id_estado)
//     {
//         $ciudades = Ciudad::where('id_estado', $id_estado)->get();
//         return response()->json($ciudades);
//     }
//     public function updateEstado(Request $request, $id)
// {
//     $estado = Estado::findOrFail($id);
//     $estado->update($request->all());
//     return redirect()->route('localidades.index')->with('success', 'Estado actualizado.');
// }

// public function updateCiudad(Request $request, $id)
// {
//     $ciudad = Ciudad::findOrFail($id);
//     $ciudad->update($request->all());
//     return redirect()->route('localidades.index')->with('success', 'Ciudad actualizada.');
// }
// }
