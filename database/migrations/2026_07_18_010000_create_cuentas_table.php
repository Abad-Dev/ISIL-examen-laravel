<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('nombre', 80);
            $table->enum('tipo', ['efectivo', 'billetera_digital', 'banco', 'otro']);
            $table->decimal('saldo', 10, 2)->nullable();
            $table->string('icon', 50)->nullable();
            $table->char('color_hex', 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
