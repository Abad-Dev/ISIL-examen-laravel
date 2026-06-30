<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'moneda',
    ];

    protected $hidden = [
        'password',
    ];

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

    // hasOne: de todas las transacciones del usuario, solo la más reciente por fecha.
    public function ultimaTransaccion()
    {
        return $this->hasOne(Transaccion::class, 'usuario_id')->latestOfMany('fecha');
    }

    // hasManyThrough: las transacciones del usuario accedidas A TRAVÉS de sus categorias
    // (usuarios -> categorias.usuario_id -> transacciones.categoria_id).
    public function transaccionesPorCategoria()
    {
        return $this->hasManyThrough(
            Transaccion::class,
            Categoria::class,
            'usuario_id',   // FK en categorias que apunta a usuarios
            'categoria_id', // FK en transacciones que apunta a categorias
            'id',           // PK local en usuarios
            'id'            // PK local en categorias
        );
    }

    // hasManyThrough: los presupuestos del usuario a través de sus categorias.
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