<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    /** @use HasFactory<\Database\Factories\MovimientoFactory> */
    use HasFactory;

    protected $fillable = [
        'producto_id', 'usuario_id', 'tipo', 'cantidad', 'origen_tipo', 'ubicacion_origen_id',
        'destino_tipo', 'ubicacion_destino_id', 'estado', 'fecha_movimiento'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
