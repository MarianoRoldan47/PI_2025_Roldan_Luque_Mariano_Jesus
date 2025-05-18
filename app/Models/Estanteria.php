<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estanteria extends Model
{
    /** @use HasFactory<\Database\Factories\EstanteriaFactory> */
    use HasFactory;

    protected $fillable = ['zona_id', 'capacidad_maxima', 'capacidad_libre'];

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_estanteria')
                    ->withPivot('cantidad');
    }
}
