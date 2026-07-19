<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Carbon;

class DashboardSummaryService
{
    public function forUser(Usuario $usuario): array
    {
        $now = Carbon::now(config('app.timezone'));

        return [
            'totalBalance' => (string) ($usuario->cuentas()->sum('saldo') ?? '0'),
            'today' => $this->periodStats(
                $usuario,
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
            ),
            'week' => $this->periodStats(
                $usuario,
                $now->copy()->startOfWeek(Carbon::MONDAY),
                $now->copy()->endOfWeek(Carbon::MONDAY),
            ),
            'month' => $this->periodStats(
                $usuario,
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ),
        ];
    }

    private function periodStats(Usuario $usuario, Carbon $from, Carbon $to): array
    {
        $fromDate = $from->toDateString();
        $toExclusive = $to->copy()->addDay()->toDateString();

        $ingresos = (string) $usuario->transacciones()
            ->where('tipo', 'ingreso')
            ->where('fecha', '>=', $fromDate)
            ->where('fecha', '<', $toExclusive)
            ->sum('monto');

        $gastos = (string) $usuario->transacciones()
            ->where('tipo', 'gasto')
            ->where('fecha', '>=', $fromDate)
            ->where('fecha', '<', $toExclusive)
            ->sum('monto');

        return [
            'ingresos' => $ingresos ?: '0',
            'gastos' => $gastos ?: '0',
            'neto' => bcsub($ingresos ?: '0', $gastos ?: '0', 2),
        ];
    }
}
