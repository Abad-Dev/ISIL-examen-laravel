<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->foreignId('cuenta_destino_id')
                ->nullable()
                ->after('cuenta_id')
                ->constrained('cuentas')
                ->restrictOnDelete();
        });

        $this->setTipos(['ingreso', 'gasto', 'transferencia']);
    }

    public function down(): void
    {
        DB::table('transacciones')->where('tipo', 'transferencia')->delete();

        $this->setTipos(['ingreso', 'gasto']);

        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cuenta_destino_id');
        });
    }

    private function setTipos(array $tipos): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            $list = collect($tipos)->map(fn (string $tipo) => "'{$tipo}'")->implode(', ');

            DB::statement('ALTER TABLE transacciones DROP CONSTRAINT IF EXISTS transacciones_tipo_check');
            DB::statement("ALTER TABLE transacciones ADD CONSTRAINT transacciones_tipo_check CHECK (tipo IN ({$list}))");

            return;
        }

        Schema::table('transacciones', function (Blueprint $table) use ($tipos) {
            $table->enum('tipo', $tipos)->change();
        });
    }
};
