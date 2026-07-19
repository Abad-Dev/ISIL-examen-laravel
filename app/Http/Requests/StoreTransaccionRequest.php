<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('categoria_id') === false) {
            $this->merge(['categoria_id' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'cuenta_id' => [
                'required',
                Rule::exists('cuentas', 'id')->where('usuario_id', auth()->id()),
            ],
            'categoria_id' => [
                'nullable',
                Rule::exists('categorias', 'id')->where(function ($query) {
                    return $query
                        ->where('usuario_id', auth()->id())
                        ->where('tipo', $this->input('tipo'));
                }),
            ],
            'tipo' => ['required', 'in:ingreso,gasto'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
        ];
    }
}
