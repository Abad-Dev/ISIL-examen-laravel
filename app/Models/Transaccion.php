<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'usuario_id',
        'cuenta_id',
        'categoria_id',
        'tipo',
        'monto',
        'descripcion',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['fecha_desde'])) {
            $query->where('fecha', '>=', $filters['fecha_desde']);
        }

        if (! empty($filters['fecha_hasta'])) {
            $toExclusive = Carbon::parse($filters['fecha_hasta'])->addDay()->toDateString();
            $query->where('fecha', '<', $toExclusive);
        }

        if (! empty($filters['cuenta_id'])) {
            $query->where('cuenta_id', $filters['cuenta_id']);
        }

        if (! empty($filters['categoria_id'])) {
            if ($filters['categoria_id'] === 'none') {
                $query->whereNull('categoria_id');
            } else {
                $query->where('categoria_id', $filters['categoria_id']);
            }
        }

        if (! empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        if (isset($filters['monto_min']) && $filters['monto_min'] !== null && $filters['monto_min'] !== '') {
            $query->where('monto', '>=', $filters['monto_min']);
        }

        if (isset($filters['monto_max']) && $filters['monto_max'] !== null && $filters['monto_max'] !== '') {
            $query->where('monto', '<=', $filters['monto_max']);
        }

        return $query;
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}