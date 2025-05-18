<x-app-layout>
    <div class="container-fluid p-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fs-3">{{ $producto->nombre }}</h1>
                <p class="text-muted">Detalles del producto</p>
            </div>
            <div class="col text-end">
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="d-flex justify-content-center p-3" style="height: 300px;">
                        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                            class="h-100" alt="{{ $producto->nombre }}"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title text-center mb-3">Información General</h4>
                        <ul class="list-unstyled">
                            <li><strong>Código:</strong> {{ $producto->codigo_producto }}</li>
                            <li><strong>Categoría:</strong> {{ $producto->categoria->nombre }}</li>
                            <li><strong>Tipo:</strong> {{ ucfirst($producto->tipo) }}</li>
                            <li><strong>Stock Total:</strong> {{ $producto->stock_total }}</li>
                            <li><strong>Stock Mínimo Para Alerta:</strong> {{ $producto->stock_minimo_alerta }}</li>
                            <li>
                                <strong>Estado:</strong>
                                <span class="badge bg-{{ $producto->necesita_reposicion ? 'danger' : 'success' }}">
                                    {{ $producto->necesita_reposicion ? 'Stock Bajo' : 'Stock OK' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card bg-dark text-white shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Descripción</h5>
                        <p>{{ $producto->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                </div>

                <div class="card bg-dark text-white shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Ubicaciones</h5>
                        @if ($producto->estanterias->isEmpty() || $producto->estanterias->sum('pivot.cantidad') == 0)
                            <p class="text-white">Este producto no está almacenado en ninguna estantería.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estantería</th>
                                            <th>Zona</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->estanterias as $estanteria)
                                            <tr>
                                                <td>{{ $estanteria->nombre }}</td>
                                                <td>{{ $estanteria->zona->nombre }}</td>
                                                <td>{{ $estanteria->pivot->cantidad }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Últimos Movimientos</h5>
                        @if ($producto->movimientos->isEmpty())
                            <p class="text-white">No hay movimientos registrados para este producto.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->movimientos as $movimiento)
                                            <tr>
                                                <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($movimiento->tipo) }}
                                                    </span>
                                                </td>
                                                <td>{{ $movimiento->cantidad }}</td>
                                                <td>{{ $movimiento->usuario->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
