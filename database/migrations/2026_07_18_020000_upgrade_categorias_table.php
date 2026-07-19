<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->renameColumn('color', 'color_hex');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->string('icon', 50)->default('heroicon-o-tag');
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->string('descripcion', 255)->nullable();
        });

        DB::table('categorias')->whereNull('color_hex')->update(['color_hex' => '#8DDA90']);

        Schema::table('categorias', function (Blueprint $table) {
            $table->unique(['usuario_id', 'tipo', 'nombre']);
            $table->index(['usuario_id', 'tipo', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropUnique(['usuario_id', 'tipo', 'nombre']);
            $table->dropIndex(['usuario_id', 'tipo', 'activo']);
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn(['icon', 'activo', 'orden', 'descripcion']);
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->renameColumn('color_hex', 'color');
        });
    }
};
