<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:80',
                Rule::unique('categorias', 'nombre')->where(function ($query) {
                    return $query
                        ->where('usuario_id', auth()->id())
                        ->where('tipo', $this->input('tipo'));
                }),
            ],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', Rule::in(config('categorias.icons'))],
            'color_hex' => ['required', 'string', Rule::in(config('categorias.colors'))],
        ];
    }
}
