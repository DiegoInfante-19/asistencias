<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\DataTables\PersonasDataTable; // Asegúrate de crear este DataTable luego
use Illuminate\Support\Facades\Log;

class PersonaController extends Controller
{
    /* Muestra la lista principal de personas usando DataTables.*/
    public function index(PersonasDataTable $dataTable){
        // El DataTable se encarga de procesar la búsqueda, paginación y ordenamiento.
        // Solo le decimos qué vista debe renderizar.
        return $dataTable->render('personas.index');
    }

    /* Muestra el formulario para registrar una nueva persona. */
    public function create(){
        // Si necesitas enviar catálogos para los selects (ej. Estados, Ciudades),
        // los consultas aquí y los pasas con compact().
        return view('personas.create');
    }

    /* Guarda la nueva persona en la base de datos. */
    public function store(StorePersonaRequest $request){
        try {
            Persona::create($request->validated());
            return redirect()->route('personas.index')
                             ->with('success', 'Estudiante registrado exitosamente.');
                             
        } catch (\Exception $e) {
            Log::error('Error registrando persona: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocurrió un error al intentar registrar al estudiante.');
        }
    }

    /*
    ¡LA JOYA DE LA CORONA!
     Muestra el expediente completo (Mega-CRUD) con sus pestañas.
     */
    // public function show($id)
    // {
        // Eager Loading: Cargamos a la persona y TODOS sus satélites en una sola 
        // consulta SQL súper optimizada. Esto alimenta las pestañas de tu vista.
        // $persona = Persona::with([
        //     'telefonos', 
        //     'observacion', 
        //     'empresaPersona', 
        //     'titulacion', 
        //     'formacionAcademica', 
        //     'inscripciones'
        // ])->findOrFail($id);

        // Aquí también enviaríamos los catálogos necesarios para los modales/formularios
        // de las pestañas (ej. lista de PNFs, Empresas, Cargos, etc.)
        // $pnfs = Pnf::all();
        // $empresas = Empresa::all();
        // ...

        // return view('personas.show', compact('persona'));
    // }

    public function show($id){
        $persona = Persona::with([
            'telefonos', 
            'observacion', 
            'empresaPersona', 
            'titulacion', 
            'formacionAcademica', 
            'inscripciones'
        ])->findOrFail($id);

        // Catálogos para Pestaña Titulación
        $pnfs = \App\Models\Pnf::all();
        $titulos = \App\Models\Titulo::all();
        $estatusExpedientes = \App\Models\EstatusExpediente::all();
        
        // Catálogo para Pestaña Inscripciones
        $cohortes = \App\Models\Cohorte::all();

        // NUEVO: Catálogos para Pestaña Perfil Laboral
        $empresas = \App\Models\Empresa::all();
        $cargos = \App\Models\Cargo::all();

        // Enviamos TOOOODO al Mega-CRUD
        return view('personas.show', compact('persona', 'pnfs', 'titulos', 'estatusExpedientes', 'cohortes', 'empresas', 'cargos'));
    }

    /**
     * Muestra el formulario para editar los datos básicos.
     */
    public function edit(Persona $persona)
    {
        return view('personas.edit', compact('persona'));
    }

    /**
     * Actualiza los datos básicos de la persona.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        try {
            $persona->update($request->validated());

            // Redirigimos de vuelta al expediente (show) para que la secretaria
            // siga viendo el perfil actualizado.
            return redirect()->route('personas.show', $persona->id_personas)
                             ->with('success', 'Datos del estudiante actualizados correctamente.');
                             
        } catch (\Exception $e) {
            Log::error('Error actualizando persona: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocurrió un error al actualizar los datos.');
        }
    }

    /**
     * Realiza un borrado (SoftDelete) de la persona.
     */
    public function destroy(Persona $persona)
    {
        try {
            // Elimina a la persona. Si configuraste eliminación en cascada en tu base de datos
            // o usas eventos en el modelo, sus satélites también se verán afectados lógicamente.
            $persona->delete();

            return redirect()->route('personas.index')
                             ->with('success', 'Estudiante eliminado del sistema.');
                             
        } catch (\Exception $e) {
            Log::error('Error eliminando persona: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocurrió un error al intentar eliminar el registro.');
        }
    }
}