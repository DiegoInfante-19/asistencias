<?php

namespace App\DataTables;

use App\Models\Sesion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Carbon\Carbon;

class SesionesSeccionDataTable extends BaseDataTable
{
    protected $id_sesion;

    public function withIdSeccion($idSeccion)
    {
        $this->id_sesion = $idSeccion;
        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('fecha_sesion', function ($sesion) {
                $carbonDate = Carbon::parse($sesion->fecha_sesion)->locale('es');
                $diaSemana = ucfirst($carbonDate->dayName);
                $diaMes = $carbonDate->format('d');
                $mes = $carbonDate->monthName;
                $anio = $carbonDate->format('Y');

                $formatoFecha = "{$diaSemana}, {$diaMes} de {$mes} del año {$anio}";

                return '<i class="bi bi-calendar-event me-1 text-secondary"></i> <span class="fw-semibold text-dark">' . $formatoFecha . '</span>';
            })
            ->addColumn('profesor_cargo', function ($sesion) {
                $nombre = $sesion->profesor->user->name_users ?? 'No asignado';
                $apellido = $sesion->profesor->user->last_name_users ?? '';
                return trim($nombre . ' ' . $apellido);
            })
            ->addColumn('estado_asistencia', function ($sesion) {
                if ($sesion->asistencias_count == 0) {
                    return '<h5><span class="badge bg-danger shadow-sm"> Pendiente</span></h5>';
                }

                if ($sesion->estaCerrada()) {
                    return '<h5><span class="badge bg-secondary shadow-sm"> Registrada (Cerrada)</span></h5>';
                }

                $horas = $sesion->horasRestantesEdicion();
                return '
                    <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Registrada</span>
                    <div class="text-muted small fw-bold mt-1" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-history text-warning"></i> Quedan ' . $horas . 'h para editar
                    </div>
                ';
            })
            ->filterColumn('fecha_sesion', function ($query, $keyword) {
                $query->where('sesiones.fecha_sesion', 'like', "%{$keyword}%");
            })
            ->filterColumn('profesor_cargo', function ($query, $keyword) {
                $query->whereHas('profesor.user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(name_users, ' ', last_name_users) like ?", ["%{$keyword}%"])
                      ->orWhere('email_users', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('estado_asistencia', function ($query, $keyword) {
                $keywordLower = strtolower($keyword);
                if (str_contains('pendiente', $keywordLower)) {
                    $query->having('asistencias_count', '=', 0);
                } elseif (str_contains('registrada', $keywordLower)) {
                    $query->having('asistencias_count', '>', 0);
                }
            })
            ->orderColumn('fecha_sesion', function ($query, $order) {
                $query->orderBy('sesiones.fecha_sesion', $order);
            })
            ->orderColumn('profesor_cargo', function ($query, $order) {
                $query->orderBy('users.name_users', $order)
                      ->orderBy('users.last_name_users', $order);
            })
            ->addColumn('action', function ($sesion) {
                if ($sesion->relationLoaded('seccion') && $sesion->seccion && !$sesion->seccion->estaActiva()) {
                    return '<span class="badge bg-secondary">Bloqueado (Inactiva)</span>';
                }
                
                if (!$sesion->seccion->estaActiva()) {
                    return '<span class="badge bg-secondary">Bloqueado (Inactiva)</span>';
                }

                return view('secciones.partials.actions_sesiones', compact('sesion'))->render();
            })
            ->rawColumns(['fecha_sesion', 'estado_asistencia', 'action'])
            ->setRowId('id_sesiones');
    }

    public function query(Sesion $model): QueryBuilder
    {
        $seccionId = $this->id_sesion;
        if (!$seccionId && request()->route('seccion')) {
            $seccion = request()->route('seccion');
            $seccionId = is_object($seccion) ? ($seccion->id_seccion ?? null) : $seccion;
        }

        return $model->newQuery()
            ->select('sesiones.*')
            ->join('profesores', 'sesiones.id_profesor', '=', 'profesores.id_profesor')
            ->join('users', 'profesores.id_users', '=', 'users.id_users')
            ->with(['profesor.user', 'seccion'])
            ->withCount('asistencias')
            ->where('sesiones.id_seccion', $seccionId);
    }

    protected function getTableId(): string
    {
        return 'sesiones-seccion-table';
    }

    public function html(): HtmlBuilder
    {
        $seccionId = $this->id_sesion;
        if (!$seccionId && request()->route('seccion')) {
            $seccion = request()->route('seccion');
            $seccionId = is_object($seccion) ? ($seccion->id_seccion ?? null) : $seccion;
        }

        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('secciones.show', ['seccion' => $seccionId, 'table' => 'sesiones-seccion-table']),
                'data' => 'function(d) { d.table = "sesiones-seccion-table"; }'
            ])
            ->orderBy(0, 'desc');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('fecha_sesion')->name('sesiones.fecha_sesion')->title('Fecha')->searchable(true)->orderable(true),
            Column::make('profesor_cargo')->name('users.name_users')->title('Profesor a Cargo')->searchable(true)->orderable(true),
            // Cambiado a Column::make para que admita name y filtros de forma consistente en Yajra
            Column::make('estado_asistencia')->name('estado_asistencia')->title('Estado')->searchable(true)->orderable(false)->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center align-middle'),
        ];
    }
}