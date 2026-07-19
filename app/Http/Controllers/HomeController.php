<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardMonthRequest;
use App\Services\DashboardSummaryService;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function __construct(
        private DashboardSummaryService $dashboardSummary
    ) {
        $this->middleware('auth');
    }

    public function index(DashboardMonthRequest $request)
    {
        $usuario = auth()->user();
        $chartData = $this->chartData($request);

        return view('home', [
            'cuentas' => $usuario->cuentas()->orderBy('nombre')->get(),
            'categorias' => $usuario->categorias()->orderBy('tipo')->orderBy('nombre')->get(),
            'summary' => $this->dashboardSummary->forUser($usuario),
            'expensesChart' => $chartData['chart'],
            'chartNavigation' => $chartData['navigation'],
        ]);
    }

    public function expensesChart(DashboardMonthRequest $request)
    {
        return response()->json($this->chartData($request));
    }

    private function chartData(DashboardMonthRequest $request): array
    {
        $usuario = auth()->user();
        $selectedMonth = $request->month();
        $now = Carbon::now(config('app.timezone'));

        $previousMonth = $selectedMonth->copy()->subMonth();
        $nextMonth = $selectedMonth->copy()->addMonth();

        return [
            'chart' => $this->dashboardSummary->expensesByCategoryForMonth($usuario, $selectedMonth),
            'navigation' => [
                'previous' => [
                    'mes' => $previousMonth->month,
                    'anio' => $previousMonth->year,
                ],
                'next' => [
                    'mes' => $nextMonth->month,
                    'anio' => $nextMonth->year,
                ],
                'canGoNext' => $nextMonth->copy()->startOfMonth()->lte($now->copy()->startOfMonth()),
            ],
        ];
    }
}
