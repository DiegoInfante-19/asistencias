<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades;

class MiembroController extends Controller
{
    public function index()
    {
        $miembros = Miembro::all()->sortByDesc('id');
        return view('miembros.index', ['miembros' => $miembros]);
    }

    public function create()
    {
        return view(view: 'miembros.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse      
    {

        $request->validate([
            'nombre_apellido' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'email' => 'required',
            'ministerio' => 'required',
        ]);

        $miembro = new Miembro();
        $miembro->nombre_apellido = $request->nombre_apellido;
        $miembro->direccion = $request->direccion;
        $miembro->telefono = $request->telefono;
        $miembro->fecha_nacimiento = $request->fecha_nacimiento;
        $miembro->genero = $request->genero;
        $miembro->email = $request->email;
        $miembro->estado = '1';
        $miembro->ministerio = $request->ministerio;

        // Validación de fotografía
        if ($request->hasFile('fotografia')) {
            $miembro->fotografia = $request->file('fotografia')->store('fotografias_miembros', 'public');
        } else {
            $miembro->fotografia = ""; // O un valor por defecto
        }

        $miembro->fecha_ingreso = date('Y-m-d');

        // $miembro->fotografia = $request->file(key: 'fotografia')->store(path: 'fotografias_miembros', options: 'public'); 
        // $miembro->fecha_ingreso = '2023-01-01';

        $miembro->save();
        return redirect()->route('miembros.index')->with('mensaje', 'Miembro creado exitosamente.');
    }

    public function show($id){
        $miembro = Miembro::findOrFail($id);
        return view('miembros.show', compact('miembro'));
    }

    public function edit($id){
        $miembro = Miembro::findOrFail($id);
        return view('miembros.edit', compact('miembro'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'nombre_apellido' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'email' => 'required',
            'ministerio' => 'required',
        ]);

        $miembro = Miembro::findOrFail($id);
        $miembro->nombre_apellido = $request->nombre_apellido;
        $miembro->direccion = $request->direccion;
        $miembro->telefono = $request->telefono;
        $miembro->fecha_nacimiento = $request->fecha_nacimiento;
        $miembro->genero = $request->genero;
        $miembro->email = $request->email;
        $miembro->ministerio = $request->ministerio;

        if ($request->hasFile('fotografia')) {
            if ($miembro->fotografia) {
                Storage::disk('public')->delete($miembro->fotografia);
            }
            $miembro->fotografia = $request->file('fotografia')->store('fotografias_miembros', 'public');
        }

        $miembro->save();
        return redirect()->route('miembros.index')->with('mensaje', 'Se actualizo el miembro exitosamente.');
    }

    public function destroy($id){
         $miembro = Miembro::findOrFail($id);
        Miembro::destroy($id);
        Storage::disk('public')->delete($miembro->fotografia);
        return redirect()->route('miembros.index')->with('mensaje', 'Miembro eliminado exitosamente.');
    }

}