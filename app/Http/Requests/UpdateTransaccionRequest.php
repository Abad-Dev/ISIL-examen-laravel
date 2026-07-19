<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            '_transaccion_id' => $this->route('transaccion')?->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'cuenta_id' => [
                'required',
                Rule::exists('cuentas', 'id')->where('usuario_id', auth()->id()),
            ],
            'categoria_id' => [
                'required',
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
