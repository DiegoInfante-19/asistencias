<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TituloPnf;

class UpdateTituloPnfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // 1. Capturamos el ID del registro desde la ruta (ej: /pnfs/titulos/5)
        $idTituloPnf = $this->route('titulo_pnf');
        
        // 2. Buscamos a qué PNF pertenece este título para aislar la regla unique
        $tituloPnf = TituloPnf::find($idTituloPnf);
        $idPnf = $tituloPnf ? $tituloPnf->id_pnf : null;

        return [
            'id_titulo' => [
                'required',
                'integer',
                'exists:titulos,id_titulos',
            ],
            'nombre_titulo_pnf' => [
                'required',
                'string',
                'max:150',
                // Validación compuesta y blindada
                Rule::unique('titulos_pnf', 'nombre_titulo_pnf')
                    ->where('id_pnf', $idPnf) // Solo busca duplicados en el mismo PNF
                    ->ignore($idTituloPnf, 'id_titulos_pnf') // Ignora el registro actual
                    ->whereNull('deleted_at'), // Ignora los borrados lógicos
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_titulo.required'         => 'Debe seleccionar un título base del catálogo.',
            'id_titulo.exists'           => 'El título base seleccionado no es válido en el sistema.',
            'nombre_titulo_pnf.required' => 'El nombre específico del título es obligatorio.',
            'nombre_titulo_pnf.max'      => 'El nombre no debe exceder los 150 caracteres.',
            'nombre_titulo_pnf.unique'   => 'Este nombre de título ya está registrado y activo en este PNF.',
            'nombre_titulo_pnf.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
        ];
    }
}