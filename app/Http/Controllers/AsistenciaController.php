<?php

namespace App\Http\Controllers;
use App\Models\Asistencia;
use Illuminate\Http\RedirectResponse;
use App\Models\Miembro;
use Illuminate\Http\Request;
use App\Http\Requests\AsistenciaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $asistencias = Asistencia::paginate();
        return view('asistencias.index', compact('asistencias'))
            ->with('i', ($request->input('page', 1) - 1) * $asistencias->perPage());
    }

    public function reportes(){
        return view('asistencias.reportes');
    }

    public function pdf(Request $request){
        $asistencias = Asistencia::paginate(); 
        $pdf = Pdf::loadView('asistencias.pdf',['asistencias' => $asistencias]);
        return $pdf->stream();
        // $asistencias = Asistencia::paginate(); 
        // return view('asistencias.pdf', compact('asistencias'))
        //     ->with('i', ($request->input('page', 1) - 1) * $asistencias->perPage());
    }

    public function pdf_fechas(Request $request){
        $fi = $request->fi;
        $ff = $request->ff;
        $asistencias = Asistencia::where('fecha','>=', $fi)
        ->where('fecha','<=',$ff)
        ->get();

        $pdf = Pdf::loadView('asistencias.pdf_fechas',['asistencias' => $asistencias]);
        return $pdf->stream();
        // return view('asistencias.pdf_fechas',['asistencias'=>$asistencias]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $asistencia = new Asistencia();
        $miembros = Miembro::pluck('nombre_apellido','id');
        return view('asistencias.create', compact('asistencia','miembros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AsistenciaRequest $request): RedirectResponse
    {
        Asistencia::create($request->validated());

        return Redirect::route('asistencias.index')
            ->with('success', 'Asistencia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $asistencia = Asistencia::find($id);

        return view('asistencias.show', compact('asistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $asistencia = Asistencia::find($id);
        $miembros = Miembro::pluck('nombre_apellido','id');
        return view('asistencias.edit', compact('asistencia','miembros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AsistenciaRequest $request, Asistencia $asistencia): RedirectResponse
    {
        $asistencia->update($request->validated());

        return Redirect::route('asistencias.index')
            ->with('success', 'Asistencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Asistencia::find($id)->delete();

        return Redirect::route('asistencias.index')
            ->with('success', 'Asistencia deleted successfully');
    }
}
