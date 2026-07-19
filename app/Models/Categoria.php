<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'tipo',
        'icon',
        'color_hex',
        'activo',
        'orden',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'categoria_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'categoria_id');
    }

    public function ultimaTransaccion()
    {
        return $this->hasOne(Transaccion::class, 'categoria_id')->latestOfMany('fecha');
    }
}
