<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCuentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->saldo === '') {
            $this->merge(['saldo' => null]);
        }

        $this->merge([
            '_cuenta_id' => $this->route('cuenta')?->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:efectivo,billetera_digital,banco,otro'],
            'saldo' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'icon' => ['required', 'string', Rule::in(config('cuentas.icons'))],
            'color_hex' => ['required', 'string', Rule::in(config('cuentas.colors'))],
        ];
    }
}
