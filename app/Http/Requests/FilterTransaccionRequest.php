<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        foreach (['fecha_desde', 'fecha_hasta', 'cuenta_id', 'categoria_id', 'tipo', 'monto_min', 'monto_max'] as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $merge[$field] = null;
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after_or_equal:fecha_desde'],
            'cuenta_id' => [
                'nullable',
                Rule::exists('cuentas', 'id')->where('usuario_id', auth()->id()),
            ],
            'categoria_id' => [
                'nullable',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === 'none') {
                        return;
                    }

                    if (! auth()->user()->categorias()->whereKey($value)->exists()) {
                        $fail(__('validation.exists', ['attribute' => __('Category')]));
                    }
                },
            ],
            'tipo' => ['nullable', 'in:ingreso,gasto'],
            'monto_min' => ['nullable', 'numeric', 'min:0'],
            'monto_max' => ['nullable', 'numeric', 'min:0', Rule::when(
                $this->filled('monto_min'),
                'gte:monto_min'
            )],
        ];
    }

    public function filters(): array
    {
        return $this->validated();
    }

    public function hasActiveFilters(): bool
    {
        return collect($this->filters())
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->isNotEmpty();
    }
}
