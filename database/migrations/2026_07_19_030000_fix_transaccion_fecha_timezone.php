<?php

use App\Models\Transaccion;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $timezone = config('app.timezone', 'America/Lima');

        Transaccion::query()->each(function (Transaccion $transaccion) use ($timezone) {
            $createdAt = $transaccion->created_at?->timezone($timezone);

            if ($createdAt === null) {
                return;
            }

            $localDate = $createdAt->toDateString();
            $utcDate = $transaccion->created_at->utc()->toDateString();
            $storedDate = substr((string) $transaccion->getRawOriginal('fecha'), 0, 10);

            if ($storedDate === $utcDate && $storedDate !== $localDate) {
                $transaccion->update(['fecha' => $localDate]);
            }
        });
    }

    public function down(): void
    {
        // No reversible: fechas corregidas no deben volver al valor UTC automático.
    }
};
