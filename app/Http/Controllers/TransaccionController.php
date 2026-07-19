<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaccionRequest;
use App\Http\Requests\UpdateTransaccionRequest;
use App\Models\Transaccion;
use App\Services\TransaccionSaldoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransaccionController extends Controller
{
    public function __construct(
        private TransaccionSaldoService $saldoService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $usuario = auth()->user();

        return view('transacciones.index', [
            'transacciones' => $usuario->transacciones()
                ->with(['cuenta', 'categoria'])
                ->orderByDesc('fecha')
                ->orderByDesc('id')
                ->get(),
            'cuentas' => $usuario->cuentas()->orderBy('nombre')->get(),
            'categorias' => $usuario->categorias()->orderBy('tipo')->orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreTransaccionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $transaccion = auth()->user()->transacciones()->create($request->validated());
            $this->saldoService->apply($transaccion);
        });

        return redirect()
            ->route('web.transacciones.index')
            ->with('status', __('Transaction created successfully.'));
    }

    public function update(UpdateTransaccionRequest $request, Transaccion $transaccion): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        DB::transaction(function () use ($request, $transaccion) {
            $original = $transaccion->only(['cuenta_id', 'tipo', 'monto']);
            $transaccion->update($request->validated());
            $this->saldoService->syncOnUpdate($transaccion, $original);
        });

        return redirect()
            ->route('web.transacciones.index')
            ->with('status', __('Transaction updated successfully.'));
    }

    public function destroy(Transaccion $transaccion): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        DB::transaction(function () use ($transaccion) {
            $this->saldoService->reverse($transaccion);
            $transaccion->delete();
        });

        return redirect()
            ->route('web.transacciones.index')
            ->with('status', __('Transaction deleted successfully.'));
    }

    private function authorizeTransaccion(Transaccion $transaccion): void
    {
        if ($transaccion->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
