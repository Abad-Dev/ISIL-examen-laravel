<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCuentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->saldo === '' || $this->saldo === null) {
            $this->merge(['saldo' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:efectivo,billetera_digital,banco,otro'],
            'saldo' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'icon' => ['required', 'string', Rule::in(config('cuentas.icons'))],
            'color_hex' => ['required', 'string', Rule::in(config('cuentas.colors'))],
        ];
    }
}
