<?php
namespace App\Observers;

use App\Models\Movimiento;
use App\Models\Estanteria;
use App\Models\Producto;

class MovimientoObserver
{
    public function created(Movimiento $movimiento)
    {
        if ($movimiento->estado == 'pendiente') return;

        if ($movimiento->estado == 'confirmado') {
            $this->confirmarMovimiento($movimiento);
        }

        if ($movimiento->estado == 'cancelado') return;
    }

    public function updated(Movimiento $movimiento)
    {
        if ($movimiento->estado == 'pendiente') return;

        if ($movimiento->estado == 'confirmado') {
            $this->confirmarMovimiento($movimiento);
        }

        if ($movimiento->estado == 'cancelado') return;
    }

    public function confirmarMovimiento(Movimiento $movimiento)
    {
        if ($movimiento->tipo == 'entrada') {
            $this->procesarEntrada($movimiento);
        }

        if ($movimiento->tipo == 'salida') {
            $this->procesarSalida($movimiento);
        }

        if ($movimiento->tipo == 'traslado') {
            $this->procesarTraslado($movimiento);
        }
    }

    private function procesarEntrada(Movimiento $movimiento)
    {
        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteriaDestino = Estanteria::find($movimiento->ubicacion_destino_id);

            if ($estanteriaDestino && $estanteriaDestino->capacidad_libre >= $movimiento->cantidad) {
                $estanteriaDestino->capacidad_libre -= $movimiento->cantidad;
                $estanteriaDestino->save();
            }
        }

        $producto = Producto::find($movimiento->producto_id);
        if ($producto) {
            $producto->stock_total += $movimiento->cantidad;
            $producto->save();
        }
    }

    private function procesarSalida(Movimiento $movimiento)
    {
        $producto = Producto::find($movimiento->producto_id);
        if ($producto) {
            $producto->stock_total -= $movimiento->cantidad;
            $producto->save();
        }

        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre += $movimiento->cantidad;
                $estanteriaOrigen->save();
            }
        }
    }

    private function procesarTraslado(Movimiento $movimiento)
    {
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre += $movimiento->cantidad;
                $estanteriaOrigen->save();
            }

            $producto = Producto::find($movimiento->producto_id);
            if ($producto) {
                $producto->stock_total -= $movimiento->cantidad;
                $producto->save();
            }
        }

        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteriaDestino = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteriaDestino) {
                $estanteriaDestino->capacidad_libre -= $movimiento->cantidad;
                $estanteriaDestino->save();
            }

            $producto = Producto::find($movimiento->producto_id);
            if ($producto) {
                $producto->stock_total += $movimiento->cantidad;
                $producto->save();
            }
        }
    }
}
