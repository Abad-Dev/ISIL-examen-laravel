<?php

namespace App\Http\Requests;

use App\Models\Cuenta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaccionRequest extends FormRequest
{
    public const NUEVA_CATEGORIA = 'nueva';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('tipo') === 'transferencia') {
            $this->merge(['categoria_id' => null, 'nueva_categoria' => null]);
        } else {
            $this->merge(['cuenta_destino_id' => null]);
        }

        if ($this->input('categoria_id') !== self::NUEVA_CATEGORIA) {
            $this->merge(['nueva_categoria' => null]);
        }

        if ($this->filled('categoria_id') === false || $this->input('categoria_id') === self::NUEVA_CATEGORIA) {
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
            'cuenta_destino_id' => [
                'nullable',
                'required_if:tipo,transferencia',
                'different:cuenta_id',
                Rule::exists('cuentas', 'id')->where('usuario_id', auth()->id()),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $origen = Cuenta::find($this->input('cuenta_id'));
                    $destino = Cuenta::find($value);

                    if ($origen && $destino && $origen->titularSaldo()->is($destino->titularSaldo())) {
                        $fail(__('Both accounts share the same balance.'));
                    }
                },
            ],
            'categoria_id' => [
                'nullable',
                Rule::exists('categorias', 'id')->where(function ($query) {
                    return $query
                        ->where('usuario_id', auth()->id())
                        ->where('tipo', $this->input('tipo'));
                }),
            ],
            'nueva_categoria' => ['nullable', 'string', 'max:80'],
            'tipo' => ['required', 'in:ingreso,gasto,transferencia'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cuenta_destino_id' => __('Destination account'),
            'nueva_categoria' => __('New category'),
        ];
    }

    /**
     * Datos de la transacción, creando la categoría nueva si se escribió una.
     */
    public function transaccionData(): array
    {
        $data = collect($this->validated())->except('nueva_categoria')->all();
        $nombre = trim((string) $this->validated('nueva_categoria'));

        if ($nombre !== '') {
            $data['categoria_id'] = auth()->user()->categorias()->firstOrCreate(
                ['nombre' => $nombre, 'tipo' => $data['tipo']],
                [
                    'icon' => config('categorias.icons')[0],
                    'color_hex' => config('categorias.colors')[0],
                    'activo' => true,
                    'orden' => 0,
                ],
            )->id;
        }

        return $data;
    }
}
