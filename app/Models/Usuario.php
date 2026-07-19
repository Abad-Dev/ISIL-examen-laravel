<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'moneda',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Usuario $usuario) {
            $usuario->moneda = config('money.currency');
        });
    }

    public function getNameAttribute(): string
    {
        return $this->nombre;
    }

    public function cuentas()
    {
        return $this->hasMany(Cuenta::class, 'usuario_id');
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class, 'usuario_id');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'usuario_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'usuario_id');
    }

    public function ultimaTransaccion()
    {
        return $this->hasOne(Transaccion::class, 'usuario_id')->latestOfMany('fecha');
    }

    public function transaccionesPorCategoria()
    {
        return $this->hasManyThrough(
            Transaccion::class,
            Categoria::class,
            'usuario_id',
            'categoria_id',
            'id',
            'id'
        );
    }

    public function presupuestosPorCategoria()
    {
        return $this->hasManyThrough(
            Presupuesto::class,
            Categoria::class,
            'usuario_id',
            'categoria_id',
            'id',
            'id'
        );
    }
}
