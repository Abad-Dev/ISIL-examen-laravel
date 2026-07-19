<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaccionRequest;
use App\Http\Requests\UpdateTransaccionRequest;
use App\Models\Transaccion;
use App\Services\TransaccionSaldoService;
use Illuminate\Support\Facades\DB;

class TransaccionController extends Controller
{
    public function __construct(
        private TransaccionSaldoService $saldoService
    ) {}

    public function index()
    {
        return auth()->user()
            ->transacciones()
            ->with(['cuenta', 'categoria'])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();
    }

    public function store(StoreTransaccionRequest $request)
    {
        $transaccion = DB::transaction(function () use ($request) {
            $transaccion = auth()->user()->transacciones()->create($request->validated());
            $this->saldoService->apply($transaccion);

            return $transaccion->load(['cuenta', 'categoria']);
        });

        return response()->json($transaccion, 201);
    }

    public function show(Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        return $transaccion->load(['cuenta', 'categoria']);
    }

    public function update(UpdateTransaccionRequest $request, Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        $transaccion = DB::transaction(function () use ($request, $transaccion) {
            $original = $transaccion->only(['cuenta_id', 'tipo', 'monto']);
            $transaccion->update($request->validated());
            $this->saldoService->syncOnUpdate($transaccion, $original);

            return $transaccion->load(['cuenta', 'categoria']);
        });

        return response()->json($transaccion);
    }

    public function destroy(Transaccion $transaccion)
    {
        $this->authorizeOwner($transaccion);

        DB::transaction(function () use ($transaccion) {
            $this->saldoService->reverse($transaccion);
            $transaccion->delete();
        });

        return response()->noContent();
    }

    private function authorizeOwner(Transaccion $transaccion): void
    {
        if ($transaccion->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
