<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_producto', 'nombre', 'descripcion', 'imagen', 'tipo',
        'stock_total', 'stock_minimo_alerta', 'categoria_id'
    ];

    protected $dates = ['deleted_at'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function estanterias()
    {
        return $this->belongsToMany(Estanteria::class, 'producto_estanteria')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
