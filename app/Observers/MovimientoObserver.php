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

                // Actualizar relación producto-estantería
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaDestino,
                    $movimiento->producto_id,
                    $movimiento->cantidad
                );
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
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre += $movimiento->cantidad;
                $estanteriaOrigen->save();

                // Actualizar relación producto-estantería (resta)
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaOrigen,
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

    private function procesarTraslado(Movimiento $movimiento)
    {
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre += $movimiento->cantidad;
                $estanteriaOrigen->save();

                // Actualizar relación en origen (resta)
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaOrigen,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    true
                );
            }
        }

        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteriaDestino = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteriaDestino) {
                $estanteriaDestino->capacidad_libre -= $movimiento->cantidad;
                $estanteriaDestino->save();

                // Actualizar relación en destino
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaDestino,
                    $movimiento->producto_id,
                    $movimiento->cantidad
                );
            }
        }
    }

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
        // Revertir cambios en estantería y producto
        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteria = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteria) {
                $estanteria->capacidad_libre += $movimiento->cantidad;
                $estanteria->save();

                // Actualizar o eliminar relación producto-estantería
                $this->actualizarRelacionProductoEstanteria(
                    $estanteria,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    true // es resta
                );
            }
        }

        // Revertir stock del producto
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

                // Restaurar relación producto-estantería
                $this->actualizarRelacionProductoEstanteria(
                    $estanteria,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    false // no es resta
                );
            }
        }

        // Restaurar stock del producto
        $producto = Producto::find($movimiento->producto_id);
        if ($producto) {
            $producto->stock_total += $movimiento->cantidad;
            $producto->save();
        }
    }

    private function revertirTraslado(Movimiento $movimiento)
    {
        // Revertir cambios en origen
        if ($movimiento->origen_tipo === 'estanteria') {
            $estanteriaOrigen = Estanteria::find($movimiento->ubicacion_origen_id);
            if ($estanteriaOrigen) {
                $estanteriaOrigen->capacidad_libre -= $movimiento->cantidad;
                $estanteriaOrigen->save();

                // Restaurar relación en origen
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaOrigen,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    false // no es resta
                );
            }
        }

        // Revertir cambios en destino
        if ($movimiento->destino_tipo === 'estanteria') {
            $estanteriaDestino = Estanteria::find($movimiento->ubicacion_destino_id);
            if ($estanteriaDestino) {
                $estanteriaDestino->capacidad_libre += $movimiento->cantidad;
                $estanteriaDestino->save();

                // Actualizar o eliminar relación en destino
                $this->actualizarRelacionProductoEstanteria(
                    $estanteriaDestino,
                    $movimiento->producto_id,
                    $movimiento->cantidad,
                    true // es resta
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
                // Si la cantidad llega a 0 o menos, eliminar la relación
                $estanteria->productos()->detach($productoId);
            } else {
                // Actualizar la cantidad
                $estanteria->productos()->updateExistingPivot($productoId, [
                    'cantidad' => $nuevaCantidad
                ]);
            }
        } else if (!$esResta && $cantidad > 0) {
            // Solo crear nueva relación si no es resta y la cantidad es positiva
            $estanteria->productos()->attach($productoId, [
                'cantidad' => $cantidad
            ]);
        }
    }
}
