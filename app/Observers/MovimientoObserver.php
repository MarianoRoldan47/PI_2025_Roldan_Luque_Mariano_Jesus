<?php

namespace App\Observers;

use App\Models\Movimiento;
use App\Models\Estanteria;
use App\Models\Producto;

class MovimientoObserver
{
    public function deleted(Movimiento $movimiento)
    {
        if ($movimiento->estado !== 'confirmado') return;

        switch ($movimiento->tipo) {
            case 'entrada':
                $this->revertirEntrada($movimiento);
                break;
            case 'salida':
                $this->revertirSalida($movimiento);
                break;
            case 'traslado':
                $this->revertirTraslado($movimiento);
                break;
        }
    }

    private function revertirEntrada(Movimiento $movimiento)
    {
        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteria = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteria) {
                $estanteria->capacidad_libre += $movimiento->cantidad;
                $estanteria->save();

                $this->actualizarRelacionProductoEstanteria(
                    $estanteria,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    true
                );
            }
        }

        $producto = Producto::find($movimiento->producto_id);
        if ($producto) {
            $producto->stock_total -= $movimiento->cantidad;
            $producto->save();
        }
    }

    private function revertirSalida(Movimiento $movimiento)
    {
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteria = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteria) {
                $estanteria->capacidad_libre -= $movimiento->cantidad;
                $estanteria->save();

                $this->actualizarRelacionProductoEstanteria(
                    $estanteria,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    false
                );
            }
        }

        $producto = Producto::find($movimiento->producto_id);
        if ($producto) {
            $producto->stock_total += $movimiento->cantidad;
            $producto->save();
        }
    }

    private function revertirTraslado(Movimiento $movimiento)
    {
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre -= $movimiento->cantidad;
                $estanteriaOrigen->save();

                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaOrigen,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    false
                );
            }
        }

        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteriaDestino = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteriaDestino) {
                $estanteriaDestino->capacidad_libre += $movimiento->cantidad;
                $estanteriaDestino->save();

                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaDestino,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    true
                );
            }
        }
    }

    private function actualizarRelacionProductoEstanteria($estanteria, $productoId, $cantidad, $esResta = false)
    {
        $existingPivot = $estanteria->productos()
            ->where('producto_id', $productoId)
            ->first();

        if ($existingPivot) {
            $nuevaCantidad = $esResta
                ? $existingPivot->pivot->cantidad - $cantidad
                : $existingPivot->pivot->cantidad + $cantidad;

            if ($nuevaCantidad <= 0) {
                $estanteria->productos()->detach($productoId);
            } else {
                $estanteria->productos()->updateExistingPivot($productoId, [
                    'cantidad' => $nuevaCantidad
                ]);
            }
        } else if (!$esResta && $cantidad > 0) {
            $estanteria->productos()->attach($productoId, [
                'cantidad' => $cantidad
            ]);
        }
    }
}
