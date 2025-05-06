<?php

namespace App\Observers;

use App\Models\AlertaStock;
use App\Models\Producto;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto): void
    {
        $this->actualizarAlertaStock($producto);
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto): void
    {
        $this->actualizarAlertaStock($producto);
    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        AlertaStock::where('producto_id', $producto->id)->delete();
    }

    /**
     * Handle the Producto "restored" event.
     */
    public function restored(Producto $producto): void
    {
        $this->actualizarAlertaStock($producto);
    }

    /**
     * Handle the Producto "force deleted" event.
     */
    public function forceDeleted(Producto $producto): void
    {
        AlertaStock::where('producto_id', $producto->id)->delete();
    }

    protected function actualizarAlertaStock(Producto $producto)
    {
        if ($producto->stock_total < $producto->stock_minimo_alerta) {
            $alertaStock = AlertaStock::firstOrNew([
                'producto_id' => $producto->id
            ]);

            $alertaStock->stock_actual = $producto->stock_total;
            $alertaStock->fecha_alerta = $alertaStock->fecha_alerta ?? now();
            $alertaStock->save();
        } else {
            AlertaStock::where('producto_id', $producto->id)->delete();
        }
    }
}
