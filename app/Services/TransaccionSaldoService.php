<?php

namespace App\Services;

use App\Models\Cuenta;
use App\Models\Transaccion;

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
        $delta = $tipo === 'ingreso' ? $monto : \bcmul($monto, '-1', 2);

        if ($direction === -1) {
            $delta = \bcmul($delta, '-1', 2);
        }

        $saldoActual = $cuenta->saldo ?? '0.00';
        $cuenta->saldo = \bcadd($saldoActual, $delta, 2);
        $cuenta->save();
    }
}
