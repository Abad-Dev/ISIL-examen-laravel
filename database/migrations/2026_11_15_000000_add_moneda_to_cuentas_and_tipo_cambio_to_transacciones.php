<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->char('moneda', 3)->default('PEN')->after('tipo');
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->decimal('tipo_cambio', 10, 4)->nullable()->after('monto');
            $table->decimal('monto_destino', 10, 2)->nullable()->after('tipo_cambio');
        });
    }

    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropColumn(['tipo_cambio', 'monto_destino']);
        });

        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropColumn('moneda');
        });
    }
};
