<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    protected $table = 'cuentas';

    protected $fillable = [
        'usuario_id',
        'cuenta_principal_id',
        'nombre',
        'tipo',
        'moneda',
        'saldo',
        'icon',
        'color_hex',
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:2',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'cuenta_id');
    }

    public function transferenciasRecibidas()
    {
        return $this->hasMany(Transaccion::class, 'cuenta_destino_id');
    }

    public function principal()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_principal_id');
    }

    public function vinculadas()
    {
        return $this->hasMany(Cuenta::class, 'cuenta_principal_id');
    }

    /**
     * Cuenta que guarda el saldo: la principal si está vinculada, o ella misma.
     */
    public function titularSaldo(): Cuenta
    {
        return $this->cuenta_principal_id ? $this->principal : $this;
    }

    public function getSaldoActualAttribute(): string
    {
        return (string) ($this->titularSaldo()->saldo ?? '0.00');
    }

    public function scopeConSaldoPropio(Builder $query): Builder
    {
        return $query->whereNull('cuenta_principal_id');
    }
}
