<?php

namespace App\Services;

use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Support\Money;

class TransaccionSaldoService
{
    public function apply(Transaccion $transaccion): void
    {
        $this->applyValues($transaccion->only(['cuenta_id', 'cuenta_destino_id', 'tipo', 'monto']), 1);
    }

    public function reverse(Transaccion $transaccion): void
    {
        $this->applyValues($transaccion->only(['cuenta_id', 'cuenta_destino_id', 'tipo', 'monto']), -1);
    }

    public function syncOnUpdate(Transaccion $transaccion, array $original): void
    {
        $saldoFields = ['cuenta_id', 'cuenta_destino_id', 'tipo', 'monto'];
        $changed = collect($saldoFields)->contains(fn (string $field) => $transaccion->{$field} != ($original[$field] ?? null));

        if (! $changed) {
            return;
        }

        $this->applyValues($original, -1);
        $this->apply($transaccion);
    }

    private function applyValues(array $values, int $direction): void
    {
        $monto = (string) $values['monto'];

        if ($values['tipo'] === 'transferencia') {
            $this->adjust((int) $values['cuenta_id'], Money::mul($monto, '-1'), $direction);
            $this->adjust((int) $values['cuenta_destino_id'], $monto, $direction);

            return;
        }

        $delta = $values['tipo'] === 'ingreso' ? $monto : Money::mul($monto, '-1');
        $this->adjust((int) $values['cuenta_id'], $delta, $direction);
    }

    private function adjust(int $cuentaId, string $delta, int $direction): void
    {
        $cuenta = Cuenta::query()->findOrFail($cuentaId);
        // Las cuentas vinculadas comparten el saldo de su cuenta principal.
        $titular = Cuenta::query()->lockForUpdate()->findOrFail($cuenta->cuenta_principal_id ?? $cuenta->id);

        if ($direction === -1) {
            $delta = Money::mul($delta, '-1');
        }

        $titular->saldo = Money::add($titular->saldo ?? '0.00', $delta);
        $titular->save();
    }
}
