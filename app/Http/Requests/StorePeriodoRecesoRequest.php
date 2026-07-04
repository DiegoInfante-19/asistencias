<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodoRecesoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'suspension_actividades' => $this->boolean('suspension_actividades'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre_periodo_receso' => [
                'required',
                'string',
                'max:100',
                'unique:periodo_recesos,nombre_periodo_receso',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,\-]+$/'
            ],
            'fecha_inicio_periodo_receso' => [
                'required',
                'date'
            ],
            'fecha_fin_periodo_receso' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio_periodo_receso'
            ],
            'descripcion_periodo_receso' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'nivel_periodo_receso' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'suspension_actividades' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_periodo_receso.required'       => 'El nombre del periodo es obligatorio.',
            'nombre_periodo_receso.max'            => 'El nombre no puede exceder los 100 caracteres.',
            'nombre_periodo_receso.unique'         => 'Este periodo ya se encuentra registrado.',
            'nombre_periodo_receso.regex'          => 'El formato del nombre contiene caracteres no permitidos.',
            
            'fecha_inicio_periodo_receso.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio_periodo_receso.date'     => 'La fecha de inicio no tiene un formato válido.',
            
            'fecha_fin_periodo_receso.required'    => 'La fecha de culminación es obligatoria.',
            'fecha_fin_periodo_receso.date'        => 'La fecha de culminación no tiene un formato válido.',
            'fecha_fin_periodo_receso.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            
            'descripcion_periodo_receso.max'       => 'La descripción no puede exceder los 1000 caracteres.',
            
            'nivel_periodo_receso.required'        => 'Debe especificar el nivel o tipo de evento.',
            'nivel_periodo_receso.max'             => 'El nivel no puede exceder los 50 caracteres.',
            'nivel_periodo_receso.regex'           => 'El nivel solo debe contener letras y espacios.',
            
            'suspension_actividades.required'      => 'Debe indicar si hay suspensión de actividades.',
            'suspension_actividades.boolean'       => 'El valor de suspensión no es válido.',
        ];
    }
}