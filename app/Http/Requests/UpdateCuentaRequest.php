<?php

namespace App\Http\Requests;

class UpdateCuentaRequest extends StoreCuentaRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            '_cuenta_id' => $this->route('cuenta')?->id,
        ]);
    }

    public function rules(): array
    {
        $cuenta = $this->route('cuenta');

        $rules = parent::rules();
        $rules['cuenta_principal_id'][] = 'not_in:'.$cuenta->id;
        $rules['cuenta_principal_id'][] = function (string $attribute, mixed $value, \Closure $fail) use ($cuenta): void {
            if ($value !== null && $cuenta->vinculadas()->exists()) {
                $fail(__('This account already has linked accounts.'));
            }
        };

        return $rules;
    }
}
