<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    /** @use HasFactory<\Database\Factories\MovimientoFactory> */
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'user_id',
        'tipo',
        'cantidad',
        'origen_tipo',
        'ubicacion_origen_id',
        'destino_tipo',
        'ubicacion_destino_id',
        'estado',
        'fecha_movimiento'
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Estanteria::class, 'ubicacion_origen_id');
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Estanteria::class, 'ubicacion_destino_id');
    }
}
