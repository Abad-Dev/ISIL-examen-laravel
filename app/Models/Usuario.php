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
}