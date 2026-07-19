<?php

namespace App\Services;

use App\Models\Usuario;
use App\Support\DateFormat;
use App\Support\Money;
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

    public function expensesByCategoryForMonth(Usuario $usuario, Carbon $month): array
    {
        $fromDate = $month->copy()->startOfMonth()->toDateString();
        $toExclusive = $month->copy()->startOfMonth()->addMonth()->toDateString();

        $categories = $usuario->transacciones()
            ->with('categoria:id,nombre,color_hex')
            ->where('tipo', 'gasto')
            ->where('fecha', '>=', $fromDate)
            ->where('fecha', '<', $toExclusive)
            ->get()
            ->groupBy(fn ($transaccion) => $transaccion->categoria_id ?? 'none')
            ->map(function ($group) {
                $transaccion = $group->first();

                return [
                    'label' => $transaccion->categoria?->nombre ?? __('No category'),
                    'total' => round((float) $group->sum('monto'), 2),
                    'color' => $transaccion->categoria?->color_hex ?? '#94a3b8',
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->all();

        return [
            'mes' => $month->month,
            'anio' => $month->year,
            'label' => DateFormat::monthYear($month->copy()->startOfMonth()),
            'categories' => $categories,
            'total' => round(array_sum(array_column($categories, 'total')), 2),
        ];
    }

    private function periodStats(Usuario $usuario, Carbon $from, Carbon $to): array
    {
        $fromDate = $from->toDateString();
        $toExclusive = $to->copy()->addDay()->toDateString();

        $ingresos = (string) ($usuario->transacciones()
            ->where('tipo', 'ingreso')
            ->where('fecha', '>=', $fromDate)
            ->where('fecha', '<', $toExclusive)
            ->sum('monto') ?? '0');

        $gastos = (string) ($usuario->transacciones()
            ->where('tipo', 'gasto')
            ->where('fecha', '>=', $fromDate)
            ->where('fecha', '<', $toExclusive)
            ->sum('monto') ?? '0');

        return [
            'ingresos' => $ingresos ?: '0',
            'gastos' => $gastos ?: '0',
            'neto' => Money::sub($ingresos ?: '0', $gastos ?: '0'),
        ];
    }
}
