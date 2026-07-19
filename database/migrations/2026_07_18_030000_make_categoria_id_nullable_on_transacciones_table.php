<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::create('transacciones_tmp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
                $table->foreignId('cuenta_id')->constrained('cuentas')->restrictOnDelete();
                $table->foreignId('categoria_id')->nullable()->constrained('categorias')->restrictOnDelete();
                $table->enum('tipo', ['ingreso', 'gasto']);
                $table->decimal('monto', 10, 2);
                $table->string('descripcion')->nullable();
                $table->date('fecha');
                $table->timestamps();
            });

            DB::statement('
                INSERT INTO transacciones_tmp (id, usuario_id, cuenta_id, categoria_id, tipo, monto, descripcion, fecha, created_at, updated_at)
                SELECT id, usuario_id, cuenta_id, categoria_id, tipo, monto, descripcion, fecha, created_at, updated_at
                FROM transacciones
            ');

            Schema::drop('transacciones');
            Schema::rename('transacciones_tmp', 'transacciones');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE transacciones ALTER COLUMN categoria_id DROP NOT NULL');
        } else {
            Schema::table('transacciones', function (Blueprint $table) {
                $table->foreignId('categoria_id')->nullable()->change();
            });
        }

        Schema::table('transacciones', function (Blueprint $table) {
            $table->foreign('categoria_id')->references('id')->on('categorias')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            DB::table('transacciones')->whereNull('categoria_id')->delete();

            Schema::disableForeignKeyConstraints();

            Schema::create('transacciones_tmp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
                $table->foreignId('cuenta_id')->constrained('cuentas')->restrictOnDelete();
                $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
                $table->enum('tipo', ['ingreso', 'gasto']);
                $table->decimal('monto', 10, 2);
                $table->string('descripcion')->nullable();
                $table->date('fecha');
                $table->timestamps();
            });

            DB::statement('
                INSERT INTO transacciones_tmp (id, usuario_id, cuenta_id, categoria_id, tipo, monto, descripcion, fecha, created_at, updated_at)
                SELECT id, usuario_id, cuenta_id, categoria_id, tipo, monto, descripcion, fecha, created_at, updated_at
                FROM transacciones
            ');

            Schema::drop('transacciones');
            Schema::rename('transacciones_tmp', 'transacciones');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable(false)->change();
            $table->foreign('categoria_id')->references('id')->on('categorias')->restrictOnDelete();
        });
    }
};
