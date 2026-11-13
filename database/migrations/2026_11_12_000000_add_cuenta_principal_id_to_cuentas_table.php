<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->foreignId('cuenta_principal_id')
                ->nullable()
                ->after('usuario_id')
                ->constrained('cuentas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cuenta_principal_id');
        });
    }
};
