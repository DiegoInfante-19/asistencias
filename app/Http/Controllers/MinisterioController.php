<?php

namespace App\Http\Controllers;

use App\Models\Ministerio;
use Illuminate\Http\Request;

class MinisterioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ministerios = Ministerio::all();
        return view('ministerios.index', ['ministerios' => $ministerios]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ministerios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $ministerio = request()->all();
        // return response()->json($ministerio);
        $request->validate([
            'nombre_ministerio' => 'required',
            'fecha_ingreso' => 'required',
        ]);

        $ministerio = new Ministerio();

        $ministerio->nombre_ministerio = $request->nombre_ministerio;
        $ministerio->descripcion = $request->descripcion;
        $ministerio->estado = '1';
        $ministerio->fecha_ingreso = $request->fecha_ingreso;
        $ministerio->save();

        return redirect()->route('ministerios.index')->with('success', 'Se registro el ministerio de manera correcta');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ministerio $ministerio){
        $ministerio = Ministerio::findOrFail($ministerio->id);
        return view('ministerios.show', ['ministerio' => $ministerio]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ministerio $ministerio){
        $ministerio = Ministerio::findOrFail($ministerio->id);
        return view('ministerios.edit', ['ministerio' => $ministerio]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $request->validate([
            'nombre_ministerio' => 'required',
            'fecha_ingreso' => 'required',
        ]);

        $ministerio = Ministerio::findOrFail($id);
        $ministerio->nombre_ministerio = $request->nombre_ministerio;
        $ministerio->descripcion = $request->descripcion;
        $ministerio->estado = '1';
        $ministerio->fecha_ingreso = $request->fecha_ingreso;
        $ministerio->save();

        return redirect()->route('ministerios.index')->with('success', 'Se actualizo el ministerio de manera correcta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        Ministerio::destroy($id);
        return redirect()->route('ministerios.index')->with('success', 'Se elimino el ministerio de manera correcta');
        
    }
}
