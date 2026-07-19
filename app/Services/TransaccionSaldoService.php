<?php

namespace App\Services;

use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Support\Money;

class TransaccionSaldoService
{
    public function apply(Transaccion $transaccion): void
    {
        $this->adjust(
            (int) $transaccion->cuenta_id,
            (string) $transaccion->tipo,
            (string) $transaccion->monto,
            1
        );
    }

    public function reverse(Transaccion $transaccion): void
    {
        $this->adjust(
            (int) $transaccion->cuenta_id,
            (string) $transaccion->tipo,
            (string) $transaccion->monto,
            -1
        );
    }

    public function syncOnUpdate(Transaccion $transaccion, array $original): void
    {
        $saldoFields = ['cuenta_id', 'tipo', 'monto'];
        $changed = collect($saldoFields)->contains(fn (string $field) => $transaccion->{$field} != $original[$field]);

        if (! $changed) {
            return;
        }

        $this->adjust(
            (int) $original['cuenta_id'],
            (string) $original['tipo'],
            (string) $original['monto'],
            -1
        );

        $this->apply($transaccion);
    }

    private function adjust(int $cuentaId, string $tipo, string $monto, int $direction): void
    {
        $cuenta = Cuenta::query()->lockForUpdate()->findOrFail($cuentaId);
        $delta = $tipo === 'ingreso' ? $monto : Money::mul($monto, '-1');

        if ($direction === -1) {
            $delta = Money::mul($delta, '-1');
        }

        $saldoActual = $cuenta->saldo ?? '0.00';
        $cuenta->saldo = Money::add($saldoActual, $delta);
        $cuenta->save();
    }
}
