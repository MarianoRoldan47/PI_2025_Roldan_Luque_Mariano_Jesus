<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaStock extends Model
{
    use HasFactory;

    protected $table = 'alerta_stock';

    protected $fillable = [
        'producto_id',
        'stock_actual',
        'fecha_alerta',
    ];

    protected $casts = [
        'fecha_alerta' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
