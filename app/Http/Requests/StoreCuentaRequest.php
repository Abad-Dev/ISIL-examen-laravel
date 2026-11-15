<?php

namespace App\Http\Requests;

use App\Models\Cuenta;
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
        if ($this->saldo === '') {
            $this->merge(['saldo' => null]);
        }

        if ($this->filled('cuenta_principal_id') === false) {
            $this->merge(['cuenta_principal_id' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:efectivo,billetera_digital,banco,otro'],
            'moneda' => ['required', Rule::in(array_keys(config('money.currencies')))],
            'cuenta_principal_id' => [
                'nullable',
                Rule::exists('cuentas', 'id')
                    ->where('usuario_id', auth()->id())
                    ->whereNull('cuenta_principal_id'),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $principal = Cuenta::find($value);

                    if ($principal && $principal->moneda !== $this->input('moneda')) {
                        $fail(__('Unified accounts must use the same currency.'));
                    }
                },
            ],
            'saldo' => ['required_without:cuenta_principal_id', 'nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'icon' => ['required', 'string', Rule::in(config('cuentas.icons'))],
            'color_hex' => ['required', 'string', Rule::in(config('cuentas.colors'))],
        ];
    }

    public function attributes(): array
    {
        return [
            'cuenta_principal_id' => __('Shares balance with'),
            'moneda' => __('Currency'),
        ];
    }
}
