<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->decimal('monto_limite', 10, 2);
            $table->tinyInteger('mes'); // 1-12
            $table->year('anio');
            $table->timestamps();

            $table->unique(['usuario_id', 'categoria_id', 'mes', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};