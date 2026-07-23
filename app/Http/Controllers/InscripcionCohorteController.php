<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCohorte;
use App\Models\GrupoAcademico;
use App\Models\Persona;
use App\Http\Requests\StoreInscripcionCohorteRequest;
use Illuminate\Http\Request;

class InscripcionCohorteController extends Controller
{
    /**
     * Muestra la lista de inscripciones.
     */
    public function index()
    {
        $inscripciones = InscripcionCohorte::with(['persona', 'grupo.cohorte', 'grupo.pnf'])
            ->latest()
            ->paginate(15);

        return view('inscripciones.index', compact('inscripciones'));
    }

    /**
     * Muestra el formulario para crear una nueva inscripción.
     */
    public function create()
    {
        $personas = Persona::orderBy('primer_apellido_personas')->get();
        
        // Cargar los grupos activos con su Cohorte y PNF para el selector
        $grupos = GrupoAcademico::with(['cohorte', 'pnf'])
            ->where('estatus_grupo', 'Activo')
            ->get();

        return view('inscripciones.create', compact('personas', 'grupos'));
    }

    /**
     * Guarda la nueva inscripción ligada al Grupo Académico.
     */
    public function store(StoreInscripcionCohorteRequest $request)
    {
        // Los datos ya vienen convalidados por el Form Request (id_personas, id_grupo, fecha, etc.)
        InscripcionCohorte::create($request->validated());

        return redirect()->route('inscripciones.index')
            ->with('success', 'Participante inscrito exitosamente en el grupo académico.');
    }
}