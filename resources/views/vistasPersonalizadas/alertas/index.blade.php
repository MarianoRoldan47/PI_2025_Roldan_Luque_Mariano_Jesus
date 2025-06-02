<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">ALERTAS DE STOCK</h1>
                <p class="text-muted">Productos con stock bajo o agotado</p>
            </div>
        </div>

        <div class="card bg-dark shadow-sm">
            <div class="card-body p-0">
                @if ($alertas->isEmpty())
                    <div class="py-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-success">¡Todo en orden!</h4>
                        <p class="text-white mb-4">No hay productos con niveles de stock por debajo del mínimo
                            establecido.</p>
                        <div>
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-box me-1"></i> Ver productos
                            </a>
                            <a href="{{ route('productos.pdf.inventario') }}" target="_blank"
                                class="btn btn-outline-info">
                                <i class="fas fa-file-pdf me-1"></i> Generar informe
                            </a>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th class="text-center">Stock Mínimo</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alertas as $alerta)
                                    <tr>
                                        <td class="small">
                                            <div class="d-flex flex-column">
                                                <span>{{ $alerta->fecha_alerta->format('d/m/Y') }}</span>
                                                <span
                                                    class="text-muted">{{ $alerta->fecha_alerta->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('productos.show', $alerta->producto_id) }}"
                                                class="d-flex align-items-center text-decoration-none text-white">
                                                @if ($alerta->producto->imagen)
                                                    <img src="{{ asset('storage/' . $alerta->producto->imagen) }}"
                                                        class="rounded-2 me-2" width="40" height="40"
                                                        alt="{{ $alerta->producto->nombre }}">
                                                @else
                                                    <img src="{{ asset('img/default-product.png') }}"
                                                        class="rounded-2 me-2" width="40" height="40"
                                                        alt="{{ $alerta->producto->nombre }}">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $alerta->producto->nombre }}</div>
                                                    <div class="small">{{ $alerta->producto->codigo_producto }}</div>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-danger">{{ $alerta->stock_actual }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="fw-bold text-warning">{{ $alerta->producto->stock_minimo_alerta }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $stockMinimo = $alerta->producto->stock_minimo_alerta;
                                                $porcentaje =
                                                    $stockMinimo > 0 ? ($alerta->stock_actual / $stockMinimo) * 100 : 0;
                                            @endphp

                                            @if ($alerta->stock_actual == 0)
                                                <span class="badge text-bg-danger">Sin Stock</span>
                                            @elseif ($porcentaje <= 30)
                                                <span class="badge text-bg-danger">Crítico</span>
                                            @elseif ($porcentaje <= 70)
                                                <span class="badge text-bg-warning">Bajo</span>
                                            @else
                                                <span class="badge text-bg-warning">Cercano al mínimo</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('movimientos.create', ['producto_id' => $alerta->producto_id, 'tipo' => 'entrada']) }}"
                                                    class="btn btn-success" title="Registrar entrada">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                                <a href="{{ route('alertas.show', $alerta->id) }}" class="btn btn-info"
                                                    title="Ver alerta">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
