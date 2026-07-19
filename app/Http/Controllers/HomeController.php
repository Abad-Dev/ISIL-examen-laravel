<?php

namespace App\Http\Controllers;

use App\Services\DashboardSummaryService;

class HomeController extends Controller
{
    public function __construct(
        private DashboardSummaryService $dashboardSummary
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = auth()->user();

        return view('home', [
            'cuentas' => $usuario->cuentas()->orderBy('nombre')->get(),
            'categorias' => $usuario->categorias()->orderBy('tipo')->orderBy('nombre')->get(),
            'summary' => $this->dashboardSummary->forUser($usuario),
        ]);
    }
}
