<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    /** @use HasFactory<\Database\Factories\ZonaFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    public function estanterias()
    {
        return $this->hasMany(Estanteria::class);
    }
}
