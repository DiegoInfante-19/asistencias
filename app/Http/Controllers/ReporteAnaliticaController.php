<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnaliticaExport;
use App\Services\Analytics\AttendanceHierarchyService; // Importar el servicio

class ReporteAnaliticaController extends Controller
{
    /**
     * El servicio se inyecta directamente en el método.
     */
    public function exportar(Request $request, AttendanceHierarchyService $hierarchyService)
    {
        $tipo = $request->tipo;       // 'pnfs', 'empresas', 'estudiantes'
        $formato = $request->formato; // 'pdf', 'excel'

        // 1. OBTENER LOS DATOS REALES DIRECTO DEL SERVICIO
        // Utiliza la misma función exacta que alimenta las vistas AJAX.
        $datos = $hierarchyService->obtenerJerarquiaPnfEmpresas($request);

        // Parametros visuales para el encabezado del reporte
        $parametros = [
            'fecha_generacion' => now()->format('d/m/Y h:i A'),
            'pnf_id' => $request->pnf,
            'seccion_id' => $request->seccion,
            'empresa_id' => $request->empresa
        ];

        // 2. SELECCIONAR LA VISTA BLADE
        $vista = 'reportes.analitica.' . $tipo;

        // 3. GENERAR Y DESCARGAR EL ARCHIVO
        $nombreArchivo = 'Reporte_Analitica_' . ucfirst($tipo) . '_' . date('Ymd_His');

        if ($formato === 'pdf') {
            // Se usa 'landscape' (horizontal) para que los meses de la tabla quepan perfectamente
            $pdf = Pdf::loadView($vista, compact('datos', 'parametros'))->setPaper('A4', 'landscape');
            return $pdf->stream($nombreArchivo . '.pdf');
        } 
        
      if ($formato === 'excel') {
    $vistaExcel = 'reportes.analitica.excel.' . $tipo; // Apunta a las vistas optimizadas para Excel
    return Excel::download(new AnaliticaExport($vistaExcel, $datos, $parametros), $nombreArchivo . '.xlsx');
}

        abort(404, 'Formato de reporte no válido.');
    }
}