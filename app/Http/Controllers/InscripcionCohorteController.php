<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCohorte;
use App\Models\GrupoAcademico;
use App\Models\Persona;
use App\Models\Cohorte;
use App\Http\Requests\StoreInscripcionCohorteRequest;
use Illuminate\Http\Request;

class InscripcionCohorteController extends Controller
{
    /**
     * Muestra la lista general de inscripciones.
     */
    public function index()
    {
        // Cargamos las inscripciones con sus relaciones (Eager Loading) para evitar N+1 queries
        $inscripciones = InscripcionCohorte::with(['persona', 'grupo.cohorte', 'grupo.pnf'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('inscripciones.index', compact('inscripciones'));
    }

    /**
     * Muestra el formulario para crear una nueva inscripción.
     */
    public function create()
    {
        // 1. Traemos a todas las personas ordenadas por apellido
        $personas = Persona::orderBy('primer_apellido_personas')->get();

        // 2. Traemos SOLO las cohortes activas (Ej. III Cohorte: 2025-2026)
        $cohortesActivas = Cohorte::where('estatus_cohorte', 'Activo')->get();

        // 3. Pre-procesamos los grupos activos para alimentar los Selects en Cascada de la vista (JavaScript)
        $gruposData = GrupoAcademico::with(['pnf'])
            ->whereHas('cohorte', function ($query) {
                // Doble validación: El grupo debe pertenecer a una cohorte activa
                $query->where('estatus_cohorte', 'Activo');
            })
            ->where('estatus_grupo', 'Activo')
            ->get()
            ->map(function ($grupo) {
                // Formateamos los datos como un array simple para que JavaScript lo procese fácilmente
                return [
                    'id_grupo'        => $grupo->id_grupo,
                    'id_cohortes'     => $grupo->id_cohortes,
                    'id_pnf'          => $grupo->id_pnf,
                    'nombre_pnf'      => $grupo->pnf->nombre_pnf,
                    // Si nivel_academico es un Enum de PHP 8.1, extraemos su valor (value), si es string lo pasamos directo
                    'nivel_academico' => $grupo->nivel_academico->value ?? $grupo->nivel_academico,
                ];
            });

        return view('inscripciones.create', compact('personas', 'cohortesActivas', 'gruposData'));
    }

    /**
     * Guarda la nueva inscripción en la base de datos.
     */
    public function store(StoreInscripcionCohorteRequest $request)
    {
        // El StoreInscripcionCohorteRequest ya se encargó de verificar que el alumno
        // no esté doblemente inscrito de forma activa en otro grupo.
        InscripcionCohorte::create($request->validated());

        return redirect()->route('inscripciones.index')
            ->with('success', 'Participante inscrito exitosamente en el grupo académico.');
    }
}