<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class DashboardMonthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $now = Carbon::now(config('app.timezone'));

        $this->merge([
            'mes' => $this->input('mes', $now->month),
            'anio' => $this->input('anio', $now->year),
        ]);
    }

    public function rules(): array
    {
        return [
            'mes' => ['required', 'integer', 'min:1', 'max:12'],
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $month = $this->month();

            if ($month->month !== (int) $this->input('mes')) {
                $validator->errors()->add('mes', __('Invalid month.'));
            }
        });
    }

    public function month(): Carbon
    {
        $now = Carbon::now(config('app.timezone'));

        try {
            $month = Carbon::createFromDate(
                (int) $this->input('anio'),
                (int) $this->input('mes'),
                1,
                config('app.timezone')
            )->startOfMonth();
        } catch (\Exception) {
            return $now->copy()->startOfMonth();
        }

        if ($month->month !== (int) $this->input('mes')) {
            return $now->copy()->startOfMonth();
        }

        if ($month->greaterThan($now->copy()->startOfMonth())) {
            return $now->copy()->startOfMonth();
        }

        return $month;
    }
}
