<?php

use App\Models\Transaccion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        $timezone = config('app.timezone', 'America/Lima');

        Transaccion::query()->each(function (Transaccion $transaccion) use ($timezone) {
            $createdAtUtc = Carbon::parse($transaccion->getRawOriginal('created_at'), 'UTC');
            $localDate = $createdAtUtc->copy()->timezone($timezone)->toDateString();
            $utcDate = $createdAtUtc->toDateString();
            $storedDate = substr((string) $transaccion->getRawOriginal('fecha'), 0, 10);

            if ($storedDate === $utcDate && $storedDate !== $localDate) {
                $transaccion->update(['fecha' => $localDate]);
            }
        });
    }

    public function down(): void
    {
        //
    }
};
